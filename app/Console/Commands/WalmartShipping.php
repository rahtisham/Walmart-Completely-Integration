<?php

namespace App\Console\Commands;

use App\Mail\OnTimeDeliveryAlert;
use App\Mail\OnTimeShipping;
use App\Models\User;
use App\Models\Walmart\Order_details;
use App\Models\Walmart\ShippingManager;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class WalmartShipping extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:WalmartShipping';

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
        $shipmentCount = ShippingManager::where('status', 'Pending')
                                    ->where('module', 'On_Time_Shipping')
                                    ->count();

        if($shipmentCount > 0){
            for ($i = 0; $i < $shipmentCount;  $i++) {


                $shipment = ShippingManager::where('status', 'Pending')
                                                ->where('module', 'On_Time_Shipping')
                                                ->first();

                $user_session_id = $shipment->marketPlace->user_id;


                if ($shipment) {

                    $last_shipment_date = Order_details::select('actualShipDate')
                                                        ->where('actualShipDate' , '!=' , null)
                                                        ->latest('actualShipDate')
                                                        ->first();

                    $to = $last_shipment_date['actualShipDate'];
                    $addDay= strtotime($last_shipment_date['actualShipDate'] . "-10 days");
                    $ten_days_ago_shipment_Date = date('Y-m-d', $addDay);


                    $reportShipment = Order_details::whereBetween('actualShipDate', [$ten_days_ago_shipment_Date , $to])->get();

                    $user = User::where('id' , '=' , $user_session_id)->get();
                    $email = $user[0]['email'];

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
                                'email' => $email,
                                'status' => $actualShippingStatus,
                            ];


                        }

                        Mail::to($email)->send(new OnTimeShipping($report_generate));
                        unset($report_generate);

                    }

                    $manager = ShippingManager::updateStatus($shipment->id, "Completed");
                    \Log::info("On Time Shipment is Done");
                }
            }
        }

        ShippingManager::where('status', 'Completed')
                        ->where('module', 'On_Time_Shipping')
                        ->update(['status' => 'Pending']);

    }
}
