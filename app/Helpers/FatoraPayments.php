<?php 

namespace App\Helpers;
use App\Http\Controllers\Controller;
use Validator;
use App\Models\Orders;
use DB;
use App\Payment;

class FatoraPayments extends Controller
{ 
  public static function paymentUrl($orderId=null,$pType=1,$amount,$serviceCharge,$netPayable,$supplierCode,$user_id)
  {
      $total_amt               = $netPayable;
      $order                   = Orders::where('id',$orderId)->with('user')->first(); 
      $supplier                = $supplierCode;
 
        if($pType== "1"){
          $paymentmethod  =  1 ;
        }
        if($pType== "2"){
          $paymentmethod  =  2 ;
        } 

          $orderItems = [];

          $orderItems[] = [
              "ItemName"  => $serviceCharge ?? 0,
              "Quantity"  => 1,
              "UnitPrice" => $total_amt
          ];

          $paymentInfo = [
              "PaymentMethodId"   => $pType,
              "CustomerName"      => $order->user->full_name_en,
              "DisplayCurrencyIso"=> "KWD",
              "MobileCountryCode" => "+965",
              "CustomerMobile"    => $order->user->mobile_number,
              "CustomerEmail"     => $order->user->email,
              "InvoiceValue"      => $total_amt,
              "CallBackUrl"       => route('transaction-success'),
              "ErrorUrl"          => route('transaction-error'),  
              "Language"          => "en",
              "CustomerReference" => $supplier,
              "ExpireDate"        => $order->created_at,    
              "InvoiceItems"      => $orderItems,
              "UserDefinedField"  => $orderId,
          ];

          $token             = config('services.myfatoorah.token');
          $paymentURL        = config('services.myfatoorah.paymentURL');
          // curl
          $curl = curl_init();
          curl_setopt_array($curl, array(
        CURLOPT_URL => "$paymentURL/v2/ExecutePayment",
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => json_encode($paymentInfo),
        CURLOPT_HTTPHEADER => array("Authorization: Bearer $token","Content-Type: application/json"),
        ));
        
      curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
      $response = curl_exec($curl);
    
      $err = curl_error($curl);
      curl_close($curl);
      try{
        
        if ($err) {
          
          echo "cURL Error #:" . $err;
            // throw new \Exception("Error Processing while payment initiated"+$err, 1); 
        } 
        else {
          
          $informationForPayment  = json_decode((string)$response, true);
          
          echo"<pre>";print_r($informationForPayment);die;
        
        }
      }catch(\Exception $e){
          
        dd($e);
      }
      echo $response;die;
      
          throw new \Exception("Error Processing while payment initiated".$err, 1);
  }

  public function successCallback(Request $request)
  {

    try{
      DB::beginTransaction();

      
      $token =  config('services.myfatoorah.token');
      #API call for get all the payment information 
        $curl = curl_init();

        curl_setopt_array($curl, array(
              CURLOPT_URL => config('services.myfatoorah.paymentURL')."/v2/GetPaymentStatus",
              CURLOPT_CUSTOMREQUEST => "POST",
              CURLOPT_POSTFIELDS => json_encode(array('Key'=>$request->paymentId,'KeyType' => 'PaymentId')),
              CURLOPT_HTTPHEADER => array("Authorization: Bearer $token","Content-Type: application/json"),
          ));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);


        $response = curl_exec($curl);

        
        $err = curl_error($curl);
        curl_close($curl);
    if ($err) {
      echo "cURL Error #:" . $err;
    } 
    else {
        #payment gateway return response after payment get failed
      $json  = json_decode((string)$response, true);

      $order_id       = $json['Data']['UserDefinedField'];
      /**UPDATE STATUS DATA IN Orders */
      $order = Orders::find($order_id);
      
          if($order){
                  $order->payment_id             = $request->paymentId;
                  $order->transaction_id         = $json['Data']['InvoiceTransactions'][0]['TransactionId'] ?? null;
                  $order->payment_status         = '2';
                  $order->gateway                = '1';
                  $order->save();

                  /**STORE DATA IN PAYMENT */
              $paymentStore = Payment::create([
                  'order_id'                      => $order->id,
                  'category_type'                  => $order->category_type,
                  'service_amount'                 => $order->total_amount,
                  'net_payable'                    => $json['Data']['InvoiceItems'][0]['UnitPrice'],
                  'paymentId'                      => $json['Data']['InvoiceTransactions'][0]['PaymentId'],
                  'transactionId'                  => $json['Data']['InvoiceTransactions'][0]['TransactionId'] ?? null,
                  'getCustomerServiceCharge'       => $json['Data']['InvoiceTransactions'][0]['CustomerServiceCharge'],
                  'referenceId'                    => $json['Data']['InvoiceTransactions'][0]['ReferenceId'],                       
                  'invoiceReference'               => $json['Data']['InvoiceReference'],                       
                  'invoiceStatus'                  => $json['Data']['InvoiceStatus'],                       
                  'invoiceId'                      => $json['Data']['InvoiceId'],
                  'customerName'                   => $json['Data']['CustomerName'],
                  'paymentGateway'                 => $json['Data']['InvoiceTransactions'][0]['PaymentGateway'],                    
                  ]);
                  
              DB::commit();

              echo "<h1 style='color:green'>Transaction Success, Refrence Id: $paymentStore->referenceId </h1>";
          }
              // throw new \Exception("Error processing on success callback request", 1);
      }
    }catch(\Exception $e){
          DB::rollback();
          throw $e;
          return redirect()->back()->with('success',__('message.something_went_wrong'));
    }
  }

  public function errorCallback(Request $request)
  {
      try{
          DB::beginTransaction();
          $token =  config('services.myfatoorah.token');
          #API call for get all the payment information 
            $curl = curl_init();
            curl_setopt_array($curl, array(
                  CURLOPT_URL => config('services.myfatoorah.paymentURL')."/v2/GetPaymentStatus",
                  CURLOPT_CUSTOMREQUEST => "POST",
                  CURLOPT_POSTFIELDS => json_encode(array('Key'=>$request->paymentId,'KeyType' => 'PaymentId')),
                  CURLOPT_HTTPHEADER => array("Authorization: Bearer $token","Content-Type: application/json"),
             ));
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($curl);
            $err = curl_error($curl);
            curl_close($curl);
        if ($err) {
          echo "cURL Error #:" . $err;
        } 
        else {
            #payment gateway return response after payment get failed
         $json  = json_decode((string)$response, true);

         $order_id       = $json['Data']['UserDefinedField'];
         /**UPDATE STATUS DATA IN Orders */
         $order = Orders::find($order_id);
         
              if($order){
                     $order->payment_id             = $request->paymentId;
                     $order->transaction_id         = $json['Data']['InvoiceTransactions'][0]['TransactionId'] ?? null;
                     $order->payment_status         = '3';
                     $order->gateway                = '1';
                     $order->save();

                     /**STORE DATA IN PAYMENT */
                  $paymentStore = Payment::create([
                     'order_id'                      => $order->id,
                     'category_type'                  => $order->category_type,
                     'service_amount'                 => $order->total_amount,
                     'net_payable'                    => $json['Data']['InvoiceItems'][0]['UnitPrice'],
                     'paymentId'                      => $json['Data']['InvoiceTransactions'][0]['PaymentId'],
                     'transactionId'                  => $json['Data']['InvoiceTransactions'][0]['TransactionId'] ?? null,
                     'getCustomerServiceCharge'       => $json['Data']['InvoiceTransactions'][0]['CustomerServiceCharge'],
                     'referenceId'                    => $json['Data']['InvoiceTransactions'][0]['ReferenceId'],                       
                     'invoiceReference'               => $json['Data']['InvoiceReference'],                       
                     'invoiceStatus'                  => $json['Data']['InvoiceStatus'],                       
                     'invoiceId'                      => $json['Data']['InvoiceId'],
                     'customerName'                   => $json['Data']['CustomerName'],
                     'paymentGateway'                 => $json['Data']['InvoiceTransactions'][0]['PaymentGateway'],                    
                     ]);
                     
                  DB::commit();

                 echo "<h1 style='color:red'>Transaction Failure, Refrence Id: $paymentStore->referenceId </h1>";
             }
                // throw new \Exception("Error processing on success callback request", 1);
        }
      }catch(\Exception $e){
             DB::rollback();
             throw $e;
             return redirect()->back()->with('success',__('message.something_went_wrong'));
      }
  }

}