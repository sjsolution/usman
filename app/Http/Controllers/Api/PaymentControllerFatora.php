<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use App\Payment;
use App\Models\Orders;

class PaymentControllerFatora extends Controller
{
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
