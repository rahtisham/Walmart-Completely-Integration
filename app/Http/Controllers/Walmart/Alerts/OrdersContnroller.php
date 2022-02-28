<?php

namespace App\Http\Controllers\Walmart\Alerts;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Integration\walmart;
//use App\Mail\regionalPerformance;
use App\Models\Walmart\Order_details;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class OrdersContnroller extends Controller
{
    public function index()
    {
        return view('walmart.alerts.order');
    }

    public function orderDetails(Request $request)
    {

        $client_id = $request->get('clientID');
        $secret = $request->get('clientSecretID');


        $this->validate($request, [

            'clientName' => 'required',
            'clientID' => 'required',
            'clientSecretID' => 'required',

        ]);

        $walmartOrderDetails = Order_details::count();

        if ($walmartOrderDetails > 0) {

            $data_last = Order_details::orderBy('order_date', 'DESC')->first();

            $createdStartDates = $data_last['order_date'];
            $addDay= strtotime($createdStartDates . "+1 days");
            $createdStartDate = date('Y-m-d', $addDay);

            $token = Walmart::getToken($client_id, $secret);

            $response[] = Walmart::getItemOrder($client_id, $secret, $token ,$createdStartDate);
//            return $response;

            if ($response[0]) {

                foreach ($response[0]['list']['elements']['order'] as $res) {

                    $order_date = $res['orderDate'];
                    $estimated_delivery_date = $res['shippingInfo']['estimatedDeliveryDate'];
                    $estimated_ship_date = $res['shippingInfo']['estimatedShipDate'];

                    $orderData = date("Y-m-d", substr($order_date, 0, 10));
                    $estimatedDeliveryDate = date("Y-m-d", substr($estimated_delivery_date, 0, 10));
                    $estimatedShipDate = date("Y-m-d", substr($estimated_ship_date, 0, 10));

                    $walmart_order_details = [
                        'user_id' => "1",
                        'purchaseOrderId' => $res['purchaseOrderId'],
                        'customerOrderId' => $res['customerOrderId'],
                        'order_date' => $orderData,
                        'estimatedDeliveryDate' => $estimatedDeliveryDate,
                        'estimatedShipDate' => $estimatedShipDate,
                        'city' => $res['shippingInfo']['postalAddress']['city'],
                        'state' => $res['shippingInfo']['postalAddress']['state'],
                        'country' => $res['shippingInfo']['postalAddress']['country'],
                        'status' => $res['shippingInfo']['postalAddress']['state'],
                        'postal_code' => $res['shippingInfo']['postalAddress']['postalCode'],
                        'cancellationReason' => $res['orderLines']['orderLine'][0]['orderLineStatuses']['orderLineStatus'][0]['cancellationReason'],
                        'status' => $res['orderLines']['orderLine'][0]['orderLineStatuses']['orderLineStatus'][0]['status'],
                        'shippingProgramType' => $res['orderLines']['orderLine'][0]['fulfillment']['shippingProgramType'],
                    ];

                    $insert_orders = Order_details::create_orders($walmart_order_details);

                }
                // End of Foreach loop
            }
            // End of IF Condition
            else{

                echo "No Current Record in API ";

            }
            // End of else condition
        }
        // End of IF Condition
        else
        {

            $token = Walmart::getToken($client_id, $secret);

            $response[] = Walmart::getItemOrder($client_id, $secret , $token ,$createdStartDate = 0);

            if (count($response) > 0) {

                foreach ($response[0]['list']['elements']['order'] as $res) {

                    $order_date = $res['orderDate'];
                    $estimated_delivery_date = $res['shippingInfo']['estimatedDeliveryDate'];
                    $estimated_ship_date = $res['shippingInfo']['estimatedShipDate'];

                    $orderData = date("Y-m-d", substr($order_date, 0, 10));
                    $estimatedDeliveryDate = date("Y-m-d", substr($estimated_delivery_date, 0, 10));
                    $estimatedShipDate = date("Y-m-d", substr($estimated_ship_date, 0, 10));

                    $walmart_order_details = [
                        'user_id' => "2",
                        'purchaseOrderId' => $res['purchaseOrderId'],
                        'customerOrderId' => $res['customerOrderId'],
                        'order_date' => $orderData,
                        'estimatedDeliveryDate' => $estimatedDeliveryDate,
                        'estimatedShipDate' => $estimatedShipDate,
                        'city' => $res['shippingInfo']['postalAddress']['city'],
                        'state' => $res['shippingInfo']['postalAddress']['state'],
                        'country' => $res['shippingInfo']['postalAddress']['country'],
                        'status' => $res['shippingInfo']['postalAddress']['state'],
                        'postal_code' => $res['shippingInfo']['postalAddress']['postalCode'],
                        'cancellationReason' => $res['orderLines']['orderLine'][0]['orderLineStatuses']['orderLineStatus'][0]['cancellationReason'],
                        'status' => $res['orderLines']['orderLine'][0]['orderLineStatuses']['orderLineStatus'][0]['status'],
                        'shippingProgramType' => $res['orderLines']['orderLine'][0]['fulfillment']['shippingProgramType'],
                    ];

                    $insert_orders = Order_details::create_orders($walmart_order_details);

                }
                // End of Foreach loop
            }
            // End of IF Condition
        }
        // End of ELSE condition
    }
    // End of function Shipping Performance
}
