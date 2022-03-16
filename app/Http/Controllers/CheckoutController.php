<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\PaymentLogs;
use App\Models\User;
use App\Mail\RegisteredNotification;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Laravel\Jetstream\Jetstream;
use net\authorize\api\contract\v1 as AnetAPI;
use net\authorize\api\controller as AnetController;


class CheckoutController extends Controller
{

   public function home()
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

            $amount = '';
            $platform = '';
            if($subscription == 'walmart_option1'){
                $amount = 97;
                $platform = "walmart_option1";
            }
            if($subscription == 'walmart_option2'){
                $amount = 147;
                $platform = "walmart_option2";
            }
            if($subscription == 'amazon_option1'){
                $amount = 97;
                $platform = "amazon_option1";
            }
            if($subscription ==  'amazon_option2')
            {
                $amount = 147;
                $platform = "amazon_option2";
            }
            // Get aurgament from Appeal lab website
            return view('checkout.checkout' , ['amount' => $amount , 'platform' => $platform]);

        }

    }



    public function create(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'fname' => 'required', 'alpha', 'max:255',
            'email' => 'required|email|max:255|regex:/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix|unique:users',
            'lname' => ['required', 'alpha', 'max:255'],
            'address' => ['required', 'string', 'max:255'],
            'city' => ['required', 'alpha', 'max:255'],
            'postal' => ['required', 'max:7'],
            'country' => ['required', 'alpha', 'max:255'],
            'state' => ['required', 'alpha', 'max:255'],
            'contact' => ['required', 'string', 'max:255'],
            'owner' => ['required', 'max:255'],
            'cardNumber' => ['required', 'min:16' , 'max:16'],
            'expiration-year' => ['required', 'string', 'max:255'],
            'expiration-month' => ['required', 'string', 'max:255'],
            'cvv' => ['required', 'max:3', 'min:3'],
            // 'amount' => ['required', 'max:255'],
            'password' => 'required',
            'password_confirmation' => 'required',
            'agreement' => 'required',
            'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature() ? ['accepted', 'required'] : '',
        ],[
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

        $paymentLog = "";
        $intervalLength = 30;

        $startDate = new Carbon();

        /* Create a merchantAuthenticationType object with authentication details
           retrieved from the constants file */
        $merchantAuthentication = new AnetAPI\MerchantAuthenticationType();
        $merchantAuthentication->setName(env('MERCHANT_LOGIN_ID'));
        $merchantAuthentication->setTransactionKey(env('MERCHANT_TRANSACTION_KEY'));

        // Set the transaction's refId
        $refId = 'ref' . time();
        $cardNumber = preg_replace('/\s+/', '', $request['cardNumber']);

        // Subscription Type Info
        $subscription = new AnetAPI\ARBSubscriptionType();
        $subscription->setName("Sample Subscription");

        $interval = new AnetAPI\PaymentScheduleType\IntervalAType();
        $interval->setLength($intervalLength);
        $interval->setUnit("days");

        $paymentSchedule = new AnetAPI\PaymentScheduleType();
        $paymentSchedule->setInterval($interval);
        $paymentSchedule->setStartDate($startDate);
        $paymentSchedule->setTotalOccurrences("12");
        $paymentSchedule->setTrialOccurrences("1");

        $subscription->setPaymentSchedule($paymentSchedule);
        $subscription->setAmount($request['amount']);
        $subscription->setTrialAmount("0.00");

        $creditCard = new AnetAPI\CreditCardType();
        $creditCard->setCardNumber($cardNumber);
        $creditCard->setExpirationDate($request['expiration-year'] . "-" . $request['expiration-month']);
        $creditCard->setCardCode($request['cvv']);

        $payment = new AnetAPI\PaymentType();
        $payment->setCreditCard($creditCard);
        $subscription->setPayment($payment);

        $order = new AnetAPI\OrderType();
        $order->setInvoiceNumber("123435456");
        $order->setDescription("Description of the subscription");
        $subscription->setOrder($order);

        $billTo = new AnetAPI\NameAndAddressType();
        $billTo->setFirstName($request['fname']);
        $billTo->setLastName($request['lname']);

        $subscription->setBillTo($billTo);

        $requestSubscribtion = new AnetAPI\ARBCreateSubscriptionRequest();
        $requestSubscribtion->setmerchantAuthentication($merchantAuthentication);
        $requestSubscribtion->setRefId($refId);
        $requestSubscribtion->setSubscription($subscription);
        $controller = new AnetController\ARBCreateSubscriptionController($requestSubscribtion);

        $response = $controller->executeWithApiResponse( \net\authorize\api\constants\ANetEnvironment::SANDBOX);

        if (($response != null) && ($response->getMessages()->getResultCode() == "Ok") )
        {

            $paymentlog = [
                'amount' => $request->amount,
                'subscription' => $response->getSubscriptionId()
            ];

            $paymentLog = PaymentLogs::createPaymentLog($paymentlog);
        }
        else
        {
            echo "ERROR :  Invalid response\n";
            $errorMessages = $response->getMessages()->getMessage();
            echo "Response : " . $errorMessages[0]->getCode() . "  " .$errorMessages[0]->getText() . "\n";
        }


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

        $payment = PaymentLogs::where('id' , $paymentLog->id)->update(['user_id' => $user->id]);

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

        Mail::to('info@appeallab.com')->send(new RegisteredNotification($registredNotification));

        return redirect('/login')->with(['success' => 'Your Appeal Lab Account Has Been Created !']);



    }






}
