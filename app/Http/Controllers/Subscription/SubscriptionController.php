<?php

namespace App\Http\Controllers\Subscription;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;
use App\Models\PaymentLogs;
use App\Models\User;
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



      public function createSubscription()
      {
          return view('subscription.create-subscription');
      }


}
