<?php

namespace App\Models\Walmart;

use App\Models\WalmartMarketPlace;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShippingManager extends Model
{
    protected $table = "shipping_manager";
    protected $guarded = [];


    public function marketPlace()
    {
        return $this->belongsTo(WalmartMarketPlace::class , 'm_id' , 'id');
    }

    public static function updateStatus($DeliveryManagerId , $status)
    {
        $manager = ShippingManager::where('id' , $DeliveryManagerId)->update(['status' => $status]);
        return $manager;
    }
}
