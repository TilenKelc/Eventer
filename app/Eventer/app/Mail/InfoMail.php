<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class InfoMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($customer, $rent, $products)
    {
        $this->rent = $rent;
        $this->customer = $customer;
        $this->products = $products;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('')
            ->subject('Nova rezervacija')
            ->view('mail.info')
            ->with([
                "customer" => $this->customer,
                "rent" => $this->rent,
                "products" => $this->products
        ]);
    }
}
