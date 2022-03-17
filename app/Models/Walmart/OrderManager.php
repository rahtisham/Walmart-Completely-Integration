<?php

namespace App\Models\Walmart;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\WalmartMarketPlace;

class OrderManager extends Model
{
    protected $table = "walmart_orders_manager";
    protected $guarded = [];


    public function marketPlace()
    {
        return $this->belongsTo(WalmartMarketPlace::class , 'm_id' , 'id');
    }


    public static function updateStatus($orderManagerId , $status)
    {
        $manager = OrderManager::where('id' , $orderManagerId)->update(['status' => $status]);
        return $manager;
    }

    public static function updateStatusAnOrder($anOrderManagerId , $status)
    {
        $manager = OrderManager::where('id' , $anOrderManagerId)->update(['status' => $status]);
        return $manager;
    }

    public static function create_an_order_manager($marketPlaceID)
    {
        OrderManager::create([

            'm_id' => $marketPlaceID,
            'status' => 'Pending',
            'module' => 'An_Order',

        ]);
    }

    public static function create_all_order_manager($marketPlaceID)
    {
        OrderManager::create([

            'm_id' => $marketPlaceID,
            'status' => 'Pending',
            'module' => 'All_Order',

        ]);
    }


}
