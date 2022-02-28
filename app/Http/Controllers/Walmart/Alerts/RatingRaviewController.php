<?php

namespace App\Http\Controllers\Walmart\Alerts;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Integration\walmart;
use App\Mail\RatingReview;
use App\Models\Walmart\RatingReviewModel;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class RatingRaviewController extends Controller
{
    public function index()
    {
        return view('walmart.alerts.rating_review');
    }
    public function ratingReview(Request $request)
    {
        $client_id = $request->get('clientID');
        $secret = $request->get('clientSecretID');

        $token = Walmart::getToken($client_id, $secret);
        $token = $token['access_token'];  // Token generated

        $response = walmart::getItemRatingReview($client_id, $secret, $token);


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

        $user = User::where('id', '=', '1')->get()->first();
        $email = $user->email;
        // match condition to unique user

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

        return redirect()->back()->withSuccess('Rating and  Review Email has Sent Successfully');
    }
}
