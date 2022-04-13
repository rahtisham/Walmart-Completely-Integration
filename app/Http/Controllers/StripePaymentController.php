<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\plans;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Laravel\Jetstream\Jetstream;
use App\Models\PaymentLogs;
use App\Mail\RegisteredNotification;
use App\Models\User;
use Session;
use Stripe;
use Exception;
use phpDocumentor\Reflection\Types\Null_;

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
            $stripe_plan = $marketPlace[0]['stripe_plan'];
            $subscriptionName = $marketPlace[0]['planName'];
            $amount = $marketPlace[0]['amount'];
            $marketPlace = $marketPlace[0]['marketPlace'];


            return view('checkout.stripecheckout', ['amount' => $amount, 'marketPlace' => $marketPlace , 'subscriptionName' => $subscriptionName , 'stripePlan' => $stripe_plan ]);
        }
     }


     public function stripePayment(Request $request)
     {

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


        $userData = [
            'name' => $request->fname,
            'email' => $request->email,
            'last_name' => $request->lname,
            'address' => $request->address,
            'city' => $request->city,
            'roles' => "1",
            'postal' => $request->postal,
            'country' => $request->country,
            'state' => $request->state,
            'contact' => $request->contact,
            'password' => Hash::make($request->password),
        ];

        $user = User::store($userData);

        $plan = $request->stripePlan;
        $token =  $request->stripeToken;
        $paymentMethod = $request->paymentMethod;


        try {

              // Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
              Stripe\Stripe::setApiKey('sk_test_51JLDlJJFs9GUB8DUfljuNoy6mWMZn7Fq7EqvUQkv2p5Ts8L6tpkkU7nnAiACqZwHmiLVYW12tnZbQM8aYZW1sTBK00rYagYsXE');


            if (is_null($user->stripe_id)) {
                 $stripeCustomer = $user->createAsStripeCustomer();
            }

            \Stripe\Customer::createSource(
                $user->stripe_id,
                ['source' => $token]
            );


            $subscription = $user->newSubscription('Cashier' , $plan)
                ->create($paymentMethod, [
                'email' => $user->email,
            ]);


            $paymentlog = [
                'amount' => $request->amount,
                'name_on_card' => $request->owner,
                'message_code' => $request->platform,
                'subscriptionName' => $request->subscriptionName,
                'status' => 'active',
                'subscription' => $plan,
            ];

             $paymentLog = PaymentLogs::createPaymentLog($paymentlog);



            $payment = PaymentLogs::where('id', $paymentLog->id)->update(['user_id' => $user->id]);

            $registredNotification =
                [
                    'name' => $user->name,
                    'lname' => $user->last_name,
                    'email' => $user->email,
                    'name_on_card' => $paymentLog->name_on_card,
                    'amount' => $paymentLog->amount,
                    'address' => $user->address,
                    'contact' => $user->contact
                ];


            Mail::to('ahtisham@amzonestep.com')->send(new RegisteredNotification($registredNotification));

            return redirect('/login')->with(['success' => 'Your Appeal Lab Account Has Been Created !']);

        } catch ( \Stripe\Error\Card $e ) {

            return back()->withErrors(['error' => 'Unable to create subscription due to this' . $e->get_message()]);

        }


     }


    public function stripePost(Request $request)
    {


        $plan = 'plan_LVAK3nrBHUFDdg';

         $user = auth()->user();
         $input = $request->all();
          $token =  $request->stripeToken;
         $paymentMethod = $request->paymentMethod;

        try {

            // Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
            Stripe\Stripe::setApiKey('sk_test_51JLDlJJFs9GUB8DUfljuNoy6mWMZn7Fq7EqvUQkv2p5Ts8L6tpkkU7nnAiACqZwHmiLVYW12tnZbQM8aYZW1sTBK00rYagYsXE');

            $stripeCustomer = null;
            if (is_null($user->stripe_id)) {
                  $stripeCustomer = $user->createAsStripeCustomer();
            }
            // Stirpe id should be null in user table

            \Stripe\Customer::createSource(
                $user->stripe_id,
                ['source' => $token]
            );
            // Token to create subscription


            $subscription = $user->newSubscription('Cashier' , $plan)
                ->create($paymentMethod, [
                'email' => $user->email,
            ]);
            // Subsription created with plan


            return back()->with('success','Subscription is completed.');
        } catch (Exception $e) {
            return back()->with('success',$e->getMessage());
        }



    }
}
