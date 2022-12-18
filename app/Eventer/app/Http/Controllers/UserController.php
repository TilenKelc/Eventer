<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

use Yajra\Datatables\Datatables;
use App\User;
use App\Address;
use App\Rent;
use App\Product;
use App\Size;
use App\Reservation;

use Log;

class UserController extends Controller
{
    public function showEdit(Request $request){
        $user = User::find($request->id);
        return view('user.edit', [
            'user' => $user
        ]);
    }

    public function saveUpdatedUser(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'surname' => 'required',
            'street' => 'required',
            'city' => 'required',
            'postal_code' => 'required',
            'country_code' => 'required',
            'phone_num' => 'required'
        ]);

        if($validator->fails()) {
            return redirect('/user/edit/' . $request->id)
                ->withInput()
                ->withErrors($validator);
        }

        $address_check = Address::where('street', $request->street)->where('city', $request->city)
            ->where('postal_code', $request->postal_code)->where('country_code', $request->country_code)->first();

        $address_id = null;
        if($address_check === null){
            $address = new Address();
            $address->street = $request->street;
            $address->city = $request->city;
            $address->postal_code = $request->postal_code;
            $address->country_code = $request->country_code;
            $address->save();

            $address_id = $address->id;
        }else{
            $address_id = $address_check->id;
        }

        $user = User::find($request->id);
        $user->name = $request->name;
        $user->surname = $request->surname;
        $user->address_id = $address_id;
        $user->phone_number = $request->phone_num;
        $user->save();

        session(['successMssg' => 'Osebni podatki uspešno posodobljeni']);
        return redirect("/user/edit/$user->id");
    }

    public function showAll(){
        return view('user.show');
    }

    public function getAllUsers(){
        $user = User::where("deleted", false)->where("user_role", "customer")->orderBy('created_at', 'asc')->get();
        return Datatables::of($user)
            ->editColumn('name', function($user){
                return $user->name . ' ' . $user->surname;
            })
            ->editColumn('email', function($user){
                return $user->email;
            })
            ->editColumn('phone_number', function($user){
                if($user->phone_number == null){
                    return "Ta podatek še ni izpolnjen";
                }else{
                    return $user->phone_number;
                }
            })
            ->editColumn('address_id', function($user){
                if($user->address_id == null){
                    return "Ta podatek še ni izpolnjen";
                }else{
                    $address = $user->getAddress();
                    return $address['street'] . '<br>' . $address['city'] . '<br>' . $address['postal_code'] . '<br>' . $address['country_code'];
                }
            })
            ->addColumn('edit', function ($user) {
                if(Auth::user()->isAdmin()){
                    return '<a href="/user/edit/' .$user->id. '">Posodobi</a>';
                }
                return '<a href="javascript:void(0)" class="demo">Posodobi</a>';
            })
            ->rawColumns(['edit', 'address_id'])
            ->make(true);
    }

    public function getUserRentals(Request $request){
        //$rent = Rent::where('customer_id', Auth::id())->get();
        $rent = Rent::where('customer_id', $request->id)->get();
        return Datatables::of($rent)
            ->editColumn('id', function($rent){
                return $rent->id;
            })
            ->editColumn('equipment_ids', function($rent){
                $list = '<ul class="list">';
                $equipment_ids = json_decode($rent->equipment_ids);
                if($equipment_ids == null){
                    $reservation_ids = json_decode($rent->reservation_ids);
                    foreach($reservation_ids as $id){
                        $reservation = Reservation::find($id);
                        $product = Product::find($reservation->product_id);
                        $list = $list . '<li>' . $product->name . ' (' . $product->product_id . ')</li>';
                    }
                }else{
                    foreach($equipment_ids as $id){
                        $product = Product::find($id);
                        $list = $list . '<li>' . $product->name . ' (' . $product->product_id . ')</li>';
                    }
                }
                $list = $list . '</ul>';
                return $list;
            })
            ->editColumn('rental_from', function($rent){
                return date('d.m.Y', strtotime($rent->rental_from));
            })
            ->editColumn('rental_to', function($rent){
                return date('d.m.Y', strtotime($rent->rental_to));
            })
            ->editColumn('total_price', function($rent){
                return $rent->total_price . ' EUR';
            })
            ->editColumn('status', function($rent){
                if($rent->status == 'in_progress'){
                    return 'V izvedbi';
                }elseif($rent->status == 'successfully_paid'){
                    return 'Uspešno plačilo';
                }elseif($rent->status == 'completed'){
                    return 'Končano';
                }elseif($rent->status == 'canceled'){
                    return 'Preklicano';
                }else{
                    return 'V čakanju';
                }
            })
            ->editColumn('contract_filepath', function($rent){
                if($rent->contract_filepath == null){
                    return 'Dokument še ni na voljo';
                }else{
                    return "<a href='$rent->contract_filepath' target='_blank'><i class='fa fa-file-pdf-o' aria-hidden='true'></i></a>";
                }
            })
            ->editColumn('return_confirmation_filepath', function($rent){
                if($rent->return_confirmation_filepath == null){
                    return 'Dokument še ni na voljo';
                }else{
                    return "<a href='$rent->return_confirmation_filepath' target='_blank'><i class='fa fa-file-pdf-o' aria-hidden='true'></i></a>";
                }
            })
            ->editColumn('created_at', function($rent){
                return date('d.m.Y h:i', strtotime($rent->created_at));
            })
            ->addColumn('edit', function ($rent) {
                return '<a href="/rent/edit/' .$rent->id. '">Pregled izposoje</a>';
            })
            ->rawColumns(['equipment_ids', 'contract_filepath', 'return_confirmation_filepath', 'edit'])
            ->make(true);

    }
}
