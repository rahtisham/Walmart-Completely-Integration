<?php

namespace App\Http\Controllers\Walmart\Alerts;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Integration\walmart;
use App\Mail\OnTimeShipping;
use App\Models\Walmart\Order_details;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class OnTimeShipmentController extends Controller
{
    public function index()
    {
        return view('walmart.alerts.on_time_shipment');
    }

    public function OnTimeShipment()
    {

        $last_shipment_date = Order_details::select('actualShipDate')
                                            ->where('actualShipDate' , '!=' , null)
                                            ->latest('actualShipDate')
                                            ->first();

        $to = $last_shipment_date['actualShipDate'];
        $addDay= strtotime($last_shipment_date['actualShipDate'] . "-10 days");
        $ten_days_ago_shipment_Date = date('Y-m-d', $addDay);


        $reportShipment = Order_details::whereBetween('actualShipDate', [$ten_days_ago_shipment_Date , $to])->get();


        if(count($reportShipment) > 0)
        {
            $report_generate = [];
            foreach($reportShipment as $report)
            {
                $actaulshipDate = strtotime($report['actualShipDate']);
                $estimatedShipDate = strtotime($report['estimatedShipDate']);

                if($actaulshipDate < $estimatedShipDate)
                {
                    $actualShippingStatus = "Excellent";
                }
                elseif($actaulshipDate == $estimatedShipDate)
                {
                    $actualShippingStatus = "Good";
                }
                elseif($actaulshipDate > $estimatedShipDate)
                {
                    $actualShippingStatus = "Poor";
                }

                $report_generate[] = [

                    'order_id' => $report['user_id'],
                    'actualShipDate' => $report['actualShipDate'],
                    'estimatedShipDate' => $report['estimatedShipDate'],
                    'email' => "ahtisham@amzonestep.com",
                    'status' => $actualShippingStatus,
                ];


            }

            Mail::to('ahtisham@amzonestep.com')->send(new OnTimeShipping($report_generate));

        }

    } // End of onTimeShipping function


}
