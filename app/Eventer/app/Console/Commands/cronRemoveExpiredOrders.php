<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Rent;
use App\Reservation;
use DateTime;

class cronRemoveExpiredOrders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rent:remove:expired';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Removes expired pending orders';

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
        $rentals = Rent::where('status', 'pending')->get();

        foreach($rentals as $rent) {
            $start_date = new DateTime(date('Y-m-d h:i:s', strtotime($rent->updated_at)));
            $since_start = $start_date->diff(new DateTime());
            $minutes = $since_start->days * 24 * 60;
            $minutes += $since_start->h * 60;
            $minutes += $since_start->i;

            if($minutes > 30){
                $reservation_ids = json_decode($rent->reservation_ids);
                foreach ($reservation_ids as $reservation_id) {
                    $reservation = Reservation::find($reservation_id);
                    $reservation->delete();
                }

                $rent->status = 'canceled';
                $rent->reservation_ids = null;
                $rent->canceled_by = 1;
                $rent->canceled_timestamp = date('Y-m-d H:i:s');
                $rent->agent_id = 1;
                $rent->save();
            }
        }
    }
}
