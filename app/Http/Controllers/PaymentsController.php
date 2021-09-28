<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PaymentsController extends Controller
{
  
    public function makpayment(Request $request)
    {
      $sendorId = $_GET['sendorId'];
      $total_amount = $_GET['total'];
      $receiver = $_GET['receiver'];
      $user = User::where('id',$sendorId)->first();
  	  try{
  		$payment = MyFatoorah::createApiInvoiceIso();
  		$payment->setCustomerName($user->name);
        $payment->setCustomerMobile("965454578");
        $payment->setCustomerEmail($user->email);
        $payment->setCustomerReference("sdhgasdsfva");
  		$payment->setDisplayCurrencyIsoAlpha( "KWD" );
  		$payment->setCountryCodeId( 1 );
  		$payment->setCallBackUrl(env("APP_URL")."/creditsuccess?sendorId=".$sendorId."&receiver=".$receiver);
  		$payment->setCallBackUrl(env("APP_URL")."/crediterror?sendorId=".$sendorId."&receiver=".$receiver);
  		//$payment->setCallBackUrl( "http://localhost/b-side_backend/public/creditsuccess?sendorId=".$sendorId);
  		//$payment->setErrorUrl( "var/www/b-side_backend/public/crediterror?sendorId=".$sendorId);
  		$payment->addProduct( "test product", $total_amount, 1 );
          $response = $payment->make();
          if($response->isSuccess()){

              $RedirectUrl = $response->getRedirectUrl();

              return redirect($RedirectUrl);

          } else{

              return response()->json($response = [
                  'message' => 'your payment is failure',
                  'error'   => true
              ]);

          }

      	} catch( \Exception $exception ){
                  return response()->json($response = [
                      'message' => 'your handling of request failure',
                      'error'   => $exception,

            ]);

  	    }
    }
}
