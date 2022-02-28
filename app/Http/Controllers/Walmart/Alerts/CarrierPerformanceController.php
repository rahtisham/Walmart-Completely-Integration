<?php

namespace App\Http\Controllers\Walmart\Alerts;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Integration\walmart;
use App\Mail\carrierPerformance;
use App\Models\Walmart\Order_details;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class CarrierPerformanceController extends Controller
{
    public function index()
    {
        return view('walmart.alerts.carrier_performance');
    }

    public function carrierPerformance()
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

        $carrierPer = [];

        $carrierPerformance = Order_details::select('carrierName')->distinct('carrierName')->whereBetween('order_date', [$ten_days_ago_delivery_Date , $to])->pluck('carrierName');
        foreach($carrierPerformance as $carrier)
        {
            $number_of_carrier =  Order_details::where('carrierName' , $carrier)->get()->count();

            $obtained_mark = $number_of_carrier / $total_number_of_row;
            $percentage = $obtained_mark * 100;

            $carrierPer[$carrier] = [

                'numberCarrier' => $number_of_carrier,
                'email' => 'ahtisham@amzonestep.com',
                'carrierName' => $carrier,
                'percentage' => $percentage

            ];

        }

        Mail::to('ahtisham@amzonestep.com')->send(new carrierPerformance($carrierPer));
        echo "Sended Email";
    }
}
