<?php

namespace App\Http\Controllers\Subscription;

use Carbon\Carbon;
use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;
use App\Models\PaymentLogs;
use App\Models\User;
use App\Models\plans;
use App\Models\subscription;
use App\Mail\RegisteredNotification;
use Illuminate\Support\Facades\Validator;
use net\authorize\api\contract\v1 as AnetAPI;
use net\authorize\api\controller as AnetController;
use Session;
use Stripe;
use Exception;


class SubscriptionController extends Controller
{

    protected $stripe;

    public function __construct()
    {
        $this->stripe = new \Stripe\StripeClient('sk_test_51JLDlJJFs9GUB8DUfljuNoy6mWMZn7Fq7EqvUQkv2p5Ts8L6tpkkU7nnAiACqZwHmiLVYW12tnZbQM8aYZW1sTBK00rYagYsXE');
    }


    public function index()
    {
        $auth_session_id = auth()->user()->id;
        $subscriptions = subscription::where('user_id' , $auth_session_id)->get();
        return view('subscription.index' , ['subscriptions' => $subscriptions]);
    }

    public function subscriptionView()
    {

        $subscriptions = PaymentLogs::all();
        return view('admin.subscription.index' , ['subscriptions' => $subscriptions]);
    }

    public function cancelSubscription($subscriptionId)
    {


        \Stripe\Stripe::setApiKey('sk_test_51JLDlJJFs9GUB8DUfljuNoy6mWMZn7Fq7EqvUQkv2p5Ts8L6tpkkU7nnAiACqZwHmiLVYW12tnZbQM8aYZW1sTBK00rYagYsXE');

        $subscription = \Stripe\Subscription::retrieve($subscriptionId);
        $cancelSubscription = $subscription->cancel();

        if($cancelSubscription)
        {
            $active_user_id = auth()->user()->id;
            $user = User::where('id' , $active_user_id)->update(['roles' => '4']);
            $paymentLog = subscription::where('user_id' , $active_user_id)->update(['stripe_status' => 'cancel']);
            return redirect('/login');
        }

        else
        {
            return redirect()->back();
        }

        /* Create a merchantAuthenticationType object with authentication details
           retrieved from the constants file */
        // $merchantAuthentication = new AnetAPI\MerchantAuthenticationType();
        // $merchantAuthentication->setName('7LfUeM3n5r');
        // $merchantAuthentication->setTransactionKey('52Z8Tf9QsM7Twq23');

        // // Set the transaction's refId
        // $refId = 'ref' . time();

        // $request = new AnetAPI\ARBCancelSubscriptionRequest();
        // $request->setMerchantAuthentication($merchantAuthentication);
        // $request->setRefId($refId);
        // $request->setSubscriptionId($subscriptionId);

        // $controller = new AnetController\ARBCancelSubscriptionController($request);

        // $response = $controller->executeWithApiResponse( \net\authorize\api\constants\ANetEnvironment::SANDBOX);

        // if (($response != null) && ($response->getMessages()->getResultCode() == "Ok"))
        // {
        //     $successMessages = $response->getMessages()->getMessage();
        //     // echo "SUCCESS : " . $successMessages[0]->getCode() . "  " .$successMessages[0]->getText() . "\n";

        //     $active_user_id = auth()->user()->id;
        //     $user = User::where('id' , $active_user_id)->update(['roles' => '4']);
        //     $paymentLog = Payment::where('user_id' , $active_user_id)->update(['status' => 'cancel']);
        //     return redirect('/login');
        // }
        // else
        // {
        //     echo "ERROR :  Invalid response\n";
        //     $errorMessages = $response->getMessages()->getMessage();
        //     echo "Response : " . $errorMessages[0]->getCode() . "  " .$errorMessages[0]->getText() . "\n";

        // }

        // return $response;

      }


      public function plan()
      {
          $plans = plans::all();
          return view('subscription.plan' , ['plans' => $plans]);
      }
      // For Cancel subsciption

      public function planView()
      {
        return $plans = plans::all();
        return view('admin.plans.index' , ['plans' => $plans]);
      }
      // For admin access

      public function createPlan(Request $request)
      {

        $validator = Validator::make($request->all(), [
            'plan' => ['required'],
            'marketplace' => ['required', 'string'],
            'amount' => ['required', 'string', 'max:255'],
        ], [
            'plan.required' => 'Plan is required',
            'marketplace.required' => 'Marketplace is required',
            'amount.required' => 'Amount name is required',
        ])->validate();

        $data = $request->except('_token');
        $price = $data['amount'] * 100;

        //create stripe product
        $stripeProduct = $this->stripe->products->create([
            'name' => $data['plan'],
        ]);

        //Stripe Plan Creation
        $stripePlanCreation = $this->stripe->plans->create([
            'amount' => $price,
            'currency' => 'usd',
            'interval' => 'month', //  it can be day,week,month or year
            'product' => $stripeProduct->id,
        ]);
        // return $stripePlanCreation;

        $stripe_plan = $stripePlanCreation->id;
        $plan = [

            'planName' => $request['plan'],
            'stripe_plan' => $stripe_plan,
            'marketPlace' => $request['marketplace'],
            'amount' => $price,

        ];

        $store = plans::store($plan);

        return back()->with('success' , 'Plan Has Been Created');


      }

      public function subscription($subscription)
      {
        if (!empty($subscription)) {

            // Here aurgament marketplace from database
            // $marketPlace = plans::where('marketPlace' , $subscription)->get();
            // $subscriptionName = $marketPlace[0]['planName'];
            // $amount = $marketPlace[0]['amount'];
            // $marketPlace = $marketPlace[0]['marketPlace'];

            // return view('subscription.create-subscription', ['amount' => $amount, 'marketPlace' => $marketPlace , 'subscriptionName' => $subscriptionName]);

            // This is for authorize.net

              $subscriptions = plans::where('Stripe_plan' , $subscription)->get();
              $Stripe_plan = $subscriptions[0]['stripe_plan'];
              $amount = $subscriptions[0]['amount'];
              $subscriptionName = $subscriptions[0]['planName'];

            return view('subscription.create-subscription', ['amount' => $amount, 'Stripe_plan' => $Stripe_plan, 'subscriptionName' => $subscriptionName]);
            // This is for stripe subscription create
        }

      }

      public function subscriptionAdded(Request $request)
      {

        $validator = Validator::make($request->all(), [
            'owner' => ['required', 'max:255'],
            'cardNumber' => ['required', 'min:16', 'max:16'],
            'expiration-year' => ['required', 'string', 'max:255'],
            'expiration-month' => ['required', 'string', 'max:255'],
            'cvc' => ['required', 'max:3', 'min:3'],
            'subscriptionName' => ['required', 'string'],
        ], [
            'cvc.required' => 'CVV is required',
            'cardNumber.required' => 'Card number is required',
            'owner.required' => 'Card holder name is required',
            'expiration-month.required'=> 'Expiration-Month name is required',
            'expiration-year.required'=> 'Expiration-Year name is required',
            'subscriptionName.required' => 'Subscription name is required',
        ])->validate();

        $user = User::where('id' , auth()->user()->id)->first();

        $plan = $request->stripePlan;
        $token =  $request->stripeToken;
        $subscriptionName = $request->subscriptionName;
        $paymentMethod = $request->paymentMethod;

        try {

            Stripe\Stripe::setApiKey('sk_test_51JLDlJJFs9GUB8DUfljuNoy6mWMZn7Fq7EqvUQkv2p5Ts8L6tpkkU7nnAiACqZwHmiLVYW12tnZbQM8aYZW1sTBK00rYagYsXE');


          if (is_null($user->stripe_id)) {
               $stripeCustomer = $user->createAsStripeCustomer();
          }

          \Stripe\Customer::createSource(
              $user->stripe_id,
              ['source' => $token]
          );


           $subscription = $user->newSubscription($subscriptionName , $plan)
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

           $session_id = auth()->user()->id;
           $user_id = User::where('id' , $session_id)->update(['roles' => '1']);

          return redirect('/')->with(['success' => 'Your Appeal Lab Account Has Been Created !']);

        } catch ( \Stripe\Error\Card $e ) {

            return back()->withErrors(['error' => 'Unable to create subscription due to this' . $e->get_message()]);

        }


        // $createSubscribtion = $this->createSubscription($request);

        // $payment = PaymentLogs::where('id', $createSubscribtion->id)->update(['user_id' => $session_id]);
        // Mail::to('info@appeallab.com')->send(new RegisteredNotification($registredNotification));

        // $session_id = auth()->user()->id;
        // $user_id = User::where('id' , $session_id)->update(['roles' => '1']);

        // authorize created by this query using static function

      }

      public function createSubscription($data){

        // return $data;
        $paymentLog = "";
        $intervalLength = 30;
        $invoice = strtotime("now");


        $startDate = new Carbon();
        // For date Time

        $session_id = auth()->user()->id;
        $user_id = User::where('id' , $session_id)->first();

        /* Create a merchantAuthenticationType object with authentication details
           retrieved from the constants file */
        $merchantAuthentication = new AnetAPI\MerchantAuthenticationType();
        $merchantAuthentication->setName('7LfUeM3n5r');
        $merchantAuthentication->setTransactionKey('52Z8Tf9QsM7Twq23');

        // Set the transaction's refId
        $refId = 'ref' . time();
        $cardNumber = preg_replace('/\s+/', '', $data['cardNumber']);

        // Subscription Type Info
        $subscription = new AnetAPI\ARBSubscriptionType();
        $subscription->setName($data['subscriptionName']);

        $interval = new AnetAPI\PaymentScheduleType\IntervalAType();
        $interval->setLength($intervalLength);
        $interval->setUnit("days");

        $paymentSchedule = new AnetAPI\PaymentScheduleType();
        $paymentSchedule->setInterval($interval);
        $paymentSchedule->setStartDate($startDate);
        $paymentSchedule->setTotalOccurrences("12");
        $paymentSchedule->setTrialOccurrences("1");

        $subscription->setPaymentSchedule($paymentSchedule);
        $subscription->setAmount($data['amount']);
        $subscription->setTrialAmount("0.00");

        $creditCard = new AnetAPI\CreditCardType();
        $creditCard->setCardNumber($cardNumber);
        $creditCard->setExpirationDate($data['expiration-year'] . "-" . $data['expiration-month']);
        $creditCard->setCardCode($data['cvv']);

        $payment = new AnetAPI\PaymentType();
        $payment->setCreditCard($creditCard);
        $subscription->setPayment($payment);

        $order = new AnetAPI\OrderType();
        $order->setInvoiceNumber("324234233". $invoice);
        $order->setDescription("Description of the subscription");
        $subscription->setOrder($order);

        $billTo = new AnetAPI\NameAndAddressType();
        $billTo->setFirstName($user_id->name);
        $billTo->setLastName($user_id->last_name);

        $subscription->setBillTo($billTo);

        $request = new AnetAPI\ARBCreateSubscriptionRequest();
        $request->setmerchantAuthentication($merchantAuthentication);
        $request->setRefId($refId);
        $request->setSubscription($subscription);
        $controller = new AnetController\ARBCreateSubscriptionController($request);

        $response = $controller->executeWithApiResponse(\net\authorize\api\constants\ANetEnvironment::SANDBOX);

        if (($response != null) && ($response->getMessages()->getResultCode() == "Ok")) {

            $response->getSubscriptionId();
            $paymentlog = [
                'amount' => $data->amount,
                'name_on_card' => $data->owner,
                'message_code' => $data->platform,
                'subscriptionName' => $data->subscriptionName,
                'status' => 'active',
                'subscription' => $response->getSubscriptionId()
            ];

            return $paymentLog = PaymentLogs::createPaymentLog($paymentlog);
        } else {
            echo "ERROR :  Invalid response\n";
            $errorMessages = $response->getMessages()->getMessage();
            echo "Response : " . $errorMessages[0]->getCode() . "  " . $errorMessages[0]->getText() . "\n";
        }

    }

    public function planEditView($id)
    {
        $plans = plans::where('id' , $id)->first();
        return view('admin.plans.edit-view' , ['plans' => $plans]);
    }

    public function editPlan(Request $request , $id)
    {
        $validator = Validator::make($request->all(), [
            'plan' => ['required', 'min:10' , 'max:45'],
            'marketplace' => ['required', 'string'],
            'amount' => ['required', 'string', 'max:255'],
        ], [
            'plan.required' => 'Plan is required',
            'marketplace.required' => 'Marketplace is required',
            'amount.required' => 'Amount name is required',
        ])->validate();

         \Stripe\Stripe::setApiKey('STRIPE_SECRET');

        $data = $request->except('_token');

         $price = $data['amount'] * 100;

         //create stripe product
         $stripeProduct = $this->stripe->products->create([
             'name' => $data['plan'],
         ]);

         //Stripe Plan Creation
         $stripePlanCreation = $this->stripe->plans->create([
             'amount' => $price,
             'currency' => 'usd',
             'interval' => 'month', //  it can be day,week,month or year
             'product' => $stripeProduct->id,
         ]);
         // return $stripePlanCreation;

        $stripe_plan = $stripePlanCreation->id;

        $plan = [

            'planName' => $request['plan'],
            'stripe_plan' => $stripe_plan,
            'marketPlace' => $request['marketplace'],
            'amount' => $request['amount'],

        ];

        $update = plans::updatePlan($plan , $id);

        if($update !='')
        {
            return redirect()->back()->with('success' , 'Plan Has Been Updated Successfully!');
        }


    }

}
