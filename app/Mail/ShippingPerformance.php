<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ShippingPerformance extends Mailable
{
    use Queueable, SerializesModels;
    public $shippingStatus , $DeliveryStatus , $regionalCity , $carrierStatus;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($shippingStatus , $DeliveryStatus , $regionalCity , $carrierStatus)
    {
        $this->shippingStatus = $shippingStatus;
        $this->DeliveryStatus = $DeliveryStatus;
        $this->regionalCity = $regionalCity;
        $this->carrierStatus = $carrierStatus;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        // return $this->view('view.name');
        return $this->subject('Shipping Performance From AppealLab')->view('email_template.shipping_performance');
    }
}
