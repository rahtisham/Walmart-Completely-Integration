<?php

namespace App\Http\Controllers\Walmart\Alerts;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ShippingPerformanceController extends Controller
{
    public function index()
    {
        return view('walmart.alerts.shipping_performance');
    }
}
