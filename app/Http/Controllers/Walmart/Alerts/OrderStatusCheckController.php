<?php

namespace App\Http\Controllers\Walmart\Alerts;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Integration\walmart;
use App\Models\Walmart\Order_details;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class OrderStatusCheckController extends Controller
{
    public function index()
    {
        return view('walmart.alerts.order_status_check');
    }

    public function orderStatusCheck(Request $request)
    {
        ini_set('max_execution_time', '700');

        $client_id = $request->get('clientID');
        $secret = $request->get('clientSecretID');

        $order_status = '';
        $actualShipDateTimes = '';
        $carrierName = '';
        $actualShippingStatus ='';

        $walmart_order = Order_details::where('status', '!=', 'Delivered')->get();
        $token = Walmart::getToken($client_id, $secret);
//        $token = $token['access_token'];  // Token generated


        foreach ($walmart_order as $order_status_databaseTable) {

            $estimatedShipDate = strtotime($order_status_databaseTable['estimatedShipDate']);
            $actualShipDate = strtotime($order_status_databaseTable['actualShipDate']);

            $order_purchade_id = $order_status_databaseTable['purchaseOrderId'];
            $response = Walmart::getItemAnOrder($client_id, $secret, $token, $order_purchade_id);
            $live_status = $response['order']['orderLines']['orderLine'][0]['orderLineStatuses']['orderLineStatus'][0]['status'];

            if($response['order']['orderLines']['orderLine'][0]['orderLineStatuses']['orderLineStatus'][0]['trackingInfo'] != null){
                $actualShipDateTime =  $response['order']['orderLines']['orderLine'][0]['orderLineStatuses']['orderLineStatus'][0]['trackingInfo']['shipDateTime'];
                $actualShipDateTimes = date("Y-m-d", substr($actualShipDateTime, 0, 10));

                $carrierName =  $response['order']['orderLines']['orderLine'][0]['orderLineStatuses']['orderLineStatus'][0]['trackingInfo']['carrierName']['carrier'];
            }

            if($actualShipDate < $estimatedShipDate)
            {
                $actualShippingStatus = "Excellent";
            }
            elseif($actualShipDate == $estimatedShipDate)
            {
                $actualShippingStatus = "Good";
            }
            elseif($actualShipDate > $estimatedShipDate)
            {
                $actualShippingStatus = "Poor";
            }

            if($live_status == 'Acknowledged'){
                $order_status = 'Acknowledged101';
                $query = Order_details::where('purchaseOrderId', $order_purchade_id)
                                         ->update(['status' => $order_status]);
            }
            elseif($live_status == 'Created'){
                $order_status = 'Created101';
                $query = Order_details::where('purchaseOrderId', $order_purchade_id)
                                        ->update(['status' => $order_status]);
            }
            elseif($live_status == 'Shipped'){
                $order_status = 'Shipped101';

                $query = Order_details::where('purchaseOrderId', $order_purchade_id)
                                         ->update(['status' => $order_status ,
                                        'actualShipDate' => $actualShipDateTimes,
                                        'carrierName' => $carrierName,
                                        'actualShipStatus' => $actualShippingStatus]);
            }
            elseif($live_status == 'Delivered'){
                $order_status = 'Delivered101';
                $query = Order_details::where('purchaseOrderId', $order_purchade_id)
                                         ->update(['status' => $order_status,
                                        'actualDeliveryDate' => date('Y-m-d')]);
            }

            // End of if condition regarding created
        }

        // End of foreach loop
    }
    // End of order_status_check
}
