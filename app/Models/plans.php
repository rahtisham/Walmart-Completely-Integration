<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class plans extends Model
{
    protected $table = "plans";
    protected $guarded = [];

    public static function store($store)
    {
        $plans = plans::create($store);
        return $plans;
    }

    public static function updatePlan($updated , $id)
    {
         $plans = plans::where('id' , $id)->update($updated);
         return $plans;
    }
}
