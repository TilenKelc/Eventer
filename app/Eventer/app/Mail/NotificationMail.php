<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class NotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($customer, $rent)
    {
        $this->customer = $customer;
        $this->rent = $rent;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('')
            ->subject('Obvestilo o prevzemu opreme')
            ->view('mail.notification')
            ->with([
                "customer" => $this->customer,
                "rent" => $this->rent,
        ]);
    }
}
