<?php

namespace App\Models\Walmart;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\WalmartMarketPlace;

class DeliveryManager extends Model
{
    protected $table = "delivery_manager";
    protected $guarded = [];


    public function marketPlace()
    {
        return $this->belongsTo(WalmartMarketPlace::class , 'm_id' , 'id');
    }

    public static function updateStatus($DeliveryManagerId , $status)
    {
        $manager = DeliveryManager::where('id' , $DeliveryManagerId)->update(['status' => $status]);
        return $manager;
    }

}
