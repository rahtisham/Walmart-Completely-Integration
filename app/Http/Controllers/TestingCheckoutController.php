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

class TestingCheckoutController extends Controller
{


    public function index($subscription)
    {
        if (auth()->user()) {
            return redirect('user/marketplace');
        } else {

            $amount = '';
            $platform = '';
            if ($subscription == 'walmart_option1') {
                $amount = 97.00;
                $platform = "walmart_option1";
                $subscriptionName = "Walmart Account Protection Insurance";
            }
            if ($subscription == 'walmart_option2') {
                $amount = 147.00;
                $platform = "walmart_option2";
                $subscriptionName = "Walmart & Amazon Account Protection Insurance";
            }
            if ($subscription == 'amazon_option1') {
                $amount = 97.00;
                $platform = "amazon_option1";
                $subscriptionName = "Amazon Account Protection Insurance";
            }
            if ($subscription ==  'amazon_option2') {
                $amount = 147.00;
                $platform = "amazon_option2";
                $subscriptionName = "Amazon & Walmart Account Protection Insurance";
            }
            // Get aurgament from Appeal lab website
            return view('checkout.test', ['amount' => $amount, 'platform' => $platform , 'subscriptionName' => $subscriptionName]);
        }
    }


    public function SubscriptionCreateTesting(Request $request)
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

        $createSubscribtion = $this->createSubscription($request);


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

        $payment = PaymentLogs::where('id', $createSubscribtion->id)->update(['user_id' => $user->id]);

        $registredNotification =
            [
                'name' => $user->name,
                'lname' => $user->last_name,
                'email' => $user->email,
                'name_on_card' => $createSubscribtion->name_on_card,
                'amount' => $createSubscribtion->amount,
                'address' => $user->address,
                'contact' => $user->contact
            ];


        Mail::to('info@appeallab.com')->send(new RegisteredNotification($registredNotification));

        return redirect('/login')->with(['success' => 'Your Appeal Lab Account Has Been Created !']);
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
                'subscription' => $response->getSubscriptionId()
            ];

            return $paymentLog = PaymentLogs::createPaymentLog($paymentlog);
        } else {
            echo "ERROR :  Invalid response\n";
            $errorMessages = $response->getMessages()->getMessage();
            echo "Response : " . $errorMessages[0]->getCode() . "  " . $errorMessages[0]->getText() . "\n";
        }

    }


    public function cancelSubscription($subscriptionId = 8018163)
{
    /* Create a merchantAuthenticationType object with authentication details
       retrieved from the constants file */
    $merchantAuthentication = new AnetAPI\MerchantAuthenticationType();
    $merchantAuthentication->setName('7LfUeM3n5r');
    $merchantAuthentication->setTransactionKey('52Z8Tf9QsM7Twq23');

    // Set the transaction's refId
    $refId = 'ref' . time();

    $request = new AnetAPI\ARBCancelSubscriptionRequest();
    $request->setMerchantAuthentication($merchantAuthentication);
    $request->setRefId($refId);
    $request->setSubscriptionId($subscriptionId);

    $controller = new AnetController\ARBCancelSubscriptionController($request);

    $response = $controller->executeWithApiResponse( \net\authorize\api\constants\ANetEnvironment::SANDBOX);

    if (($response != null) && ($response->getMessages()->getResultCode() == "Ok"))
    {
        $successMessages = $response->getMessages()->getMessage();
        echo "SUCCESS : " . $successMessages[0]->getCode() . "  " .$successMessages[0]->getText() . "\n";

     }
    else
    {
        echo "ERROR :  Invalid response\n";
        $errorMessages = $response->getMessages()->getMessage();
        echo "Response : " . $errorMessages[0]->getCode() . "  " .$errorMessages[0]->getText() . "\n";

    }

    return $response;

  }





}
