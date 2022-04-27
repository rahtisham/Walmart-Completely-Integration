<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class shippingPerformanceItems extends Mailable
{
    use Queueable, SerializesModels;
    public $shippingPerformance;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($shippingPerformance)
    {
        $this->shippingPerformance = $shippingPerformance;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        // return $this->view('view.name');
        return $this->subject('Shipping Performance Items From AppealLab')->view('email_template.shipping_performance_items');
    }
}
