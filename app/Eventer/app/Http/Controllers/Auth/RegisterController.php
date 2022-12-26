<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Str;

use App\Address;
use Log;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'surname' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'phone_number' => ['required', 'string', 'min:8'],
            'street' => ['required', 'string'],
            'city' => ['required', 'string'],
            'postal_code' => ['required', 'string', 'regex:/([1-9][0-9]{3})/'],
            // 'country_code' => ['required', 'string'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        $address_check = Address::where('street', $data['street'])->where('city', $data['city'])
            ->where('postal_code', $data['postal_code'])->where('country_code', 'SL')->first();

        $address_id = null;
        if($address_check === null){
            $address = new Address();
            $address->street = $data['street'];
            $address->city = $data['city'];
            $address->postal_code = $data['postal_code'];
            $address->country_code = 'SL';
            $address->save();

            $address_id = $address->id;
        }else{
            $address_id = $address_check->id;
        }

        $user = new User();
        $user->name = $data['name'];
        $user->surname = $data['surname'];
        $user->email = $data['email'];
        $user->phone_number = $data['phone_number'];
        $user->password = Hash::make($data['password']);
        $user->address_id = $address_id;
        $user->api_token = Str::random(60);
        $user->save();

        return $user;
        /*
        return User::create([
            'name' => $data['name'],
            'surname' => $data['surname'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'address_id' => $address_id
        ]);
        */
    }
}
