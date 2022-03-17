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

    public static function create_regional_performance_manager($marketPlaceID)
    {
        ShippingManager::create([

            'm_id' => $marketPlaceID,
            'status' => 'Pending',
            'module' => 'Regional_Performance',

        ]);
    }

    public static function create_on_time_delivery_manager($marketPlaceID)
    {
        ShippingManager::create([

            'm_id' => $marketPlaceID,
            'status' => 'Pending',
            'module' => 'On_Time_Delivery',

        ]);
    }

    public static function create_on_time_shipping_manager($marketPlaceID)
    {
        ShippingManager::create([

            'm_id' => $marketPlaceID,
            'status' => 'Pending',
            'module' => 'On_Time_Shipping',

        ]);
    }

    public static function create_carrier_performance_manager($marketPlaceID)
    {
        ShippingManager::create([

            'm_id' => $marketPlaceID,
            'status' => 'Pending',
            'module' => 'Carrier_Performance',

        ]);
    }

    public static function create_rating_review_manager($marketPlaceID)
    {
        ShippingManager::create([

            'm_id' => $marketPlaceID,
            'status' => 'Pending',
            'module' => 'Rating_Review',

        ]);
    }


}
