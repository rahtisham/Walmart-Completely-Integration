<?php

namespace App\Console\Commands;

use App\Mail\OnTimeDeliveryAlert;
use App\Models\User;
use App\Models\Walmart\Order_details;
use App\Models\Walmart\ShippingManager;
use Illuminate\Console\Command;
use App\Integration\walmart;
use App\Mail\SendMail;
use App\Models\WalmartMarketPlace;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Config;

class WalmartDelivery extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:walmartDelivery';

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
        $delivery = ShippingManager::count();
        for ($i=0; $i<=$delivery;  $i++) {

            $DeliveryManager = ShippingManager::where('status', 'Pending')
                                                ->where('module', 'On_Time_Delivery')
                                                ->first();

            if ($DeliveryManager) {

                $user_session_id = $DeliveryManager->marketPlace->user_id;

                $last_delivery_date = order_details::select('actualDeliveryDate')
                    ->where('actualDeliveryDate' , '!=' , null)
                    ->latest('actualDeliveryDate')
                    ->first();

                $to = $last_delivery_date['actualDeliveryDate'];
                $addDay= strtotime($last_delivery_date['actualDeliveryDate'] . "-10 days");
                $ten_days_ago_delivery_Date = date('Y-m-d', $addDay);
//                \Log::info("Status has been updated" . $to);

                $reportDelivery = order_details::whereBetween('actualDeliveryDate', [$ten_days_ago_delivery_Date , $to])->get();

                $user = User::where('id' , '=' , $user_session_id)->get();
                $email = $user[0]['email'];

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
                            'email' => $email,
                            'status' => $actualDeliveryStatus,
                        ];

                    }

                    Mail::to($email)->send(new OnTimeDeliveryAlert($report_generate));

                }

                $manager = ShippingManager::updateStatus($DeliveryManager->id, "Completed");
                 \Log::info("Delivery On Time Done");
            }
        }
    }
}
