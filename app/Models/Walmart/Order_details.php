<?php

namespace App\Models\Walmart;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order_details extends Model
{
    protected $table = 'walmart_order_details';
    protected $guarded = [];

    public static function create_orders($walmart_order_details){
        Order_details::create($walmart_order_details);
    }
}
