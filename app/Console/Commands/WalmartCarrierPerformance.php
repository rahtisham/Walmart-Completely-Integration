<?php

namespace App\Console\Commands;

use App\Mail\carrierPerformance;
use App\Models\Walmart\Order_details;
use App\Models\User;
use App\Models\Walmart\ShippingManager;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class WalmartCarrierPerformance extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:WalmartCarrierPerformance';

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
        $carrierPerformance = ShippingManager::count();
        for ($i=0; $i<=$carrierPerformance;  $i++) {

            $carrierPerformance = ShippingManager::where('status', 'Pending')
                                                ->where('module', 'Carrier_Performance')
                                                ->first();
            $user_session_id = $carrierPerformance->marketPlace->user_id;

            $user = User::where('id' , '=' , $user_session_id)->get();
            $email = $user[0]['email'];

            if ($carrierPerformance) {

                $last_delivery_date = Order_details::select('order_date')
                    ->where('order_date' , '!=' , null)
                    ->latest('order_date')
                    ->first();

                $to = $last_delivery_date['order_date'];
                $addDay= strtotime($last_delivery_date['order_date'] . "-10 days");
                $ten_days_ago_delivery_Date = date('Y-m-d', $addDay);

                $reportShipment = Order_details::whereBetween('order_date', [$ten_days_ago_delivery_Date , $to])->get();
                $total_number_of_row =  $reportShipment->count();

                $carrierPer = [];


                $carrierPerformance = Order_details::select('carrierName')->distinct('carrierName')->whereBetween('order_date', [$ten_days_ago_delivery_Date , $to])->pluck('carrierName');
                foreach($carrierPerformance as $carrier)
                {
                    $number_of_carrier =  Order_details::where('carrierName' , $carrier)->get()->count();

                    $obtained_mark = $number_of_carrier / $total_number_of_row;
                    $percentage = $obtained_mark * 100;

                    $carrierPer[$carrier] = [

                        'numberCarrier' => $number_of_carrier,
                        'email' => $email,
                        'carrierName' => $carrier,
                        'percentage' => $percentage

                    ];

                }

                Mail::to($email)->send(new carrierPerformance($carrierPer));
                $manager = ShippingManager::updateStatus($carrierPerformance->id, "Completed");
                \Log::info("Carrier Performance Done");
            }
        }


    }
}
