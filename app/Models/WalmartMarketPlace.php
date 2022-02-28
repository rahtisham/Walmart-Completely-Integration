<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WalmartMarketPlace extends Model
{
    use HasFactory;

    protected $table = "integrations";
    protected $guarded = [];

    public static function createWalmartStore($integration)
    {
        $market = WalmartMarketPlace::create($integration);
        return $market;
    }

    public static function updateWalmartStore($integration, $mid)
    {
        $market = WalmartMarketPlace::where('id' , $mid)->update($integration);
        return $market;
    }


}
