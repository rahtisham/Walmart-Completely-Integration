<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Walmart\OrderManager;
use App\Models\Walmart\Order_details;
use App\Integration\Walmart;
use App\Models\WalmartMarketPlace;
use Illuminate\Support\Facades\Config;

class WalmartOrders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:walmartOrder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $orderCount = OrderManager::where('status', 'Pending')
                                ->where('module' , 'All_Order')
                                ->count();

        if($orderCount > 0){

            for ($i = 0; $i < $orderCount;  $i++) {

                $orderManager = OrderManager::where('status', 'Pending')
                                            ->where('module' , 'All_Order')
                                            ->first();
                if($orderManager)
                {
                    $client_id = $orderManager->marketPlace->client_id;
                    $client_secret = $orderManager->marketPlace->client_secret;
                    $user_id = $orderManager->marketPlace->user_id;
                    $mid = $orderManager->marketPlace->id;

                    $walmartOrderDetails = Order_details::count();

                    if ($walmartOrderDetails > 0) {

                        $data_last = Order_details::orderBy('order_date', 'DESC')->first();

                        $createdStartDates = $data_last['order_date'];
                        $addDay= strtotime($createdStartDates . "+1 days");
                        $createdStartDate = date('Y-m-d', $addDay);

                        $token = Walmart::getToken($client_id, $client_secret);

                        $response[] = Walmart::getItemOrder($client_id, $client_secret, $token ,$createdStartDate);

                        if ($response[0]) {

                            foreach ($response[0]['list']['elements']['order'] as $res) {

                                $order_date = $res['orderDate'];
                                $estimated_delivery_date = $res['shippingInfo']['estimatedDeliveryDate'];
                                $estimated_ship_date = $res['shippingInfo']['estimatedShipDate'];

                                $orderData = date("Y-m-d", substr($order_date, 0, 10));
                                $estimatedDeliveryDate = date("Y-m-d", substr($estimated_delivery_date, 0, 10));
                                $estimatedShipDate = date("Y-m-d", substr($estimated_ship_date, 0, 10));

                                $walmart_order_details = [
                                    'user_id' => $user_id,
                                    'm_id' => $mid,
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

                        $token = Walmart::getToken($client_id, $client_secret);

                        $response[] = Walmart::getItemOrder($client_id, $client_secret , $token ,$createdStartDate = 0);

                        if (count($response) > 0) {

                            foreach ($response[0]['list']['elements']['order'] as $res) {

                                $order_date = $res['orderDate'];
                                $estimated_delivery_date = $res['shippingInfo']['estimatedDeliveryDate'];
                                $estimated_ship_date = $res['shippingInfo']['estimatedShipDate'];

                                $orderData = date("Y-m-d", substr($order_date, 0, 10));
                                $estimatedDeliveryDate = date("Y-m-d", substr($estimated_delivery_date, 0, 10));
                                $estimatedShipDate = date("Y-m-d", substr($estimated_ship_date, 0, 10));

                                $walmart_order_details = [
                                    'user_id' => $user_id,
                                    'm_id' => $mid,
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

                    $manager = OrderManager::updateStatus($orderManager->id, "Completed");

                }


                \Log::info("All Orders has been submited");
            }
        }
        OrderManager::where('status', 'Completed')
                        ->where('module', 'All_Order')
                        ->update(['status' => 'Pending']);
    }
}
