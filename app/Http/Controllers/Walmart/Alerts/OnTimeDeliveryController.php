<?php

namespace App\Http\Controllers\Walmart\Alerts;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Integration\walmart;
use App\Mail\OnTimeDeliveryAlert;
use App\Models\Walmart\Order_details;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class OnTimeDeliveryController extends Controller
{
    public function index()
    {
        return view('walmart.alerts.on_time_delivery');
    }
    public function OnTimeDelivered()
    {
        $last_delivery_date = order_details::select('actualDeliveryDate')
                                            ->where('actualDeliveryDate' , '!=' , null)
                                            ->latest('actualDeliveryDate')
                                            ->first();

        $to = $last_delivery_date['actualDeliveryDate'];
        $addDay= strtotime($last_delivery_date['actualDeliveryDate'] . "-10 days");
        $ten_days_ago_delivery_Date = date('Y-m-d', $addDay);


        $reportDelivery = order_details::whereBetween('actualDeliveryDate', [$ten_days_ago_delivery_Date , $to])->get();


        if(count($reportDelivery) > 0)
        {
            $report_generate = [];
            foreach($reportDelivery as $report)
            {
                $actualDeliveryDate = strtotime($report['actualDeliveryDate']);
                $estimatedDeliveryDate = strtotime($report['estimatedDeliveryDate']);

                if($actualDeliveryDate <= $estimatedDeliveryDate)
                {
                    $actualDeliveryStatus = "Excellent";
                }
                elseif($actualDeliveryDate == $estimatedDeliveryDate)
                {
                    $actualDeliveryStatus = "Good";
                }
                elseif($actualDeliveryDate > $estimatedDeliveryDate)
                {
                    $actualDeliveryStatus = "Poor";
                }

                $report_generate[] = [

                    'order_id' => $report['user_id'],
                    'actualDeliveryDate' => $report['actualDeliveryDate'],
                    'estimatedDeliveryDate' => $report['estimatedDeliveryDate'],
                    'email' => "ahtisham@amzonestep.com",
                    'status' => $actualDeliveryStatus,
                ];

            }

            Mail::to('ahtisham@amzonestep.com')->send(new OnTimeDeliveryAlert($report_generate));

        }
    }

}
