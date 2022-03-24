<?php

namespace App\Console\Commands;

use App\Integration\Walmart;
use App\Mail\RatingReview;
use App\Models\User;
use App\Models\Walmart\RatingReviewModel;
use App\Models\Walmart\ShippingManager;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class WalmartRatingReview extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:WalmartRatingReview';

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
         $ratingReviewCount = ShippingManager::where('status', 'Pending')
                                        ->where('module', 'Rating_Review')
                                        ->count();
        if($ratingReviewCount > 0){

            for ($i = 0; $i < $ratingReviewCount; $i++) {
               // \Log::info($ratingReview);

               $ratingReview = ShippingManager::where('status', 'Pending')
                                                   ->where('module', 'Rating_Review')
                                                   ->first();


                if ($ratingReview) {
                    $user_id = $ratingReview->marketPlace->user_id;
                    $m_id = $ratingReview->marketPlace->id;

                    $user = User::where('id', '=', $user_id)->get();
                    $email = $user[0]['email'];

                   $client_id = $ratingReview->marketPlace->client_id;
                   $client_secret = $ratingReview->marketPlace->client_secret;

                   $token = Walmart::getToken($client_id, $client_secret);

                   $response = walmart::getItemRatingReview($client_id, $client_secret, $token);


                   $offerScore = $response['payload']['score']['offerScore'];
                   $ratingReviewScore = $response['payload']['score']['ratingReviewScore'];
                   $contentScore = $response['payload']['score']['contentScore'];

                   $itemDefectCnt = $response['payload']['postPurchaseQuality']['itemDefectCnt'];
                   $defectRatio = $response['payload']['postPurchaseQuality']['defectRatio'];

                   $listingQuality = $response['payload']['listingQuality'];
                   $status = $response['status'];

                   $ratingRaview = RatingReviewModel::Create([

                       'offerScore' => $response['payload']['score']['offerScore'],
                       'user_id' => $user_id,
                       'm_id' => $m_id,
                       'ratingReviewScore' => $response['payload']['score']['ratingReviewScore'],
                       'contentScore' => $response['payload']['score']['contentScore'],
                       'itemDefectCnt' => $response['payload']['postPurchaseQuality']['itemDefectCnt'],
                       'defectRatio' => $response['payload']['postPurchaseQuality']['defectRatio'],
                       'listingQuality' => $response['payload']['listingQuality'],
                       'status' => $response['status']

                   ]);

                   // Retrieve rating review percentage from database


                   if(!empty($email))
                   {
                       if($ratingRaview)
                       {
                        $mailSenderArray[] = [

                            'offerScore'        => $ratingRaview['offerScore'],
                            'ratingReviewScore' => $ratingRaview['ratingReviewScore'],
                            'contentScore'      => $ratingRaview['contentScore'],
                            'itemDefectCnt'     => $ratingRaview['itemDefectCnt'],
                            'defectRatio'       => $ratingRaview['defectRatio'],
                            'listingQuality'    => $ratingRaview['listingQuality'],
                            'status'            => $ratingRaview['status'],
                            'email'             => $email
                        ];
                           Mail::to($email)->send(new RatingReview($mailSenderArray));
                       }
                   }

                   $manager = ShippingManager::updateStatus($ratingReview->id, "Completed");
                   \Log::info("Rating and Review Performance Done");
               }
           }

        }


        ShippingManager::where('status', 'Completed')
                        ->where('module', 'Rating_Review')
                        ->update(['status' => 'Pending']);

    }
}
