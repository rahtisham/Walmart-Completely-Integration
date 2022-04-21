<?php

namespace App\Console\Commands;

use App\Integration\Walmart;
use App\Models\Walmart\ItemsManager;
use App\Models\Walmart\Order_details;
use App\Models\Walmart\OrderManager;
use Illuminate\Console\Command;

class WalmartAnOrder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:WalmartAnOrder';

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
        $anOrderCount = OrderManager::where('status', 'Pending')
                                    ->where('module' , 'An_Order')
                                    ->count();

        if($anOrderCount > 0){

            for ($i = 0; $i < $anOrderCount;  $i++) {

                $anOrderManager = OrderManager::where('status', 'Pending')
                                                ->where('module' , 'An_Order')
                                                ->first();

                $client_id = $anOrderManager->marketPlace->client_id;
                $client_secret = $anOrderManager->marketPlace->client_secret;
                $user_session_id = $anOrderManager->marketPlace->user_id;
                $mid = $anOrderManager->marketPlace->id;

                if ($anOrderManager) {

                    ini_set('max_execution_time', '700');


                    $order_status = '';
                    $actualShipDateTimes = '';
                    $carrierName = '';
                    $actualShippingStatus ='';

                    $walmart_order = Order_details::where('status', '!=', 'Delivered')->get();
                    $token = Walmart::getToken($client_id, $client_secret);



                    foreach ($walmart_order as $order_status_databaseTable) {

                        $estimatedShipDate = strtotime($order_status_databaseTable['estimatedShipDate']);
                        $actualShipDate = strtotime($order_status_databaseTable['actualShipDate']);

                        $order_purchade_id = $order_status_databaseTable['purchaseOrderId'];
                        $response = Walmart::getItemAnOrder($client_id, $client_secret, $token, $order_purchade_id);
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

                    $manager = OrderManager::updateStatusAnOrder($anOrderManager->id, "Completed");
                    \Log::info("Regional Performance Done");
                }
            }
        }
        // OrderManager::where('status', 'Completed')
        //                 ->where('module', 'An_Order')
        //                 ->update(['status' => 'Pending']);
    }
}
