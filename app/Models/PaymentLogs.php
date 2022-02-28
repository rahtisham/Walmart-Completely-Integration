<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentLogs extends Model
{
    protected $table = 'payment_logs';

    protected $guarded = [];

    public static function createPaymentLog($paymentlog)
    {
        $payment = PaymentLogs::create($paymentlog);
        return $payment;
    }
}
