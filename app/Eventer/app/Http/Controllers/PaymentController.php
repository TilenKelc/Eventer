<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use DateTime;

use App\Mail\OrderMail;
use App\Mail\InfoMail;
use App\Address;
use App\User;
use App\Rent;
use App\Product;
use App\Reservation;
use App\Category;

use Log;
class PaymentController extends Controller
{
    public function showPayment(Request $request){
        if(Auth::user()->isAgent()){
            $rent = Rent::find($request->id);

            $rent->braintree_transaction_id = 'interna rezervacija';
            $rent->payment_transaction_timestamp = date("Y/m/d h:i:s");
            $rent->status = "successfully_paid";

            $product_ids = array();
            foreach(json_decode($rent->reservation_ids) as $id){
                $reservation = Reservation::find($id);
                array_push($product_ids, $reservation->product_id);
            }
            $products = Product::whereIn('id', $product_ids)->get();

            $rent->equipment_ids = json_encode($product_ids);
            $rent->save();

            session(["rent_id" => null, "rental_from" => null, "rental_to" => null]);
            return redirect('/payment_redirect');
        }else{
            $rent = Rent::find($request->id);

            // extra za 3dsecure
            $customer = Auth::user();
            $address = Address::find($customer->address_id);

            $tmp_reservation = Reservation::find(json_decode($rent->reservation_ids)[0]);
            $tmp_product = Product::find($tmp_reservation->product_id);
            $amount = Category::find($tmp_product->category_id)->amount;
            // extra za 3dsecure

            $braintree = config('braintree');
            $clientToken = $braintree->clientToken()->generate();

            return view('rent.payment', [
                'clientToken' => $clientToken,
                'rent' => $rent,

                // extra za 3d secure
                'customer' => $customer,
                'address' => $address,
                'amount' => $amount
                // extra za 3d secure
            ]);
        }
    }

    public function processPayment(Request $request){
        $payload = $request->input('payload', false);
        $nonce = $payload['nonce'];

        $rent = Rent::find($payload["rentID"]);
        $customer = User::find($rent->customer_id);

        $braintree = config('braintree');
        $status = $braintree->transaction()->sale([
            'amount' => 5,
            'paymentMethodNonce' => $nonce,
            'billing' => [
                'firstName' => $customer->name,
                'lastName' => $customer->surname
            ],
            'customer' => [
                'firstName' => $customer->name,
                'lastName' => $customer->surname
            ],
            'options' => [
              'submitForSettlement' => True
            ]
        ]);

        if($status->success){
            $rent->braintree_transaction_id = $status->transaction->id;
            $rent->payment_transaction_timestamp = date("Y/m/d h:i:s");
            $rent->status = "successfully_paid";

            $product_ids = array();
            foreach(json_decode($rent->reservation_ids) as $id){
                $reservation = Reservation::find($id);
                array_push($product_ids, $reservation->product_id);
            }
            $products = Product::whereIn('id', $product_ids)->get();

            $rental_to = new DateTime($rent->rental_to);
            $rental_from = new DateTime($rent->rental_from);
            $interval = $rental_from->diff($rental_to);
            $num_of_days = $interval->format('%a');
            $num_of_days += 1;

            $total_price = 0;
            foreach($products as $product){
                $total_price += $product->price_per_day * $num_of_days;
            }

            if($num_of_days >= 8){
                $total_price = $total_price - ($total_price * 0.35);

            }else if($num_of_days >= 3 && $num_of_days <= 7){
                $total_price = $total_price - ($total_price * 0.3);

            }else if($num_of_days == 2){
                $total_price = $total_price - ($total_price * 0.2);
            }

            $rent->equipment_ids = json_encode($product_ids);
            $rent->total_price = $total_price;
            $rent->save();

            session(["rent_id" => null, "rental_from" => null, "rental_to" => null]);

            $products = array();
            foreach(json_decode($rent->equipment_ids) as $id){
                $product = Product::find($id);
                array_push($products, $product);
            }

            // TODO - Uncomment sent mail
            //Mail::to($customer->email)->send(new OrderMail($customer, $rent, $products));
            //Mail::to('tilen.kelc@gmail.com')->send(new InfoMail($customer, $rent, $products));
        }

        return response()->json($status);
    }

    public function refundPayment($rent_id){
        $rent = Rent::find($rent_id);
        $braintree = config('braintree');

        $result = $braintree->transaction()->refund($rent->braintree_transaction_id);

        if(!empty($result->success) && $result->success){
            $rent->braintree_refund_transaction_id = $result->transaction->id;
            $rent->refund_transaction_timestamp = date("Y/m/d h:i:s");
            $rent->agent_id = Auth::id();
            $rent->save();
        }else{
            $result = $braintree->transaction()->void($rent->braintree_transaction_id);

            if($result->success){
                $rent->braintree_refund_transaction_id = $rent->braintree_transaction_id;
                $rent->refund_transaction_timestamp = date("Y/m/d h:i:s");
                $rent->agent_id = Auth::id();
                $rent->save();
            }else{
                session(['errorMssg' => 'Napaka pri preklicu transakcije']);
                if(Auth::user()->isStaff()){
                    return redirect('/rent/show/all');
                }else{
                    return redirect('/rent/edit/' . $rent->id);
                }
            }
        }
        session(['successMssg' => 'Transakcija se je izvedla uspešno']);
        if(Auth::user()->isStaff()){
            return redirect('/rent/show/all');
        }else{
            return redirect('/rent/edit/' . $rent->id);
        }
    }


    public function refundRemovePayment($rent_id){
        $rent = Rent::find($rent_id);
        $braintree = config('braintree');

        $result = $braintree->transaction()->refund($rent->braintree_transaction_id);

        if(!empty($result->success) && $result->success){
            $rent->braintree_refund_transaction_id = $result->transaction->id;
            $rent->refund_transaction_timestamp = date("Y/m/d h:i:s");
            $rent->agent_id = Auth::id();
            $rent->save();
        }else{
            $result = $braintree->transaction()->void($rent->braintree_transaction_id);

            if($result->success){
                $rent->braintree_refund_transaction_id = $rent->braintree_transaction_id;
                $rent->refund_transaction_timestamp = date("Y/m/d h:i:s");
                $rent->agent_id = Auth::id();
                $rent->save();
            }else{
                session(['errorMssg' => 'Napaka pri preklicu transakcije']);
                if(Auth::user()->isStaff()){
                    return back();
                }
            }
        }
        session(['successMssg' => 'Transakcija se je izvedla uspešno']);
        if(Auth::user()->isStaff()){
            return back();
        }
    }



    public function redirect(){
        session(['successMssg' => 'Plačilo je bilo uspešno, hvala za vašo rezervacijo!']);
        return view("rent.thank_you");
    }

    /*
    public function test(){
        $rent = Rent::find(19);
        $customer = Auth::user();
        $products = array();
        foreach(json_decode($rent->equipment_ids) as $id){
            $product = Product::find($id);
            array_push($products, $product);
         }

        Mail::to($customer->email)->send(new OrderMail($customer, $rent, $products));
        Mail::to('rent@11-11.sii')->send(new InfoMail($customer, $rent, $products));
    }
    */
}
