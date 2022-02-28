<?php

namespace App\Actions\Fortify;

use App\Models\User;
use App\Models\PaymentLogs;
use http\Env\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Laravel\Jetstream\Jetstream;
use net\authorize\api\contract\v1 as AnetAPI;
use net\authorize\api\controller as AnetController;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array  $input
     * @return \App\Models\User
     */
    public function create(array $input)
    {
        $validator = Validator::make($input, [
            'fname' => ['required', 'alpha', 'max:255'],
            'email' => 'required|email|max:255|regex:/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix|unique:users',
            'lname' => ['required', 'alpha', 'max:255'],
            'address' => ['required', 'string', 'max:255'],
            'city' => ['required', 'alpha', 'max:255'],
            'postal' => ['required', 'max:7'],
            'country' => ['required', 'alpha', 'max:255'],
            'state' => ['required', 'alpha', 'max:255'],
            'contact' => ['required', 'string', 'max:255'],
            'owner' => ['required', 'alpha', 'max:255'],
            'cardNumber' => ['required', 'max:16'],
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


        $input['cardNumber'];
        $input['expiration-year'];
        $input['expiration-month'];
        $input['cvv'];
        $input['amount'];

         $user = User::create([
            'name' => $input['fname'],
            'email' => $input['email'],
            'last_name' => $input['lname'],
            'address' => $input['address'],
            'city' => $input['city'],
            'postal' => $input['postal'],
            'country' => $input['country'],
            'state' => $input['state'],
            'contact' => $input['contact'],
            'password' => Hash::make($input['password']),
        ]);

         $user_id = $user->id;

        $merchantAuthentication = new AnetAPI\MerchantAuthenticationType();
        $merchantAuthentication->setName(env('MERCHANT_LOGIN_ID'));
        $merchantAuthentication->setTransactionKey(env('MERCHANT_TRANSACTION_KEY'));

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
                        'user_id' => $user_id,
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



        return $user;



    }
}
