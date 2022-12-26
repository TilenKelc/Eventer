<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use DateTime;

use App\Mail\NotificationMail;
use App\Size;
use App\Category;
use App\Product;
use App\Rent;
use App\User;
use App\Reservation;
use Log;

class RentController extends Controller
{
    public function editRent(Request $request){
        $rent = Rent::find($request->id);
        //$products = Product::where('deleted', false)->orderBy('created_at', 'asc')->get();
        $equipment_ids = json_decode($rent->equipment_ids);

        $category = null;
        $products = null;
        if($equipment_ids == null){
            $reservation_ids = json_decode($rent->reservation_ids);
            $product_ids = Reservation::whereIn('id', $reservation_ids)->pluck('product_id');
            $products = Product::whereIn('id', $product_ids)->get();
            $category = Category::find($products[0]->category_id);
        }else{
            $products = Product::where('deleted', false)->whereIn('id', json_decode($rent->equipment_ids))->get();
            $category = Category::find($products[0]->category_id);
        }
        $customer = User::where('id', $rent->customer_id)->get()->first();

        return view('rent.add',[
            "rent" => $rent,
            //"user" => Auth::user(),
            "category" => $category,
            "customer" => $customer,
            "products" => $products,
        ]);
    }

    public function saveUpdatedRent(Request $request){
        $rent_id = $request->id;

        if(Auth::user()->isStaff()){
            switch($request->input('action')){
                case 'cancel':
                    $rent = Rent::find($rent_id);
                    $rent->status = 'canceled';
                    $rent->canceled_by = Auth::id();
                    $rent->canceled_timestamp = date('Y-m-d H:i:s');
                    $rent->save();

                    $customer = User::find($rent->customer_id);
                    if($customer->isAgent()){
                        session(['successMssg' => 'Preklic naročila se je izvedel uspešno']);
                        return redirect('/rent/show/all');
                    }

                    return redirect()->route('payment.refund', ['rent_id' => $rent_id]);
                    break;
                case 'ready':
                    $rent = Rent::find($rent_id);
                    $rent->ready_for_take_over = true;
                    $rent->status = "in_progress";

                    /* če je rental_from jutrišnji dan, pomeni da notification še ni bil poslan. Poslati ga moramo zdaj */
                    /*$datum_renta_string = date("d-m-Y", strtotime($rent->rental_from));
                    $datum_jutri = new \DateTime('tomorrow');
                    $datum_jutri_string = $datum_jutri->format('d-m-Y');

                    $customer = User::find($rent->customer_id);

                    if($datum_renta_string == $datum_jutri_string){
                      //Mail::to($customer->email)->send(new NotificationMail($customer, $rent)); TODO UNCOMMENT
                    }*/

                    $rent->save();

                    $customer = User::find($rent->customer_id);

                    //if(date("h") > "07"){
                        // Mail::to($customer->email)->send(new NotificationMail($customer, $rent)); TODO uncomment
                    //}

                    return view('agent.show', [
                        'status' => 'all'
                    ])->with("successMssg", 'Rezervacija uspešno posodobljena');
                    break;
                case 'refund':
                    $rent = Rent::find($rent_id);
                    $rent->status = 'completed';
                    $rent->save();

                    if($rent->braintree_transaction_id == 'interna rezervacija'){
                        session(['successMssg' => 'Transakcija se je izvedla uspešno']);
                        return back();
                    }
                    
                    return redirect()->route('payment.refund', ['rent_id' => $rent_id]);
                    break;
            }
        }else{
            switch($request->input('action')){
                case 'cancel':
                    $rent = Rent::find($rent_id);
                    $rent->status = 'canceled';
                    $rent->canceled_by = Auth::id();
                    $rent->canceled_timestamp = date('Y-m-d H:i:s');
                    $rent->save();

                    if($rent->braintree_transaction_id == 'interna rezervacija'){
                        session(['successMssg' => 'Transakcija se je izvedla uspešno']);
                        return back();
                    }

                    $customer = User::find($rent->customer_id);
                    return redirect()->route('payment.refund', ['rent_id' => $rent_id]);
                    break;
            }
        }
    }

    public function showCart(){
        if(session('rent_id') != null){
            $rent = Rent::find(session('rent_id'));
            $customer = User::find($rent->customer_id);

            $product_ids = array();
            foreach(json_decode($rent->reservation_ids) as $id){
                $reservation = Reservation::find($id);
                array_push($product_ids, $reservation->product_id);
            }
            $products = Product::whereIn('id', $product_ids)->get();
            $category = Category::find($products[0]->category_id);

            return view('cart.show', [
                "rent" => $rent,
                "rent_id" => $rent->id,
                "products" => $products,
                "customer" => $customer,
                "category" => $category
            ]);
        }else{
            return view('cart.show', [
                "rent" => null,
                "rent_id" => -1
            ]);
        }
    }

    public function showUserRents(){
        return view('rent.show_all');
    }

    public function removeFromCart($prod_id){

      if(session('rent_id') != null){
        $rent = Rent::find(session('rent_id'));

        $rezervations_ids_on_rent = json_decode($rent->reservation_ids);
        foreach($rezervations_ids_on_rent as $key => $id){
          $reservation = Reservation::find($id);
          if($reservation->product_id == $prod_id){
            $reservation->delete();
            unset($rezervations_ids_on_rent[$key]);
          }
        }

        $new_reservation_ids_string = [];
        foreach($rezervations_ids_on_rent as $id){
            array_push($new_reservation_ids_string, $id);
        }

        $rent->reservation_ids = json_encode($new_reservation_ids_string);
        $rent->save();

        return back()->with('successMssg', 'Miza uspešno odstranjen iz košarice!');
      }
    }



  public function getRemoveRentalView($id){

    $rent = Rent::find($id);
    $customer = User::where('id', $rent->customer_id)->get()->first();

    return view('rent.remove',[
        "rent" => $rent,
        "customer" => $customer
    ]);
  }


  public function postRemoveRental($id){
    // 1. spremenimo status, canceled_by in canceled_timestamp
    // 2. ker gre za izbris, iz koledarja rezervacij vržemo vse reservationse ki se navezujejo na ta rent

    $rent = Rent::find($id);

    $reservation_ids = json_decode($rent->reservation_ids);
    foreach ($reservation_ids as $reservation_id) {
      $reservation = Reservation::find($reservation_id);
      $reservation->delete();
    }

    $rent->status = 'canceled';
    $rent->reservation_ids = null;
    $rent->canceled_by = Auth::id();
    $rent->canceled_timestamp = date('Y-m-d H:i:s');
    $rent->save();

    return redirect('/rent/show/canceled')->with("successMssg", 'Rent uspešno izbrisan/preklican.');

  }
}
