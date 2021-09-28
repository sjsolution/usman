<?php

namespace App\Http\Services\MyFatoorah;
use GuzzleHttp\Client;
use Guzzle\Http\Exception\ClientErrorResponseException;
use App\Models\Orders;


class MyFatoorahPaymentServices
{
    public $paymentUrl;
    public $secretKey;

    public function __construct()
    {
        $this->secretKey      = config('services.myfatoorah.token');
        $this->paymentURL     = config('services.myfatoorah.paymentURL');
    }

    public function paymentUrl($orderId=null,$pType=1,$amount,$serviceCharge,$netPayable,$supplierCode,$user_id)
    {

        try 
        {
            $responseArr             = [];
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
                "PaymentMethodId"   => 1,
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

            $client = new Client();

            $clientRequest = $client->post(
                $this->paymentURL.'/v2/ExecutePayment/',
                [
                    'body'   => json_encode($paymentInfo),
                    'headers' => [
                        'Authorization' => 'Bearer '.$this->secretKey,
                        'Content-Type'  => 'application/json'
                    ]
                ]
            );

            $response = json_decode((string)$clientRequest->getBody(), true);

            $responseArr['status'] = 1;
            $responseArr['message'] = $response['Message'];
            $responseArr['data'] = [
                "token"      => '',
                "paymenturl" => $response['Data']['PaymentURL']
            ];
            $responseArr["SuccessCallBackURL"] =  url('/payment/success');
            $responseArr["FailedCallBackURL"]  =  url('/payment/failure');
            $responseArr['order_id']           =  $order->id;
            $responseArr['wallet_amount']      =  $order->user_id;
            $responseArr['paymentUrl']         =  $response['Data']['PaymentURL'];

            return $responseArr;
       
        }catch(\Exception $e){
            \Log::info($e->getMessage());
            return response()->json(['Payment gateway not responding'],200);
        }
    
    }

    public function successUrl($request)
    {
        try {

            $paymentInfo = [
                'Key'     => $request->paymentId,
                'KeyType' => 'PaymentId'
            ];
    
            $client = new Client();
    
            $clientRequest = $client->post(
                $this->paymentURL.'/v2/GetPaymentStatus',
                [
                    'body'   => json_encode($paymentInfo),
                    'headers' => [
                        'Authorization' => 'Bearer '.$this->secretKey,
                        'Content-Type'  => 'application/json'
                    ]
                ]
            );
    
            $response = json_decode((string)$clientRequest->getBody(), true);
    
            return $response;

        }catch (\Exception $e){
            \Log::info($e->getMessage());
            return $data = [
                'IsSuccess' => false,
                'message'   => 'Payment gateway success is not responding'
            ];
        }

   
    }

    public function failureUrl($request)
    {
        try {

            $paymentInfo = [
                'Key'     => $request->paymentId,
                'KeyType' => 'PaymentId'
            ];
    
            $client = new Client();
    
            $clientRequest = $client->post(
                $this->paymentURL.'/v2/GetPaymentStatus',
                [
                    'body'   => json_encode($paymentInfo),
                    'headers' => [
                        'Authorization' => 'Bearer '.$this->secretKey,
                        'Content-Type'  => 'application/json'
                    ]
                ]
            );
    
            $response = json_decode((string)$clientRequest->getBody(), true);
    
            return $response;

        }catch (\Exception $e){
            \Log::info($e->getMessage());
            return $data = [
                'IsSuccess' => false,
                'message'   => 'Payment gateway error is not responding'
            ];
        }

   
    }
}
