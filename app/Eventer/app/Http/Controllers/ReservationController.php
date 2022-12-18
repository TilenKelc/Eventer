<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

use App\Reservation;
use App\Product;
use App\Rent;
use App\Size;
use Log;

class ReservationController extends Controller
{
    public function show(Request $request){
        if(session()->get("rent_id") != null){
            ReservationController::delete();
        }

        $product = Product::find($request->id);
        return view('reservation.show', [
            "product" => $product,
        ]);
    }

    public function getFullDates(Request $request){
        $dates = Reservation::where("product_id", $request->product_id)->get();
        return response()->json($dates);
    }

    public function addNewReservation(Request $request){
        $reservation = new Reservation();
        $reservation->product_id = $request->product_id;
        $reservation->date_from = $request->start;
        $reservation->date_to = $request->end;
        $reservation->save();

        $rent = new Rent();
        $rent->customer_id = Auth::id();
        $rent->reservation_ids = json_encode(array($reservation->id));
        $rent->rental_to = $reservation->date_to;
        $rent->rental_from = $reservation->date_from;
        $rent->save();

        session(["rent_id" => $rent->id, "rental_from" => $reservation->date_from, "rental_to" => $reservation->date_to]);
        return response()->json(["status" => "done"]);
    }

    public function delete(){
        if(session()->get("rental_from") != null && session()->get("rental_to") != null && session()->get('rent_id') == null){
            session(["rent_id" => NULL, "rental_from" => NULL, "rental_to" => NULL]);
        }else{
            $rent = Rent::find(session()->get("rent_id"));
            session(["rent_id" => NULL, "rental_from" => NULL, "rental_to" => NULL]);

            $reservation_ids = json_decode($rent->reservation_ids);
            foreach($reservation_ids as $id){
                $reservation = Reservation::find($id);
                $reservation->delete();
            }
            $rent->delete();
        }
    }

    public function addProductToReservation(Request $request){
        $product_id = $request->id;
        $rent = null;
        if(session()->get("rental_to") != null && session()->get("rental_from") && session()->get('rent_id') == null){
            $reservation = new Reservation();

            $reservation->product_id = $product_id;
            $reservation->date_from = session()->get('rental_from');
            $reservation->date_to = session()->get('rental_to');
            $reservation->save();

            $rent = new Rent();
            $rent->customer_id = Auth::user()->id;
            $rent->reservation_ids = json_encode(array($reservation->id));
            $rent->contract_issued = false;
            $rent->return_confirmation_issued = false;
            $rent->rental_to = $reservation->date_to;
            $rent->rental_from = $reservation->date_from;
            $rent->save();
            session(['rent_id' => $rent->id, 'successMssg' => 'Artikel je bil uspešno dodan']);
        }else{
            $rent = Rent::find(session()->get("rent_id"));

            $reservation_ids = json_decode($rent->reservation_ids);

            $checkIfExists = true;
            foreach($reservation_ids as $id){
                $reservation = Reservation::find($id);
                if($reservation->product_id == $product_id){
                    session(["errorMssg" => "Artikel je že bil dodan"]);
                    $checkIfExists = false;
                    break;
                }
            }

            if($checkIfExists){
                $reservation = new Reservation();
                $reservation->product_id = $product_id;
                $reservation->date_from = session()->get("rental_from");
                $reservation->date_to = session()->get("rental_to");
                $reservation->save();

                array_push($reservation_ids, $reservation->id);
                $rent->reservation_ids = json_encode($reservation_ids);
                $rent->save();
                session(["successMssg" => "Artikel je bil uspešno dodan"]);
            }
        }

        return back();
    }

    public function setReservationSession(Request $request){
        $validator = Validator::make($request->all(), [
            'set_from_date' => 'required',
            'set_to_date' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect('/')
                ->withInput()
                ->withErrors($validator);
        }

        $set_from_date = date_format(date_create($request->set_from_date), "Y-m-d");
        $set_to_date = date_format(date_create($request->set_to_date), "Y-m-d");

        session(['rental_from' => $set_from_date, 'rental_to' => $set_to_date]);
        
        // return back();
        return redirect('/products/bydate');
    }

    /*
    public function clearReservationSearch(){
        $rent_id = session()->get("rent_id");
        $reservation = Reservation::find($rent_id);
        $rent = Rent::find($rent_id);
        session(["rent_id" => NULL, "rental_from" => NULL, "rental_to" => NULL]);
        $reservation->delete();
        $rent->delete();
    }
    */

  }
