<?php

namespace App\Console\Commands;

use App\Models\Walmart\Order_details;
use App\Models\Walmart\ShippingManager;
use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class RegionalPerformance extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:RegionalPerformance';

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
        $regionalPerformanceCount = ShippingManager::where('status', 'Pending')
                                                ->where('module', 'Regional_Performance')
                                                ->count();

        if($regionalPerformanceCount > 0){

            for ($i = 0; $i < $regionalPerformanceCount;  $i++) {

                $regionalPerformance = ShippingManager::where('status', 'Pending')
                                                    ->where('module', 'Regional_Performance')
                                                    ->first();


                if ($regionalPerformance) {

                    $user_id = $regionalPerformance->marketPlace->user_id;

                    $user = User::where('id' , '=' , $user_id)->get();
                    $email = $user[0]['email'];

                    $last_delivery_date = Order_details::select('order_date')
                                                        ->where('order_date' , '!=' , null)
                                                        ->latest('order_date')
                                                        ->first();

                    $to = $last_delivery_date['order_date'];
                    $addDay= strtotime($last_delivery_date['order_date'] . "-10 days");
                    $ten_days_ago_delivery_Date = date('Y-m-d', $addDay);

                    $reportShipment = Order_details::whereBetween('order_date', [$ten_days_ago_delivery_Date , $to])->get();
                    $total_number_of_row =  $reportShipment->count();

                    $regionalCity = [];
                    $regionalState = [];

                    $cities = Order_details::select('city')->distinct('city')->whereBetween('order_date', [$ten_days_ago_delivery_Date , $to])->pluck('city');
                    foreach($cities as $city)
                    {
                        $number_of_orders =  Order_details::where('city' , $city)->get()->count();

                        $obtained_mark = $number_of_orders / $total_number_of_row;
                        $percentage = $obtained_mark * 100;

                        $regionalCity[$city] = [

                            'order' => $number_of_orders,
                            'email' => $email,
                            'city' => $city,
                            'percentage' => $percentage

                        ];

                    }

                    $states = Order_details::select('state')->distinct('state')->whereBetween('order_date', [$ten_days_ago_delivery_Date , $to])->pluck('state');
                    foreach($states as $state)
                    {
                        $number_of_orders =  Order_details::where('state' , $state)->get()->count();

                        $obtained_mark = $number_of_orders / $total_number_of_row;
                        $percentage = $obtained_mark * 100;

                        $regionalState[$state] = [

                            'order' => $number_of_orders,
                            'email' => $email,
                            'city' => $state,
                            'percentage' => $percentage

                        ];

                    }

                    Mail::to($email)->send(new \App\Mail\regionalPerformance($regionalCity));

                }

                $manager = ShippingManager::updateStatus($regionalPerformance->id, "Completed");
                \Log::info("Regional Performance Done");
            }
        }

        ShippingManager::where('status', 'Completed')
                        ->where('module', 'Regional_Performance')
                        ->update(['status' => 'Pending']);
    }
}
