<?php

namespace App\Http\Controllers\Walmart\Alerts;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Mail\shippingPerformanceItems;
use App\Models\User;
use App\Models\Walmart\Order_details;
use App\Models\Walmart\Items;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Config;

class ShippingPerformanceController extends Controller
{
    public function index()
    {
        return view('walmart.alerts.shipping_performance');
    }

    public function shippingPerformance()
    {

        $userCount = User::count();

        if($userCount > 0)
        {

            for($i = 0; $i < $userCount; $i++)
            {

                $walmartItem = User::all();

                $email = $walmartItem[$i]['email'];

                $walmartitemCount = Items::where('user_id' , $walmartItem[$i]['id'])->get();

                if($walmartitemCount->count() > 0){

                    $walmart_IpClaim_Count = Items::select('alert_type')
                                                        ->where('user_id' , $walmartItem[$i]['id'])
                                                        ->where('alert_type' , 'ip_claim')
                                                        ->count();

                    $IP_Claim_Percentage = $walmart_IpClaim_Count / $walmartitemCount->count() * 100;

                    $walmart_Offensive_product_Count = Items::select('alert_type')
                                                            ->where('user_id' , $walmartItem[$i]['id'])
                                                            ->where('alert_type' , 'offensive_product')
                                                            ->count();

                    $offensive_product_percentage = $walmart_Offensive_product_Count / $walmartitemCount->count() * 100;

                    $walmart_regulatory_compliance_count = Items::select('alert_type')
                                                                ->where('user_id' , $walmartItem[$i]['id'])
                                                                ->where('alert_type' , 'regulatory_compliance')
                                                                ->count();

                    $regulatory_compliance_percentage = $walmart_regulatory_compliance_count / $walmartitemCount->count() * 100;

                    $walmart_brand_partnership_violation_count = Items::select('alert_type')
                                                                        ->where('user_id' , $walmartItem[$i]['id'])
                                                                        ->where('alert_type' , 'brand_partnership_violation')
                                                                        ->count();

                    $brand_partnership_violation_percentage = $walmart_brand_partnership_violation_count / $walmartitemCount->count() * 100;

                    $shippingPerformance = [

                        'ip_claim' => $IP_Claim_Percentage,
                        'offensive_product' => $offensive_product_percentage,
                        'regulatory_compliance' => $regulatory_compliance_percentage,
                        'brand_partnership_violation' => $brand_partnership_violation_percentage,

                    ];

                    Mail::to($email)->send(new shippingPerformanceItems($shippingPerformance));

                }
                else{
                     echo "Data is not available";
                }
            }
            // print_r($shippingPerformance);
        }

    }

    public function shippingPerformanceOrder()
    {
        $userCount = User::count();

        if($userCount > 0)
        {

            for($i = 0; $i < $userCount; $i++){

                $user = User::all();

                $email = $user[$i]['email'];

                $walmartitemCount = Order_details::where('user_id' , $user[$i]['id'])->get();

                if($walmartitemCount->count() > 0){

                    $last_order_date = Order_details::orderBy('order_date' , 'DESC')->first();

                    $to = $last_order_date['order_date'];
                    $addDay= strtotime($last_order_date['order_date'] . "-5 days");
                    $ten_days_ago_order_Date = date('Y-m-d', $addDay);

                    $reportShipment = Order_details::where('user_id' , $user[$i]['id'])->whereBetween('order_date', [$ten_days_ago_order_Date , $to])->get();
                    $total_number_of_order_in_five_days =  $reportShipment->count();



                    $regionalCity = [];
                    $shippingStatus = [];
                    $DeliveryStatus = [];

                    $on_time_shipping_performance = Order_details::select('actualShipStatus')->distinct('actualShipStatus')->where('actualShipStatus' , '!=' , null)->where('user_id' , $user[$i]['id'])->whereBetween('order_date', [$ten_days_ago_order_Date , $to])->pluck('actualShipStatus');

                    foreach($on_time_shipping_performance as $ontimeshiping)
                    {

                        $ontimeShipping = Order_details::where('actualShipStatus' , $ontimeshiping)->where('user_id' , $user[$i]['id'])->get(); echo "<br>";
                        $ontimeShippingstatus = Order_details::where('actualShipStatus' , $ontimeshiping)->where('user_id' , $user[$i]['id'])->first(); echo "<br>";
                        $number_of_on_time_shiping_count = $ontimeShipping->count();

                        $reason = '';
                        if($ontimeShippingstatus->actualShipStatus == "Good"){

                            $reason = "Product is shipped on the time of given date";
                        }
                       if($ontimeShippingstatus->actualShipStatus == "Excellent"){

                            $reason = "Product is shipped before the given date";
                       }
                       if($ontimeShippingstatus->actualShipStatus == "Poor"){

                            $reason = "Product is shipped after date";
                       }

                        $obtained_mark = $number_of_on_time_shiping_count / $total_number_of_order_in_five_days;
                        $percentage = $obtained_mark * 100; echo "<br>";

                        $shippingStatus[] = [

                            'Total Orders' => $total_number_of_order_in_five_days,
                            'Orders Shipment' => $number_of_on_time_shiping_count,
                            'email' => $email,
                            'percentage' => $percentage,
                            'Status' => $ontimeShippingstatus->actualShipStatus,
                            'reason' => $reason

                        ];

                    }

                    // return $shippingStatus;

                    $on_time_Delivery_performance = Order_details::select('actualDeliveryStatus')->distinct('actualDeliveryStatus')->where('actualDeliveryStatus' , '!=' , null)->where('user_id' , $user[$i]['id'])->whereBetween('order_date', [$ten_days_ago_order_Date , $to])->pluck('actualDeliveryStatus');

                    foreach($on_time_Delivery_performance as $ontimeDelivery)
                    {

                        $ontimeDeliveries = Order_details::where('actualDeliveryStatus' , $ontimeDelivery)->where('user_id' , $user[$i]['id'])->get(); echo "<br>";
                        $ontimeDeliverystatus = Order_details::where('actualDeliveryStatus' , $ontimeDelivery)->where('user_id' , $user[$i]['id'])->first(); echo "<br>";
                        $number_of_on_time_Delivery_count = $ontimeDeliveries->count();

                        $reason = '';
                        if($ontimeDeliverystatus->actualDeliveryStatus == "Good"){

                            $reason = "Product is shipped on the time of given date";
                        }
                       if($ontimeDeliverystatus->actualDeliveryStatus == "Excellent"){

                            $reason = "Product is shipped before the given date";
                       }
                       if($ontimeDeliverystatus->actualDeliveryStatus == "Poor"){

                            $reason = "Product is shipped after date";
                       }

                        $obtained_mark = $number_of_on_time_Delivery_count / $total_number_of_order_in_five_days;
                        $percentage = $obtained_mark * 100; echo "<br>";

                        $DeliveryStatus[] = [

                            'Total Orders' => $total_number_of_order_in_five_days,
                            'Orders Delivery' => $number_of_on_time_Delivery_count,
                            'email' => $email,
                            'percentage' => $percentage,
                            'Status' => $ontimeDeliverystatus->actualDeliveryStatus,
                            'reason' => $reason

                        ];

                    }

                    return $DeliveryStatus;


                    $cities = Order_details::select('city')->distinct('city')->where('user_id' , $user[$i]['id'])->whereBetween('order_date', [$ten_days_ago_order_Date , $to])->pluck('city');
                    foreach($cities as $city)
                    {
                        $number_of_city_count = Order_details::where('city' , $city)->where('user_id' , $user[$i]['id'])->count(); echo "<br>";

                        $obtained_mark = $number_of_city_count / $total_number_of_order_in_five_days;
                        $percentage = $obtained_mark * 100;

                         $regionalCity[] = [

                            'Total Order of Five Days' => $total_number_of_order_in_five_days,
                            'order' => $number_of_city_count,
                            'email' => $email,
                            'city' => $city,
                            'percentage' => $percentage

                        ];
                    }

                    return $regionalCity;


                }
                else{
                     echo "Data is not available";
                }
            }
            // print_r($shippingPerformance);
        }

    }
}
