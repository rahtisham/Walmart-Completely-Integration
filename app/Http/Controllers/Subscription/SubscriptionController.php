<?php

namespace App\Http\Controllers\Subscription;

use Carbon\Carbon;
use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;
use App\Models\PaymentLogs;
use App\Models\User;
use App\Models\plans;
use App\Mail\RegisteredNotification;
use Illuminate\Support\Facades\Validator;
use net\authorize\api\contract\v1 as AnetAPI;
use net\authorize\api\controller as AnetController;


class SubscriptionController extends Controller
{
    public function index()
    {
        $auth_session_id = auth()->user()->id;
        $paymentLog = PaymentLogs::where('user_id' , $auth_session_id)->get();
        return view('subscription.index' , ['paymentLog' => $paymentLog]);
    }

    public function cancelSubscription($subscriptionId)
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

            $active_user_id = auth()->user()->id;
            $user = User::where('id' , $active_user_id)->update(['roles' => '4']);
            $paymentLog = Payment::where('user_id' , $active_user_id)->update(['status' => 'cancel']);
            // return redirect('logout')->with(['success' => 'Subscription Has Been Cancel !']);
        }
        else
        {
            echo "ERROR :  Invalid response\n";
            $errorMessages = $response->getMessages()->getMessage();
            echo "Response : " . $errorMessages[0]->getCode() . "  " .$errorMessages[0]->getText() . "\n";

        }

        return $response;

      }


      public function plan()
      {
          $plans = plans::all();
          return view('subscription.plan' , ['plans' => $plans]);
      }

      public function subscription($subscription)
      {
        if (!empty($subscription)) {

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
            return view('subscription.create-subscription', ['amount' => $amount, 'platform' => $platform , 'subscriptionName' => $subscriptionName]);
        }

      }

      public function subscriptionAdded(Request $request)
      {

        // return $request->cardNumber;
        $validator = Validator::make($request->all(), [
            'owner' => ['required', 'max:255'],
            'cardNumber' => ['required', 'min:16', 'max:16'],
            'expiration-year' => ['required', 'string', 'max:255'],
            'expiration-month' => ['required', 'string', 'max:255'],
            'cvv' => ['required', 'max:3', 'min:3'],
        ], [
            'cvv.required' => 'CVV is required',
            'cardNumber.required' => 'Card number is required',
            'owner.required' => 'Card holder name is required',
        ])->validate();

        $createSubscribtion = $this->createSubscription($request);

        $session_id = auth()->user()->id;
        $user_id = User::where('id' , $session_id)->update(['roles' => '1']);

        $payment = PaymentLogs::where('id', $createSubscribtion->id)->update(['user_id' => $session_id]);
        // Mail::to('info@appeallab.com')->send(new RegisteredNotification($registredNotification));

        return redirect('/')->with(['success' => 'Your Appeal Lab Account Has Been Created !']);
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
