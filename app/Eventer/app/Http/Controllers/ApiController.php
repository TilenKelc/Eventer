<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

use App\Category;
use App\Product;
use App\User;
use App\Address;
use App\Rent;
use App\Reservation;
use Log;

class ApiController extends Controller
{
    public function loginApi(Request $request){
        $credentials = $request->only('email', 'password');

        if(!Auth::attempt($credentials)){
            return response()->json([
                'message' => 'Invalid login details'
            ], 401);
        }

        $user = User::where('email', $request['email'])->firstOrFail();

        return response()->json($user->api_token);
    }

    public function registerApi(Request $request){
        Log::info($request);

        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'surname' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8'],
            'phone_number' => ['required', 'string', 'min:8'],
            'street' => ['required', 'string'],
            'city' => ['required', 'string'],
            'postal_code' => ['required', 'string', 'regex:/([1-9][0-9]{3})/'],
            // 'country_code' => ['required', 'string'],
        ]);

        if ($validator->fails()) {
            Log::info($validator->messages()->get('*'));
            return response()->json([$validator->messages()->get('*')]);
        }
        
        $address_check = Address::where('street', $request->street)
            ->where('city', $request->city)
            ->where('postal_code', $request->postal_code)
            ->where('country_code', 'SL')->first();

        $address_id = null;
        if($address_check === null){
            $address = new Address();
            $address->street = $request->street;
            $address->city = $request->city;
            $address->postal_code = $request->postal_code;
            $address->country_code = 'SL';
            $address->save();

            $address_id = $address->id;
        }else{
            $address_id = $address_check->id;
        }

        $user = new User();
        $user->name = $request->name;
        $user->surname = $request->surname;
        $user->email = $request->email;
        $user->phone_number = $request->phone_number;
        $user->password = Hash::make($request->password);
        $user->address_id = $address_id;
        $user->api_token = Str::random(60);
        $user->save();
        
        Log::info("User created");

        return response()->json(["token" => $user->api_token]);
    }

    public function getCategoriesApi(Request $request){
        $categories = Category::where('deleted', false)->get();
        return response()->json($categories);
    }

    public function getCategoryProductsApi(Request $request){
        $products = Product::where('deleted', false)->where('category_id', $request->id)->get();
        return response()->json($products);
    }

    public function getUserInfoApi(Request $request){
        return response()->json($request->user());
    }

    public function saveUserInfoApi(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'surname' => ['required', 'string', 'max:255'],
            'phone_number' => ['required', 'string', 'min:8'],
            'street' => ['required', 'string'],
            'city' => ['required', 'string'],
            'postal_code' => ['required', 'string'],
        ]);

        if($validator->fails()) {
            Log::info($validator->messages()->get('*'));
            return response()->json([$validator->messages()->get('*')]);
        }

        $address_check = Address::where('street', $request->street)->where('city', $request->city)
            ->where('postal_code', $request->postal_code)->where('country_code', 'SL')->first();

        $address_id = null;
        if($address_check === null){
            $address = new Address();
            $address->street = $request->street;
            $address->city = $request->city;
            $address->postal_code = $request->postal_code;
            $address->country_code = 'SL';
            $address->save();

            $address_id = $address->id;
        }else{
            $address_id = $address_check->id;
        }

        $user = User::find($request->user()->id);
        $user->name = $request->name;
        $user->surname = $request->surname;
        $user->address_id = $address_id;
        $user->phone_number = $request->phone_number;
        $user->save();

        return response()->json($user);
    }

    public function getUserRentals(Request $request){
        $all_rents = Rent::where('customer_id', $request->user()->id)->get();

        $return_rents = [];
        foreach($all_rents as $rent){
            $category = "";
            $equipment_ids = json_decode($rent->equipment_ids);
            if($equipment_ids == null){
                $reservation_ids = json_decode($rent->reservation_ids);
                $reservation = Reservation::find($reservation_ids[0]);

                $product = Product::find($reservation->product_id);
                $category = Category::find($product->category_id)->name;
            }else{
                $product = Product::find($equipment_ids[0]);
                $category = Category::find($product->category_id)->name;
            }

            $products = [];
            $equipment_ids = json_decode($rent->equipment_ids);
            if($equipment_ids == null){
                $reservation_ids = json_decode($rent->reservation_ids);
                foreach($reservation_ids as $id){
                    $reservation = Reservation::find($id);
                    $product = Product::find($reservation->product_id);
                    array_push($products, $product->name);
                }
            }else{
                foreach($equipment_ids as $id){
                    $product = Product::find($id);
                    array_push($products, $product->name);
                }
            }

            $tmp = [
                "category_name" => $category,
                "subcategories" => $products,
                "date" => date('d.m.Y', strtotime($rent->rental_from)),
                "termin" => date('H:00', strtotime($rent->rental_from)) . ' - ' . date('H:00', strtotime($rent->rental_to)),
                "status" => $rent->status,
                "created_at" => date("H:i d.m.Y", strtotime($rent->created_at))
            ];

            array_push($return_rents, $tmp);
        }
        
        return response()->json($return_rents);
    }

    public function getRentInfo(Request $request){
        $rent = Rent::find($request->id);
        $user = $request->user();

        $category = null;
        $equipment_ids = json_decode($rent->equipment_ids);
        if($equipment_ids == null){
            $reservation_ids = json_decode($rent->reservation_ids);
            $reservation = Reservation::find($reservation_ids[0]);

            $product = Product::find($reservation->product_id);
            $category = Category::find($product->category_id);
        }else{
            $product = Product::find($equipment_ids[0]);
            $category = Category::find($product->category_id);
        }
        $address = Address::find($category->address_id);
        
        return response()->json([
            "status" => $rent->status,
            "category" => [
                "name" => $category->name,
                "street" => $address->street,
                "city" => $address->postal_code . ' ' . $address->city,
            ],
            "customer" => [
                "name" => $user->name,
                "surname" => $user->surname,
                "email" => $user->email,
                "phone_number" => $user->phone_number
            ],
            "date" => date('d.m.Y', strtotime($rent->rental_from)),
            "termin" => date('H:00', strtotime($rent->rental_from)) . ' - ' . date('H:00', strtotime($rent->rental_to)),
        ]);
    }

    public function getFullDates(Request $request){
        $reservations = Reservation::where("product_id", $request->id)->get();

        $dates = [];
        foreach($reservations as $reservation){
            array_push($dates, [
                "date" => date('Y-m-d', strtotime($reservation->date_from)),
                "time_from" => date('H:i', strtotime($reservation->date_from)),
                "time_to" => date('H:i', strtotime($reservation->date_to)),
            ]);
        }

        return response()->json($dates);
    }

    public function payRent(Request $request){
        $validator = Validator::make($request->all(), [
            'id' => ['required'],
            'date' => ['required'],
            'time_from' => ['required'],
            'time_to' => ['required'],
            'card_num' => ['required', 'regex:/^([1-9][0-9]{3})[-]([0-9]{4})[-]([0-9]{4})[-]([0-9]{4})$/'],
            'valid' => ['required', 'regex:/^(0?[1-9]|1[012])[\/]([0-9]{2})$/'],
            'ccv' => ['required', 'regex:/^([1-9][0-9]{2})$/'],
        ]);

        if($validator->fails()) {
            Log::info($validator->messages()->get('*'));
            return response()->json([$validator->messages()->get('*')]);
        }

        $date_from = $request->date .' '. $request->time_from;
        $date_to = $request->date .' '. $request->time_to;

        $reservation = new Reservation();
        $reservation->product_id = $request->id;
        $reservation->date_from = $date_from;
        $reservation->date_to = $date_to;
        $reservation->save();

        $rent = new Rent();
        $rent->customer_id = $request->user()->id;
        $rent->reservation_ids = json_encode(array($reservation->id));
        $rent->rental_to = $date_from;
        $rent->rental_from = $date_to;
        $rent->status = 'successfully_paid';

        $rent->braintree_transaction_id = "interna rezervacija";
        $rent->payment_transaction_timestamp = date('Y-m-d h:i:s');
        
        $product_ids = array();
        foreach(json_decode($rent->reservation_ids) as $id){
            $reservation = Reservation::find($id);
            array_push($product_ids, $reservation->product_id);
        }
        $products = Product::whereIn('id', $product_ids)->get();

        $rent->equipment_ids = json_encode($product_ids);
        $rent->save();

        return response()->json(["status" => 200]);
    }
}
