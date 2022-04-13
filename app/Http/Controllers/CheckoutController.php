<?php

namespace App\Http\Controllers;

use App\Mail\RegisteredNotification;
use Illuminate\Http\Request;
use App\Models\PaymentLogs;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Laravel\Jetstream\Jetstream;
use net\authorize\api\contract\v1 as AnetAPI;
use net\authorize\api\controller as AnetController;
use Carbon\Carbon;
use App\Models\plans;
use Session;
use Stripe;
use Exception;

class CheckoutController extends Controller
{

    // protected $stripe;

    // public function __construct()
    // {
    //     Stripe\Stripe::setApiKey('sk_test_51IlK6HDoULpDRQsxvnaIQ4mSksoxJwlTMfAcxmpOUnWmuODvX8MWQkcKildVidhh9Cb8c4XRWvIvlmA2DYjozWoK00E5m9lbdk');
    // }


    public function login()
    {
        if(auth()->user()){
            return redirect('dashboard/marketplace');
        }else{
            return view('auth.login');
        }

    }


    public function index($subscription)
    {
        if(auth()->user()){
            return redirect('dashboard/marketplace');
        }else{

            $marketPlace = plans::where('marketPlace' , $subscription)->get();
            if($marketPlace[0]['marketPlace'] == $subscription){
                $stripe_plan = $marketPlace[0]['stripe_plan'];
                $subscriptionName = $marketPlace[0]['planName'];
                $amount = $marketPlace[0]['amount'];
                $marketPlace = $marketPlace[0]['marketPlace'];

                // $amount = '';
                // $platform = '';
                // $subscriptionName = '';

                // if($subscription == 'walmart_option1'){
                //     $amount = 97;
                //     $platform = "walmart_option1";
                //     $subscriptionName = "Walmart Account Protection Insurance";
                // }
                // if($subscription == 'walmart_option2'){
                //     $amount = 147;
                //     $platform = "walmart_option2";
                //     $subscriptionName = "Walmart & Amazon Account Protection Insurance";
                // }
                // if($subscription == 'amazon_option1'){
                //     $amount = 97;
                //     $platform = "amazon_option1";
                //     $subscriptionName = "Amazon Account Protection Insurance";
                // }
                // if($subscription ==  'amazon_option2')
                // {
                //     $amount = 147;
                //     $platform = "amazon_option2";
                //     $subscriptionName = "Amazon & Walmart Account Protection Insurance";
                // }
                // Get aurgament from Appeal lab website

            return view('checkout.checkout' , ['amount' => $amount , 'marketPlace' => $marketPlace , 'subscriptionName' => $subscriptionName , 'stripePlan' => $stripe_plan]);
            }
            else
            {
                return "Url is not match";
            }
        }

    }


//    public function create(Request $request)
//    {
//
//        $validator = Validator::make($request->all(), [
//            'fname' => 'required', 'alpha', 'max:255',
//            'email' => 'required|email|max:255|regex:/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix|unique:users',
//            'lname' => ['required', 'alpha', 'max:255'],
//            'address' => ['required', 'string', 'max:255'],
//            'city' => ['required', 'alpha', 'max:255'],
//            'postal' => ['required', 'max:7'],
//            'country' => ['required', 'alpha', 'max:255'],
//            'state' => ['required', 'alpha', 'max:255'],
//            'contact' => ['required', 'string', 'max:255'],
//            'owner' => ['required', 'max:255'],
//            'cardNumber' => ['required', 'min:16' , 'max:16'],
//            'expiration-year' => ['required', 'string', 'max:255'],
//            'expiration-month' => ['required', 'string', 'max:255'],
//            'cvv' => ['required', 'max:3', 'min:3'],
//            // 'amount' => ['required', 'max:255'],
//            'password' => 'required',
//            'password_confirmation' => 'required',
//            'agreement' => 'required',
//            'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature() ? ['accepted', 'required'] : '',
//        ],[
//            'email.required' => 'Email is required',
//            'fname.required' => 'First name is required',
//            'fname.alpha' => 'First name must only contain letters',
//            'lname.required' => 'First name is required',
//            'lname.alpha' => 'Last name must only contain letters',
//            'address.required' => 'Address is required',
//            'city.required' => 'City is required',
//            'state.required' => 'State is required',
//            'contact.required' => 'Phone number is required',
//            'postal.required' => 'Postal code is required',
//            // 'amount.required' => 'Amount is required',
//            'country.required' => 'Country is required',
//            'cvv.required' => 'CVV is required',
//            'cardNumber.required' => 'Card number is required',
//            'owner.required' => 'Card holder name is required',
//            'password.required' => 'Password is required',
//            'agreement.required' => 'Agreement is required',
//            'password_confirmation.required' => 'Confirm password is required',
//        ])->validate();
//
//        $paymentLog = "";
//        $intervalLength = 30;
//
//        $startDate = new Carbon();
//
//        /* Create a merchantAuthenticationType object with authentication details
//           retrieved from the constants file */
//        $merchantAuthentication = new AnetAPI\MerchantAuthenticationType();
//        $merchantAuthentication->setName(env('MERCHANT_LOGIN_ID'));
//        $merchantAuthentication->setTransactionKey(env('MERCHANT_TRANSACTION_KEY'));
//
//        // Set the transaction's refId
//        $refId = 'ref' . time();
//        $cardNumber = preg_replace('/\s+/', '', $request['cardNumber']);
//
//        // Subscription Type Info
//        $subscription = new AnetAPI\ARBSubscriptionType();
//        $subscription->setName("Sample Subscription");
//
//        $interval = new AnetAPI\PaymentScheduleType\IntervalAType();
//        $interval->setLength($intervalLength);
//        $interval->setUnit("days");
//
//        $paymentSchedule = new AnetAPI\PaymentScheduleType();
//        $paymentSchedule->setInterval($interval);
//        $paymentSchedule->setStartDate($startDate);
//        $paymentSchedule->setTotalOccurrences("12");
//        $paymentSchedule->setTrialOccurrences("1");
//
//        $subscription->setPaymentSchedule($paymentSchedule);
//        $subscription->setAmount($request['amount']);
//        $subscription->setTrialAmount("0.00");
//
//        $creditCard = new AnetAPI\CreditCardType();
//        $creditCard->setCardNumber($cardNumber);
//        $creditCard->setExpirationDate($request['expiration-year'] . "-" . $request['expiration-month']);
//        $creditCard->setCardCode($request['cvv']);
//
//        $payment = new AnetAPI\PaymentType();
//        $payment->setCreditCard($creditCard);
//        $subscription->setPayment($payment);
//
//        $order = new AnetAPI\OrderType();
//        $order->setInvoiceNumber("123435456");
//        $order->setDescription("Description of the subscription");
//        $subscription->setOrder($order);
//
//        $billTo = new AnetAPI\NameAndAddressType();
//        $billTo->setFirstName($request['fname']);
//        $billTo->setLastName($request['lname']);
//
//        $subscription->setBillTo($billTo);
//
//        $requestSubscribtion = new AnetAPI\ARBCreateSubscriptionRequest();
//        $requestSubscribtion->setmerchantAuthentication($merchantAuthentication);
//        $requestSubscribtion->setRefId($refId);
//        $requestSubscribtion->setSubscription($subscription);
//        $controller = new AnetController\ARBCreateSubscriptionController($requestSubscribtion);
//
//        $response = $controller->executeWithApiResponse( \net\authorize\api\constants\ANetEnvironment::PRODUCTION);
//
//        if (($response != null) && ($response->getMessages()->getResultCode() == "Ok") )
//        {
//
//            $paymentlog = [
//                'amount' => $request->amount,
//                'subscription' => $response->getSubscriptionId()
//            ];
//
//            $paymentLog = PaymentLogs::createPaymentLog($paymentlog);
//        }
//        else
//        {
//            echo "ERROR :  Invalid response\n";
//            $errorMessages = $response->getMessages()->getMessage();
//            echo "Response : " . $errorMessages[0]->getCode() . "  " .$errorMessages[0]->getText() . "\n";
//        }
//
//
//        $userData = [
//            'name' => $request->fname,
//            'email' => $request->email,
//            'last_name' => $request->lname,
//            'address' => $request->address,
//            'city' => $request->city,
//            'roles' => "1",
//            'postal' => $request->postal,
//            'country' => $request->country,
//            'state' => $request->state,
//            'contact' => $request->contact,
//            'password' => Hash::make($request->password),
//        ];
//
//        $user = User::store($userData);
//
//        $payment = PaymentLogs::where('id' , $paymentLog->id)->update(['user_id' => $user->id]);
//
//        $registredNotification =
//            [
//                'name' => $user->name,
//                'lname' => $user->last_name,
//                'email' => $user->email,
//                'name_on_card' => $paymentLog->name_on_card,
//                'amount' => $paymentLog->amount,
//                'address' => $user->address,
//                'contact' => $user->contact
//            ];
//
//        Mail::to('info@appeallab.com')->send(new RegisteredNotification($registredNotification));
//
//        return redirect('/login')->with(['success' => 'Your Appeal Lab Account Has Been Created !']);
//
//
//
//
//    }

//     public function SubscriptionCreate(Request $request)
//     {

//         $validator = Validator::make($request->all(), [
//             'fname' => 'required', 'alpha', 'max:255',
//             'email' => 'required|email|max:255|regex:/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix|unique:users',
//             'lname' => ['required', 'alpha', 'max:255'],
//             'address' => ['required', 'string', 'max:255'],
//             'city' => ['required', 'alpha', 'max:255'],
//             'postal' => ['required','string',  'max:7'],
//             'country' => ['required' , 'max:255'],
//             'state' => ['required', 'alpha', 'max:255'],
//             'contact' => ['required', 'string', 'max:255'],
//             'owner' => ['required' , 'max:255'],
//             'cardNumber' => ['required', 'min:16' , 'max:16'],
//             'expiration-year' => ['required', 'string', 'max:255'],
//             'expiration-month' => ['required', 'string', 'max:255'],
//             'cvv' => ['required', 'max:3', 'min:3'],
//             'password' => 'required',
//             'agreement' => 'required',
//             'password_confirmation' => 'required',
//             'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature() ? ['accepted', 'required'] : '',
//         ],[
//             'email.required' => 'Email is required',
//             'fname.required' => 'First name is required',
//             'fname.alpha' => 'First name must only contain letters',
//             'lname.required' => 'First name is required',
//             'lname.alpha' => 'Last name must only contain letters',
//             'address.required' => 'Address is required',
//             'city.required' => 'City is required',
//             'state.required' => 'Province is required',
//             'contact.required' => 'Phone number is required',
//             'postal.required' => 'Postal code is required',
//             'country.required' => 'Country is required',
//             'cvv.required' => 'CVV is required',
//             'cardNumber.required' => 'Card number is required',
//             'owner.required' => 'Card holder name is required',
//             'password.required' => 'Password is required',
//             'agreement.required' => 'Agreement is required',
//             'password_confirmation.required' => 'Confirm password is required',
//         ])->validate();


//         $paymentLog = "";

//         $merchantAuthentication = new AnetAPI\MerchantAuthenticationType();
//         $merchantAuthentication->setName('4r764PscJQTG');
//         $merchantAuthentication->setTransactionKey('4KJ3834hRLqvr6vb');

//         // Set the transaction's refId
//         $refId = 'ref' . time();
//         $cardNumber = preg_replace('/\s+/', '', $request['cardNumber']);

//         // Create the payment data for a credit card
//         $creditCard = new AnetAPI\CreditCardType();
//         $creditCard->setCardNumber($cardNumber);
//         $creditCard->setExpirationDate($request['expiration-year'] . "-" . $request['expiration-month']);
//         $creditCard->setCardCode($request['cvv']);

//         // Add the payment data to a paymentType object
//         $paymentOne = new AnetAPI\PaymentType();
//         $paymentOne->setCreditCard($creditCard);

//         // Set the customer's Bill To address
//         $customerAddress = new AnetAPI\CustomerAddressType();
// //        $customerAddress->setFirstName("Anas");
// //        $customerAddress->setLastName("Reaz");
// //        $customerAddress->setCompany("OutOrigin");
// //        $customerAddress->setAddress("1616 19 St NW");
// //        $customerAddress->setCity("Edmonton");
// //        $customerAddress->setState("Alberta");
// //        $customerAddress->setZip("T6T 2C1");
// //        $customerAddress->setCountry("Canada");

//         $customerAddress = new AnetAPI\CustomerAddressType();
//         $customerAddress->setFirstName($request['fname']);
//         $customerAddress->setLastName($request['lname']);
//         $customerAddress->setCompany("Appeal Lab");
//         $customerAddress->setAddress($request['address']);
//         $customerAddress->setCity($request['city']);
//         $customerAddress->setState($request['state']);
//         $customerAddress->setZip($request['postal']);
//         $customerAddress->setCountry($request['country']);

//         // Create a TransactionRequestType object and add the previous objects to it
//         $transactionRequestType = new AnetAPI\TransactionRequestType();
//         $transactionRequestType->setTransactionType("authCaptureTransaction");
//         $transactionRequestType->setAmount($request['amount']);
//         $transactionRequestType->setPayment($paymentOne);
//         $transactionRequestType->setBillTo($customerAddress);


//         // Assemble the complete transaction request
//         $requests = new AnetAPI\CreateTransactionRequest();
//         $requests->setMerchantAuthentication($merchantAuthentication);
//         $requests->setRefId($refId);
//         $requests->setTransactionRequest($transactionRequestType);

//         // Create the controller and get the response
//         $controller = new AnetController\CreateTransactionController($requests);
//         $response = $controller->executeWithApiResponse(\net\authorize\api\constants\ANetEnvironment::PRODUCTION);

//         if ($response != null) {
//             // Check to see if the API request was successfully received and acted upon
//             if ($response->getMessages()->getResultCode() == "Ok") {
//                 // Since the API request was successful, look for a transaction response
//                 // and parse it to display the results of authorizing the card
//                 $tresponse = $response->getTransactionResponse();

//                 if ($tresponse != null && $tresponse->getMessages() != null) {
//                     echo " Successfully created transaction with Transaction ID: " . $tresponse->getTransId() . "\n";
//                     echo " Transaction Response Code: " . $tresponse->getResponseCode() . "\n";
//                     echo " Message Code: " . $tresponse->getMessages()[0]->getCode() . "\n";
//                     echo " Auth Code: " . $tresponse->getAuthCode() . "\n";
//                     echo " Description: " . $tresponse->getMessages()[0]->getDescription() . "\n";

//                     $paymentlog = [
//                         'amount' => $request['amount'],
// //                        'user_id' => $user_id,
//                         'response_code' => $tresponse->getResponseCode(),
//                         'transaction_id' => $tresponse->getTransId(),
//                         'auth_id' => $tresponse->getAuthCode(),
//                         'message_code' => $tresponse->getMessages()[0]->getCode(),
//                         'name_on_card' => trim($request['owner']),
//                         'quantity' => 1
//                     ];

//                     $paymentLog = PaymentLogs::createPaymentLog($paymentlog);


//                 } else {

//                     if ($tresponse->getErrors() != null) {

//                         $cardError = " Error Code  : " . $tresponse->getErrors()[0]->getErrorCode() . "\n";
//                         $cardError.= " Error Message : " . $tresponse->getErrors()[0]->getErrorText() . "\n";

//                         return redirect()->back()->with(['cardError' => $cardError]);

//                     }
//                 }
//                 // Or, print errors if the API request wasn't successful
//             } else {

//                 $tresponse = $response->getTransactionResponse();

//                 if ($tresponse != null && $tresponse->getErrors() != null) {
//                     $cardError = " Error Code  : " . $tresponse->getErrors()[0]->getErrorCode() . "\n";
//                     $cardError.= " Error Message : " . $tresponse->getErrors()[0]->getErrorText() . "\n";

//                     return redirect()->back()->with(['cardError' => $cardError]);



//                 } else {

//                     $cardError = " Error Code  : " . $response->getMessages()->getMessage()[0]->getCode() . "\n";
//                     $cardError.= " Error Message : " . $response->getMessages()->getMessage()[0]->getText() . "\n";

//                     return redirect()->back()->with(['cardError' => $cardError]);

//                 }
//             }
//         } else {
//             echo  "No response returned \n";
//         }


//         $userData = [
//             'name' => $request['fname'],
//             'email' => $request['email'],
//             'last_name' => $request['lname'],
//             'address' => $request['address'],
//             'city' => $request['city'],
//             'postal' => $request['postal'],
//             'country' => $request['country'],
//             'state' => $request['state'],
//             'contact' => $request['contact'],
//             'password' => Hash::make($request['password']),
//         ];

//         $user = User::store($userData);

//         $payment = PaymentLogs::where('id' , $paymentLog->id)->update(['user_id' => $user->id]);

//         $registredNotification =
//             [
//                 'name' => $user->name,
//                 'lname' => $user->last_name,
//                 'email' => $user->email,
//                 'name_on_card' => $paymentLog->name_on_card,
//                 'amount' => $paymentLog->amount,
//                 'address' => $user->address,
//                 'contact' => $user->contact
//             ];

//         Mail::to('info@appeallab.com')->send(new RegisteredNotification($registredNotification));

//         return redirect('/login')->with(['success' => 'Your Appeal Lab Account Has Been Created !']);


//     }


    public function SubscriptionCreate(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'fname' => 'required', 'alpha', 'max:255',
            'email' => 'required|email|max:255|regex:/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix|unique:users',
            'lname' => ['required', 'alpha', 'max:255'],
            'address' => ['required', 'string', 'max:255'],
            'city' => ['required', 'max:255'],
            'postal' => ['required', 'max:7'],
            'country' => ['required', 'max:255'],
            'state' => ['required', 'alpha', 'max:255'],
            'contact' => ['required', 'string', 'max:255'],
            'owner' => ['required', 'max:255'],
            'cardNumber' => ['required', 'min:16', 'max:16'],
            'expiration-year' => ['required', 'string', 'max:255'],
            'expiration-month' => ['required', 'string', 'max:255'],
            'cvv' => ['required', 'max:3', 'min:3'],
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
            'country.required' => 'Country is required',
            'cvv.required' => 'CVV is required',
            'cardNumber.required' => 'Card number is required',
            'owner.required' => 'Card holder name is required',
            'password.required' => 'Password is required',
            'agreement.required' => 'Agreement is required',
            'password_confirmation.required' => 'Confirm password is required',
        ])->validate();

        // $paymentLogId = $this->createSubscription($request);

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

                Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

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
                    'subscription' => 'Subscription Create',
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


    public function createSubscription($data){

        $paymentLog = "";
        $intervalLength = 30;
        $invoice = strtotime("now");


        $startDate = new Carbon();
        // For date Time

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
        $order->setInvoiceNumber("3243243434". $invoice);
        $order->setDescription("Description of the subscription");
        $subscription->setOrder($order);

        $billTo = new AnetAPI\NameAndAddressType();
        $billTo->setFirstName($data['fname']);
        $billTo->setLastName($data['lname']);

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




}
