<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

//use App\Cart;
use App\Rent;
use DateTime;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $braintree = new \Braintree\Gateway([
            'environment' => env('BTREE_ENVIRONMENT'),
            'merchantId' => env('BTREE_MERCHANT_ID'),
            'publicKey' => env('BTREE_PUBLIC_KEY'),
            'privateKey' => env('BTREE_PRIVATE_KEY')
        ]);
        config(['braintree' => $braintree]); 

        view()->composer('*', function($view)
        {
            /*if (Auth::check() && Auth::user()->user_role == "customer"){
                
            }
            */

            $count = 0;
            $rent_id = session('rent_id');
            if($rent_id != null){
                $rent = Rent::find($rent_id);
                $count = count(json_decode($rent->reservation_ids));
            }

            //variables passed to all views
            $data = [
                'cart_num' => $count,
                /*'st_odgovorov_spec'    => $pripravljeni_odgovori_specialistov,
                'st_delovniih_porocil' => $delovna_porocila_v_cakalnici,
                'st_danes_narocenih'   => $stevilo_danes_narocenih,
                'vpogled_v_teku'       => $vpogled_v_teku,*/
            ];

            $view->with($data);
      });
    }
}
