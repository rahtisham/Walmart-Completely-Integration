<?php

namespace App\Http\Controllers;

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
            'owner' => ['required', 'alpha', 'max:255'],
            'cardNumber' => ['required', 'min:16' , 'max:16'],
            'expiration-year' => ['required', 'string', 'max:255'],
            'expiration-month' => ['required', 'string', 'max:255'],
            'cvv' => ['required', 'max:3', 'min:3'],
            'amount' => ['required', 'max:255'],
            'password' => 'required|string|min:6|confirmed|regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{6,}$/',
            'password_confirmation' => 'required',
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
            'amount.required' => 'Amount is required',
            'country.required' => 'Country is required',
            'cvv.required' => 'CVV is required',
            'cardNumber.required' => 'Card number is required',
            'owner.required' => 'Card holder name is required',
            'password.required' => 'Password is required',
            'password_confirmation.required' => 'Confirm password is required',
        ])->validate();


        $paymentLog = "";

        $merchantAuthentication = new AnetAPI\MerchantAuthenticationType();
//        $merchantAuthentication->setName(env('MERCHANT_LOGIN_ID'));
//        $merchantAuthentication->setTransactionKey(env('MERCHANT_TRANSACTION_KEY'));
        $merchantAuthentication->setName('7LfUeM3n5r');
        $merchantAuthentication->setTransactionKey('52Z8Tf9QsM7Twq23');

        // Set the transaction's refId
        $refId = 'ref' . time();
        $cardNumber = preg_replace('/\s+/', '', $request['cardNumber']);

        // Create the payment data for a credit card
        $creditCard = new AnetAPI\CreditCardType();
        $creditCard->setCardNumber($cardNumber);
        $creditCard->setExpirationDate($request['expiration-year'] . "-" . $request['expiration-month']);
        $creditCard->setCardCode($request['cvv']);

        // Add the payment data to a paymentType object
        $paymentOne = new AnetAPI\PaymentType();
        $paymentOne->setCreditCard($creditCard);

        // Create a TransactionRequestType object and add the previous objects to it
        $transactionRequestType = new AnetAPI\TransactionRequestType();
        $transactionRequestType->setTransactionType("authCaptureTransaction");
        $transactionRequestType->setAmount($request['amount']);
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

                    $paymentlog = [
                        'amount' => $request['amount'],
//                        'user_id' => $user_id,
                        'response_code' => $tresponse->getResponseCode(),
                        'transaction_id' => $tresponse->getTransId(),
                        'auth_id' => $tresponse->getAuthCode(),
                        'message_code' => $tresponse->getMessages()[0]->getCode(),
                        'name_on_card' => trim($request['owner']),
                        'quantity' => 1
                    ];

                    $paymentLog = PaymentLogs::createPaymentLog($paymentlog);


                } else {

                    if ($tresponse->getErrors() != null) {

                        $cardError = " Error Code  : " . $tresponse->getErrors()[0]->getErrorCode() . "\n";
                        $cardError.= " Error Message : " . $tresponse->getErrors()[0]->getErrorText() . "\n";

                        return redirect()->back()->with(['cardError' => $cardError]);

                    }
                }
                // Or, print errors if the API request wasn't successful
            } else {

                $tresponse = $response->getTransactionResponse();

                if ($tresponse != null && $tresponse->getErrors() != null) {
                    $cardError = " Error Code  : " . $tresponse->getErrors()[0]->getErrorCode() . "\n";
                    $cardError.= " Error Message : " . $tresponse->getErrors()[0]->getErrorText() . "\n";

                    return redirect()->back()->with(['cardError' => $cardError]);



                } else {

                    $cardError = " Error Code  : " . $response->getMessages()->getMessage()[0]->getCode() . "\n";
                    $cardError.= " Error Message : " . $response->getMessages()->getMessage()[0]->getText() . "\n";

                    return redirect()->back()->with(['cardError' => $cardError]);

                }
            }
        } else {
            echo  "No response returned \n";
        }


        $userData = [
            'name' => $request['fname'],
            'email' => $request['email'],
            'last_name' => $request['lname'],
            'address' => $request['address'],
            'city' => $request['city'],
            'postal' => $request['postal'],
            'country' => $request['country'],
            'state' => $request['state'],
            'contact' => $request['contact'],
            'password' => Hash::make($request['password']),
        ];

        $user = User::store($userData);

         $payment = PaymentLogs::where('id' , $paymentLog->id)->update(['user_id' => $user->id]);

         $registredNotification =
                         [
                             'name' => $user->name,
                             'lname' => $user->last_name,
                             'email' => $user->email,
                             'name_on_card' => $payment->name_on_Card,
                             'amount' => $payment->amount,
                             'address' => $user->address,
                             'contact' => $user->contact
                         ];

        Mail::to('ahtisham@amzonestep.com')->send(new RegisteredNotification($registredNotification));

        return redirect('/login')->with(['success' => 'Your Appeal Lab Account Has Been Created !']);


    }
}
