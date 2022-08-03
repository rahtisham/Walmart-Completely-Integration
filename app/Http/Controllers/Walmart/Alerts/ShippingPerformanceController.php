<?php

namespace App\Http\Controllers\Walmart\Alerts;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Mail\shippingPerformanceItems;
use App\Mail\ShippingPerformance;
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
                    $addDay= strtotime($last_order_date['order_date'] . "-10 days");
                    $ten_days_ago_order_Date = date('Y-m-d', $addDay);

                    $reportShipment = Order_details::where('user_id' , $user[$i]['id'])->whereBetween('order_date', [$ten_days_ago_order_Date , $to])->get();
                    $total_number_of_order_in_five_days =  $reportShipment->count();

                    $regionalCity = [];
                    $shippingStatus = [];
                    $DeliveryStatus = [];
                    $carrierStatus = [];


                    $on_time_shipping_performance = Order_details::select('actualShipStatus')
                                                                        ->distinct('actualShipStatus')
                                                                        ->where('actualShipStatus' , '!=' , null)
                                                                        ->where('user_id' , $user[$i]['id'])
                                                                        ->whereBetween('order_date', [$ten_days_ago_order_Date , $to])
                                                                        ->pluck('actualShipStatus');
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

                            'TotalOrders' => $total_number_of_order_in_five_days,
                            'OrdersShipment' => $number_of_on_time_shiping_count,
                            'email' => $email,
                            'percentage' => $percentage,
                            'Status' => $ontimeShippingstatus->actualShipStatus,
                            'reason' => $reason

                        ];

                    } // End of shipping performance

                    echo "Shipping Performance";


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

                            'TotalOrders' => $total_number_of_order_in_five_days,
                            'OrdersDelivery' => $number_of_on_time_Delivery_count,
                            'email' => $email,
                            'percentage' => $percentage,
                            'Status' => $ontimeDeliverystatus->actualDeliveryStatus,
                            'reason' => $reason

                        ];

                    } // End of delivery performance

                    echo "Delivery Performance the cobra is found in india and pakistan";


                    $cities = Order_details::select('city')->distinct('city')->where('user_id' , $user[$i]['id'])->whereBetween('order_date', [$ten_days_ago_order_Date , $to])->pluck('city');
                    foreach($cities as $city)
                    {
                        $number_of_city_count = Order_details::where('city' , $city)->where('user_id' , $user[$i]['id'])->count(); echo "<br>";

                        $obtained_mark = $number_of_city_count / $total_number_of_order_in_five_days;
                        $percentage = $obtained_mark * 100;

                         $regionalCity[] = [

                            'TotalOrder' => $total_number_of_order_in_five_days,
                            'order' => $number_of_city_count,
                            'email' => $email,
                            'city' => $city,
                            'percentage' => $percentage

                        ];
                    } // End of regional performance

                    echo "Regional Performance";


                    $carrierPerformance = Order_details::select('carrierName')->distinct('carrierName')->where('actualShipStatus' , '!=' , null)->where('status' , 'Shipped101')->where('user_id' , $user[$i]['id'])->whereBetween('order_date', [$ten_days_ago_order_Date , $to])->pluck('carrierName');
                    foreach($carrierPerformance as $carrier)
                    {
                        $number_of_carrier_count = Order_details::where('carrierName' , $carrier)->where('user_id' , $user[$i]['id'])->count(); echo "<br>";

                        $obtained_mark = $number_of_carrier_count / $total_number_of_order_in_five_days;
                        $percentage = $obtained_mark * 100;

                         $carrierStatus[] = [

                            'TotalCarrier' => $total_number_of_order_in_five_days,
                            'NoOfCarrier' => $number_of_carrier_count,
                            'email' => $email,
                            'carrierName' => $carrier,
                            'percentage' => $percentage

                        ];
                    } // End of Carrier performance

                    echo "Carrier Performance";


                    Mail::to($email)->send(new ShippingPerformance($shippingStatus , $DeliveryStatus , $regionalCity , $carrierStatus));

                    // return $carrierStatus;

                }
                else{
                     echo "Data is not available";
                }
            }
            // print_r($shippingPerformance);
        }

    }
}
