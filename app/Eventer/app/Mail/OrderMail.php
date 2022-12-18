<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class OrderMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($customer, $rent, $products)
    {
        $this->customer = $customer;
        $this->rent = $rent;
        $this->products = $products;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('', 'Potrdilo rezervacije')
            ->subject('Rezervacija športne opreme')
            ->view('mail.order')
            ->with([
                "customer" => $this->customer,
                "rent" => $this->rent,
                "products" => $this->products
        ]);
    }
}
