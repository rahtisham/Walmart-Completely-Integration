<?php

namespace App\Http\Controllers\Walmart\Alerts;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Integration\walmart;
use App\Mail\regionalPerformance;
use App\Models\Walmart\Order_details;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class RegionalPerformanceController extends Controller
{
    public function index()
    {
        return view('walmart.alerts.regional_performance');
    }

    public function regionalPerformance()
    {
        $last_delivery_date = Order_details::select('order_date')
                                            ->where('order_date' , '!=' , null)
                                            ->latest('order_date')
                                            ->first();

        $to = $last_delivery_date['order_date'];
        $addDay= strtotime($last_delivery_date['order_date'] . "-10 days");
        $ten_days_ago_delivery_Date = date('Y-m-d', $addDay);

        $reportShipment = Order_details::whereBetween('order_date', [$ten_days_ago_delivery_Date , $to])->get();
        $total_number_of_row =  $reportShipment->count();

        $regionalCity = [];
        $regionalState = [];

        $cities = Order_details::select('city')->distinct('city')->whereBetween('order_date', [$ten_days_ago_delivery_Date , $to])->pluck('city');
        foreach($cities as $city)
        {
            $number_of_orders =  Order_details::where('city' , $city)->get()->count();

            $obtained_mark = $number_of_orders / $total_number_of_row;
            $percentage = $obtained_mark * 100;

            $regionalCity[$city] = [

                'order' => $number_of_orders,
                'email' => 'ahtisham@amzonestep.com',
                'city' => $city,
                'percentage' => $percentage

            ];

        }

        $states = Order_details::select('state')->distinct('state')->whereBetween('order_date', [$ten_days_ago_delivery_Date , $to])->pluck('state');
        foreach($states as $state)
        {
            $number_of_orders =  Order_details::where('state' , $state)->get()->count();

            $obtained_mark = $number_of_orders / $total_number_of_row;
            $percentage = $obtained_mark * 100;

            $regionalState[$state] = [

                'order' => $number_of_orders,
                'email' => 'ahtisham@amzonestep.com',
                'city' => $state,
                'percentage' => $percentage

            ];

        }

        Mail::to('ahtisham@amzonestep.com')->send(new regionalPerformance($regionalCity));

    }
}
