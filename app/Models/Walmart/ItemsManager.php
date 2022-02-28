<?php

namespace App\Models\Walmart;

use App\Models\WalmartMarketPlace;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemsManager extends Model
{
    protected $table = "walmartitemsmanager";
    protected $guarded = [];

    public static function create_manager($marketPlaceID) {

        ItemsManager::create([

            'm_id' => $marketPlaceID,
            'status'      => "Pending",
            'module'  => "Items",

        ]);
    }

    public function marketPlace()
    {
        return $this->belongsTo(WalmartMarketPlace::class , 'm_id' , 'id');
    }

}
