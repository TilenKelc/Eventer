<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

use App\Mail\NotificationMail;
use App\Rent;
use App\User;

class cronUserNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mail:sendnotifications';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This will email all users, whose rental equipment is ready for takeover.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // vsi renti, ki so ob klicu te funckije že pripravljeni na prevzem
        $rents = Rent::where('ready_for_take_over', true)->get();
        foreach($rents as $rent){
            $customer = User::find($rent->customer_id);



            // če je rental_from nastavljen na jutrišnji datum
            $datum_renta_string = date("d-m-Y", strtotime($rent->rental_from));
            $datum_jutri = new \DateTime('tomorrow');
            $datum_jutri_string = $datum_jutri->format('d-m-Y');

            // echo $datum_renta_string;
            // echo " - ";
            // echo $datum_jutri_string;
            // echo "\n";

            if($datum_renta_string == $datum_jutri_string){
               Mail::to($customer->email)->send(new NotificationMail($customer, $rent));
            //   Mail::to('jan.rijavec@tauria.si')->send(new NotificationMail($customer, $rent));
            }

        }
    }
}
