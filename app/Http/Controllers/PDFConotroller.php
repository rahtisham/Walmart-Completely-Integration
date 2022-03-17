<?php

namespace App\Http\Controllers;

use App\Models\PaymentLogs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\PDFsending;
use App\Mail\RegisteredNotification;
use PDF;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use net\authorize\api\contract\v1 as AnetAPI;
use net\authorize\api\controller as AnetController;
use DateTime;

class PDFConotroller extends Controller
{

    public function user()
    {
        return "This is user";
    }

    public function admin()
    {
        return "This is admin";
    }


    public  function dash()
    {
        return view('admin.index');
    }




    public function index()
    {

        return view('myPDF');
//        $pdf = [];
//        $pdf[] = [
//
//            'name' => 'Ahtisham',
//            'email' => 'ahtisham@amzonestep.com'
//
//        ];
//
//        Mail::to('ahtisham@amzonestep.com')->send(new PDFsending($pdf));

//        $pdf = PDF::loadView('myPDF', $data);
//         $pdf->download('itsolutionstuff.pdf');
    }

    public function generatePDF()

    {

        return "This is user";


//            Role::create(['name' => 'Edit']);
//            return "insert";


//        $data = [
//
//            'title' => 'Welcome to ItSolutionStuff.com',
//            'date' => date('m/d/Y')
//
//        ];
//
//        // Mail::to('ahtisham@amzonestep.com')->send(new RegisteredNotification($data));
//
//        return view('email_template.registered_notification');

    }




    function createSubscription($intervalLength=30)
    {
        $startDate = new DateTime('2022-03-15');

        /* Create a merchantAuthenticationType object with authentication details
           retrieved from the constants file */
        $merchantAuthentication = new AnetAPI\MerchantAuthenticationType();
        $merchantAuthentication->setName('7LfUeM3n5r');
        $merchantAuthentication->setTransactionKey('52Z8Tf9QsM7Twq23');

        // Set the transaction's refId
        $refId = 'ref' . time();

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
        $subscription->setAmount(rand(1,99999)/12.0*12);
        $subscription->setTrialAmount("0.00");

        $creditCard = new AnetAPI\CreditCardType();
        $creditCard->setCardNumber("4111111111111111");
        $creditCard->setExpirationDate("2023-12");

        $payment = new AnetAPI\PaymentType();
        $payment->setCreditCard($creditCard);
        $subscription->setPayment($payment);

        $order = new AnetAPI\OrderType();
        $order->setInvoiceNumber("1234354");
        $order->setDescription("Description of the subscription");
        $subscription->setOrder($order);

        $billTo = new AnetAPI\NameAndAddressType();
        $billTo->setFirstName("Ahtisham");
        $billTo->setLastName("Smith");

        $subscription->setBillTo($billTo);

        $request = new AnetAPI\ARBCreateSubscriptionRequest();
        $request->setmerchantAuthentication($merchantAuthentication);
        $request->setRefId($refId);
        $request->setSubscription($subscription);
        $controller = new AnetController\ARBCreateSubscriptionController($request);

        $response = $controller->executeWithApiResponse( \net\authorize\api\constants\ANetEnvironment::SANDBOX);

        if (($response != null) && ($response->getMessages()->getResultCode() == "Ok") )
        {

//            return $response->getSubscriptionId();
//             return "SUCCESS: Subscription ID : " . $response->getSubscriptionId() . "\n";
            $paymentlog = [
                'amount' => $request['amount'],
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





}
