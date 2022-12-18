<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

use App\User;
use App\Product;
use App\Rent;
use App\Reservation;
use App\Address;
use Yajra\Datatables\Datatables;
use Log;

class AgentController extends Controller
{
    public function index(){
        return view('agent.index');
    }

    public function getAgents(){
        $agent = User::where('user_role', '=', 'agent')->orderBy('created_at', 'asc')->get();
        return Datatables::of($agent)
            ->editColumn('created_at', function($agent){
                return date_format(date_create($agent->created_at), "d.m.Y h:i");
            })
            ->editColumn('updated_at', function($agent){
                return date_format(date_create($agent->updated_at), "d.m.Y h:i");
            })
            ->editColumn('last_logged_in', function($agent){
                return date_format(date_create($agent->last_logged_in), "d.m.Y h:i");
            })
            ->addColumn('edit', function ($agent) {
                return '<a href="/agent/edit/' .$agent->id. '">Posodobi</a>';
            })
            ->rawColumns(['edit', 'delete'])
            ->make(true);
    }

    public function addNewAgent(){
        return view('agent.add', [
            'agent' => null
        ]);
    }

    public function saveNewAgent(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'surname' => 'required',
            'email' => 'required',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect('agent/add')
                ->withInput()
                ->withErrors($validator);
        }

        $agent = new User();
        $agent->name = $request->name;
        $agent->surname = $request->surname;
        $agent->email = $request->email;
        $agent->user_role = 'agent';

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

        if($request->password){
            $agent->password = Hash::make($request->password);
        }
        $agent->address_id = $address_id;
        $agent->password = Hash::make($request->password);
        $agent->status = true;

        // agenti ne rabijo potrjevat maila
        $agent->email_verified_at = date('Y-m-d H:i:s');

        $agent->save();

        return view('agent.index')->with("successMssg", 'Agent ' . $agent->name . ' ' . $agent->surname . ' uspešno dodan');
    }

    public function editAgent(Request $request){
        $id = $request->id;
        $agent = User::find($id);
        return view('agent.add', [
            'agent' => $agent
        ]);
    }

    public function saveUpdatedAgent(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'surname' => 'required',
            'email' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect('agent/add')
                ->withInput()
                ->withErrors($validator);
        }

        $agent = User::find($request->id);
        $agent->name = $request->name;
        $agent->surname = $request->surname;
        $agent->email = $request->email;

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

        if($request->password){
            $agent->password = Hash::make($request->password);
        }
        $agent->address_id = $address_id;
        $agent->save();

        return view('agent.index')->with("successMssg", 'Agent ' . $agent->name . ' ' . $agent->surname . ' uspešno posodobljen');
    }

    public function showRentals(Request $request){
        return view("agent.show", [
            "status" => $request->status
        ]);
    }

    public function getRentals(Request $request){
        $rent = null;
        if($request->status == 'all'){
            $rent = Rent::orderBy('created_at', 'asc')->get();

        } elseif($request->status == 'today_out'){
          // $rent = Rent::whereRaw('Date(rental_from) = CURDATE()')->where('status','successfully_paid')->get();
            $rent = Rent::whereRaw('Date(rental_from) = CURDATE()')->orWhereRaw('Date(rental_from) = CURDATE() + 1')->where('status','successfully_paid')->get();

        } elseif($request->status == 'week_out'){
          $from = date("Y-m-d");
          $to = date("Y-m-d", strtotime("+1 week"));;
          $rent = Rent::whereBetween('rental_from', [$from, $to])->where('status','successfully_paid')->get();

        } elseif($request->status == 'today_in'){
          $rent = Rent::whereRaw('Date(rental_to) = CURDATE() - 1')->orWhereRaw('Date(rental_to) = CURDATE()')->orWhereRaw('Date(rental_to) = CURDATE() + 1')->where('status','in_progress')->get();

        }else{
            $rent = Rent::where('status', $request->status)->orderBy('created_at', 'asc')->get();
        }
        return Datatables::of($rent)
                ->editColumn('name', function($rent){
                    $customer = User::find($rent->customer_id);
                    return $customer->name . ' ' . $customer->surname;
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
                ->editColumn('email', function($rent){
                    $customer = User::find($rent->customer_id);
                    return $customer->email;
                })
                ->editColumn('date_from', function($rent){
                    return date('d.m.Y', strtotime($rent->rental_from));
                })
                ->editColumn('products', function($rent){
                    $list = '<ul class="list">';
                    $equipment_ids = json_decode($rent->equipment_ids);
                    if($equipment_ids == null){

                      if($reservation_ids = json_decode($rent->reservation_ids)){
                        foreach($reservation_ids as $id){
                            $reservation = Reservation::find($id);
                            $product = Product::find($reservation->product_id);
                            $list = $list . '<li>' . $product->name .'</li>';
                        }
                      } else {
                        $list = $list . '/';
                      }



                    }else{
                        foreach($equipment_ids as $id){
                            $product = Product::find($id);
                            $list = $list . '<li>' . $product->name . '</li>';
                        }
                    }
                    $list = $list . '</ul>';
                    return $list;
                })
                ->editColumn('date_to', function($rent){
                    return date('d.m.Y', strtotime($rent->rental_to));
                })
                ->editColumn('total_price', function($rent){
                    if($rent->total_price == null){
                        return '/';
                    }
                    return $rent->total_price . ' EUR';
                })
                ->editColumn('ready_for_take_over', function($rent){
                    if($rent->ready_for_take_over == true){
                        return 'Da';
                    }else{
                        return 'Ne';
                    }
                })
                ->editColumn('created_at', function($rent){
                    return date('d.m.Y h:i', strtotime($rent->created_at));
                })
                ->editColumn('canceled_timestamp', function($rent){
                    return date('d.m.Y h:i', strtotime($rent->canceled_timestamp));
                })
                ->addColumn('edit', function ($rent) {
                    if(Auth::user()->isAdmin()){
                        return '<a href="/rent/edit/' .$rent->id. '">Posodobi</a>';
                    }
                    return '<a href="javascript:void(0)" class="demo">Posodobi</a>';
                })
                ->rawColumns(['edit', 'delete', 'products'])
                ->make(true);
    }

    public function delete(Request $request){
        $agent = Agent::find($request->id);
        $agent->deleted_at = date('Y-m-d H:i:s');
        $agent->deleted = true;
        $agent->save();

        return view('agent.index')->with("successMssg", 'Agent ' . $agent->name . ' ' . $agent->surname . ' uspešno izbrisan');
    }
}
