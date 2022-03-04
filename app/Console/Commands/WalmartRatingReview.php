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
        $ratingReview = ShippingManager::count();
        for ($i=0; $i<=$ratingReview;  $i++) {

            $ratingReview = ShippingManager::where('status', 'Pending')
                ->where('module', 'Rating_Review')
                ->first();

            $user_session_id = $ratingReview->marketPlace->user_id;

            $user = User::where('id', '=', $user_session_id)->get();
            $email = $user[0]['email'];

            if ($ratingReview) {

                $client_id = $ratingReview->marketPlace->client_id;
                $client_secret = $ratingReview->marketPlace->client_secret;
                $user_session_id = $ratingReview->marketPlace->user_id;

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
                    'ratingReviewScore' => $response['payload']['score']['ratingReviewScore'],
                    'contentScore' => $response['payload']['score']['contentScore'],
                    'itemDefectCnt' => $response['payload']['postPurchaseQuality']['itemDefectCnt'],
                    'defectRatio' => $response['payload']['postPurchaseQuality']['defectRatio'],
                    'listingQuality' => $response['payload']['listingQuality'],
                    'status' => $response['status']

                ]);

                $walmart_rating_review = RatingReviewModel::all();
                // Retrieve rating review percentage from database


                if(!empty($email))
                {
                    if(isset($walmart_rating_review) && count($walmart_rating_review) > 0)
                    {
                        $mailSenderArray = [];
                        foreach($walmart_rating_review as $review_mail)
                        {
                            $mailSenderArray[] = [

                                'offerScore' => $review_mail['offerScore'],
                                'ratingReviewScore' => $review_mail['ratingReviewScore'],
                                'contentScore' => $review_mail['contentScore'],
                                'itemDefectCnt' => $review_mail['itemDefectCnt'],
                                'defectRatio' => $review_mail['defectRatio'],
                                'listingQuality' => $review_mail['listingQuality'],
                                'status' => $review_mail['status'],
                                'email' => $email
                            ];
                        }
                        Mail::to($email)->send(new RatingReview($mailSenderArray));
                    }
                }

                $manager = ShippingManager::updateStatus($ratingReview->id, "Completed");
                \Log::info("Rating and Review Performance Done");
            }
        }
    }
}
