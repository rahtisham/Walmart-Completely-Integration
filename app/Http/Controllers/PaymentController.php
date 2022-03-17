<?php

namespace App\Http\Controllers;

use App\Mail\RegisteredNotification;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Laravel\Jetstream\Jetstream;
use net\authorize\api\contract\v1 as AnetAPI;
use net\authorize\api\controller as AnetController;
use Illuminate\Http\Request;
use App\Models\PaymentLogs;
use DateTime;
use Carbon\Carbon;

class PaymentController extends Controller
{
    public function pay()
    {
        return view('authorize.index');
    }


    public function createSubscriptions(Request $request)
    {

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
        $billTo->setFirstName("Athtisham");
        $billTo->setLastName("khan");

        $subscription->setBillTo($billTo);

        $requestSubscribtion = new AnetAPI\ARBCreateSubscriptionRequest();
        $requestSubscribtion->setmerchantAuthentication($merchantAuthentication);
        $requestSubscribtion->setRefId($refId);
        $requestSubscribtion->setSubscription($subscription);
        $controller = new AnetController\ARBCreateSubscriptionController($requestSubscribtion);

        $response = $controller->executeWithApiResponse( \net\authorize\api\constants\ANetEnvironment::SANDBOX);

        if (($response != null) && ($response->getMessages()->getResultCode() == "Ok") )
        {
           return $response->getSubscriptionId();

            $paymentlog = [
                'amount' => $request['amount'],
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

        return $response;



    }




    public function handleonlinepay(Request $request)
    {

        $input = $request->input();

        /* Create a merchantAuthenticationType object with authentication details
          retrieved from the constants file */
//        $merchantAuthentication = new AnetAPI\MerchantAuthenticationType();
//        $merchantAuthentication->setName(env('MERCHANT_LOGIN_ID'));
//        $merchantAuthentication->setTransactionKey(env('MERCHANT_TRANSACTION_KEY'));

        $merchantAuthentication = new AnetAPI\MerchantAuthenticationType();
        $merchantAuthentication->setName('7LfUeM3n5r');
        $merchantAuthentication->setTransactionKey('52Z8Tf9QsM7Twq23');

        // Set the transaction's refId
        $refId = 'ref' . time();
        $cardNumber = preg_replace('/\s+/', '', $input['cardNumber']);

        // Create the payment data for a credit card
        $creditCard = new AnetAPI\CreditCardType();
        $creditCard->setCardNumber($cardNumber);
        $creditCard->setExpirationDate($input['expiration-year'] . "-" . $input['expiration-month']);
        $creditCard->setCardCode($input['cvv']);

        // Add the payment data to a paymentType object
        $paymentOne = new AnetAPI\PaymentType();
        $paymentOne->setCreditCard($creditCard);

        // Create a TransactionRequestType object and add the previous objects to it
        $transactionRequestType = new AnetAPI\TransactionRequestType();
        $transactionRequestType->setTransactionType("authCaptureTransaction");
        $transactionRequestType->setAmount($input['amount']);
        $transactionRequestType->setPayment($paymentOne);

        // Assemble the complete transaction request
        $requests = new AnetAPI\CreateTransactionRequest();
        $requests->setMerchantAuthentication($merchantAuthentication);
        $requests->setRefId($refId);
        $requests->setTransactionRequest($transactionRequestType);

        // Create the controller and get the response
        $controller = new AnetController\CreateTransactionController($requests);
        $response = $controller->executeWithApiResponse(\net\authorize\api\constants\ANetEnvironment::SANDBOX);

        if ($response != null) {
            // Check to see if the API request was successfully received and acted upon
            if ($response->getMessages()->getResultCode() == "Ok") {
                // Since the API request was successful, look for a transaction response
                // and parse it to display the results of authorizing the card
                $tresponse = $response->getTransactionResponse();

                if ($tresponse != null && $tresponse->getMessages() != null) {
                    echo " Successfully created transaction with Transaction ID: " . $tresponse->getTransId() . "\n";
                    echo " Transaction Response Code: " . $tresponse->getResponseCode() . "\n";
                    echo " Message Code: " . $tresponse->getMessages()[0]->getCode() . "\n";
                    echo " Auth Code: " . $tresponse->getAuthCode() . "\n";
                    echo " Description: " . $tresponse->getMessages()[0]->getDescription() . "\n";

                    \App\Models\PaymentLogs::create([
                        'amount' => $input['amount'],
                        'response_code' => $tresponse->getResponseCode(),
                        'transaction_id' => $tresponse->getTransId(),
                        'auth_id' => $tresponse->getAuthCode(),
                        'message_code' => $tresponse->getMessages()[0]->getCode(),
                        'name_on_card' => trim($input['owner']),
                        'quantity' => 1
                    ]);


                } else {
                    echo "Transaction Failed \n";
                    if ($tresponse->getErrors() != null) {
                        echo " Error Code  : " . $tresponse->getErrors()[0]->getErrorCode() . "\n";
                        echo " Error Message : " . $tresponse->getErrors()[0]->getErrorText() . "\n";
                    }
                }
                // Or, print errors if the API request wasn't successful
            } else {
                echo "Transaction Failed \n";
                $tresponse = $response->getTransactionResponse();

                if ($tresponse != null && $tresponse->getErrors() != null) {
                    echo " Error Code  : " . $tresponse->getErrors()[0]->getErrorCode() . "\n";
                    echo " Error Message : " . $tresponse->getErrors()[0]->getErrorText() . "\n";

                } else {
                    echo " Error Code  : " . $response->getMessages()->getMessage()[0]->getCode() . "\n";
                    echo " Error Message : " . $response->getMessages()->getMessage()[0]->getText() . "\n";
                }
            }
        } else {
            echo  "No response returned \n";
        }

//        return $response;
    }



}
