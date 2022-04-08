<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\plans;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Session;
use Stripe;

class StripePaymentController extends Controller
{

    /**
     * success response method.
     *
     * @return \Illuminate\Http\Response
     */
    public function stripe()
    {
        return view('stripe');
    }

    /**
     * success response method.
     *
     * @return \Illuminate\Http\Response
     */


     public function index($subscription)
     {
        if (auth()->user()) {
            return redirect('user/marketplace');
        } else {

            $marketPlace = plans::where('marketPlace' , $subscription)->get();
            $subscriptionName = $marketPlace[0]['planName'];
            $amount = $marketPlace[0]['amount'];
            $marketPlace = $marketPlace[0]['marketPlace'];

            return view('checkout.stripecheckout', ['amount' => $amount, 'marketPlace' => $marketPlace , 'subscriptionName' => $subscriptionName]);
        }
     }


     public function stripePayment(Request $request)
     {
         return $request;

        $validator = Validator::make($request->all(), [
            'fname' => 'required', 'max:255',
            'email' => 'required|email|max:255|regex:/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix|unique:users',
            'lname' => ['required', 'alpha', 'max:255'],
            'address' => ['required', 'string', 'max:255'],
            'city' => ['required', 'alpha', 'max:255'],
            'postal' => ['required', 'max:7'],
            'country' => ['required', 'max:255'],
            'state' => ['required', 'alpha', 'max:255'],
            'contact' => ['required', 'string', 'max:255'],
            'owner' => ['required', 'max:255'],
            'cardNumber' => ['required', 'min:16', 'max:16'],
            'expiration-year' => ['required', 'string', 'max:255'],
            'expiration-month' => ['required', 'string', 'max:255'],
            'cvv' => ['required', 'max:3', 'min:3'],
            // 'amount' => ['required', 'max:255'],
            'password' => 'required',
            'password_confirmation' => 'required',
            'agreement' => 'required',
            'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature() ? ['accepted', 'required'] : '',
        ], [
            'email.required' => 'Email is required',
            'fname.required' => 'First name is required',
            'fname.alpha' => 'First name must only contain letters',
            'lname.required' => 'First name is required',
            'lname.alpha' => 'Last name must only contain letters',
            'address.required' => 'Address is required',
            'city.required' => 'City is required',
            'state.required' => 'State is required',
            'contact.required' => 'Phone number is required',
            'postal.required' => 'Postal code is required',
            // 'amount.required' => 'Amount is required',
            'country.required' => 'Country is required',
            'cvv.required' => 'CVV is required',
            'cardNumber.required' => 'Card number is required',
            'owner.required' => 'Card holder name is required',
            'password.required' => 'Password is required',
            'agreement.required' => 'Agreement is required',
            'password_confirmation.required' => 'Confirm password is required',
        ])->validate();
     }

    public function stripePost(Request $request)
    {

        // return $request;
        Stripe\Stripe::setApiKey('sk_test_51IlK6HDoULpDRQsxvnaIQ4mSksoxJwlTMfAcxmpOUnWmuODvX8MWQkcKildVidhh9Cb8c4XRWvIvlmA2DYjozWoK00E5m9lbdk');
        Stripe\Charge::create ([
                "amount" => 147*100,
                "currency" => "USD",
                "source" => $request->stripeToken,
                "description" => "This payment is testing purpose of websolutionstuff.com",
        ]);

        Session::flash('success', 'Payment Successful !');

        return back();
    }
}
