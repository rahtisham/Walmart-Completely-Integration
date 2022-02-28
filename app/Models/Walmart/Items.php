<?php

namespace App\Models\Walmart;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Items extends Model
{
    protected $table = 'walmart_item_alerts';

    protected $guarded = [];

    public static function insert_item_alert($walmartAlerts){
        $walmartAlerts = Items::create($walmartAlerts);
    }
}
