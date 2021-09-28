<?php
namespace App\Http\Services;
use GuzzleHttp\Client as GuzzleClient;
use Guzzle\Http\Exception\ClientErrorResponseException;
require_once 'hesabekit/Misc/Constants.php';
require_once 'hesabekit/Models/HesabeCheckoutResponseModel.php';
use App\Http\Services\hesabekit\Misc\PaymentHandler;
use App\Http\Services\hesabekit\Libraries\HesabeCrypt;
use Models\HesabeCheckoutResponseModel;
use App\Http\Services\hesabekit\Helpers\ModelBindingHelper;
use App\Http\Services\hesabekit\Misc\Constants;

class HesabeOrderPaymentServices
{
 	protected  $merchantCode;
 	protected  $paymentURL;
 	protected  $order;
 	protected  $paymentInfo;
 	protected  $paymentType;
 	#for pyment gatewat cred
 	public $paymentApiUrl;
    public $secretKey;
    public $ivKey;
    public $accessCode;
    public $hesabeCheckoutResponseModel;
    public $modelBindingHelper;
    public $hesabeCrypt;
   
    public function __construct()
    {
        $this->paymentApiUrl = config('hesabe.PAYMENT_API_URL');
        // Get all three values from Merchant Panel, Profile section
        $this->secretKey =  config('hesabe.MERCHANT_SECRET_KEY');  // Use Secret key
        $this->ivKey =  config('hesabe.MERCHANT_IV');              // Use Iv Key
        $this->accessCode =  config('hesabe.ACCESS_CODE');         // Use Access Code
        $this->hesabeCheckoutResponseModel = new HesabeCheckoutResponseModel();
        $this->modelBindingHelper          = new ModelBindingHelper();
        $this->hesabeCrypt                 = new HesabeCrypt();   // instance of Hesabe Crypt library
    }
 	 /**
     * function to set data on properties
     *
     * @return void
     */

    public function setDataForPayment($order,$type,$userWallet,$orderAmount){
     	$this->merchantCode   			=      config('hesabe.MERCHANT_CODE');
 		$this->paymentURL               =      config('hesabe.PAYMENT_URL');
 		$this->order 			        =      $order;
        $this->paymentType 		        =      $type;
        $this->userWallet               =      $userWallet;
        $this->orderAmount              =      $orderAmount;
 		return $this->makePaymentData();
    }

 	/**
    * function for set payment information to intiated payment 
    * @return json
    */
    public function makePaymentData()
    {
 		
 		$this->paymentInfo = [
 			'merchantCode'         => $this->merchantCode, 
            'amount'               => number_format($this->orderAmount,3,'.',''),
            'paymentType'          => $this->paymentType,  //1 : Knet 2: Card
            'currency'		       => 'KWD',
            'orderReferenceNumber' => $this->order->id,
            'responseUrl'          => url('/payment/success'),
            'failureUrl'           => url('/payment/failure'),
            'version'	           => 2.0,
            'variable1'            => $this->order->id,
            'variable2'            => $this->order->service_provider_id,
            'variable3'            => number_format($this->order->net_payable_amount,3,'.',''),
            'variable4'            => 'Complete order payment',
            'variable5'            => ''
 		];
 	
 		return $this->payment();
 	}
 	/**
    * function for intiated payment 
    * @return json
    */
 	public function payment(){
 		// Initialize the Payment request encryption/decryption library using the Secret Key and IV Key from the configuration
            $paymentHandler = new PaymentHandler($this->paymentApiUrl, $this->merchantCode, $this->secretKey, $this->ivKey, $this->accessCode);

            // Getting the payment data into request object
            $requestData = $this->modelBindingHelper->getCheckoutRequestData($this->paymentInfo);

            // POST the requested object to the checkout API and receive back the response
            $response = $paymentHandler->checkoutRequest($requestData);

            //Get encrypted and decrypted checkout data response
            [$encryptedResponse , $hesabeCheckoutResponseModel] = $this->getCheckoutResponse($response);

            // check the response and validate it
            if ($hesabeCheckoutResponseModel->status == false && $hesabeCheckoutResponseModel->code != Constants::SUCCESS_CODE) {
                echo "<p style='word-break: break-all;'> <b>Encrypted Data</b>:- ".$encryptedResponse."</p>";
                echo "<p><b>Decrypted Data</b>:- </p>";
                echo "<pre>";
                print_r($hesabeCheckoutResponseModel);
                exit;
            }
            $informationForPayment['status'] = 1;
            $informationForPayment['message'] = $hesabeCheckoutResponseModel->message;
            $informationForPayment['data'] = [
                "token"      => $hesabeCheckoutResponseModel->response['data'],
                "paymenturl" => $this->paymentApiUrl."/payment?data={$hesabeCheckoutResponseModel->response['data']}"
            ];
            $informationForPayment["SuccessCallBackURL"] =  url('/payment/success');
            $informationForPayment["FailedCallBackURL"]  =  url('/payment/failure');
            $informationForPayment['order_id']           =  $this->order->id;
            $informationForPayment['wallet_amount']      =  $this->userWallet;
            $informationForPayment['paymentUrl']         =  $this->paymentApiUrl."/payment?data={$hesabeCheckoutResponseModel->response['data']}";
            
            return  $informationForPayment;
 	

    }
    
	 /**
     * Process the response after the transaction is complete
     *
     * @return array De-serialize the decrypted response
     *
     */
    public function getPaymentResponse($responseData)
    {
        //Decrypt the response received in the data query string
        $decryptResponse = $this->hesabeCrypt::decrypt($responseData, $this->secretKey, $this->ivKey);

        //De-serialize the decrypted response
        return $decryptResponseData = json_decode($decryptResponse, true);
        // echo"<pre>";print_r($decryptResponseData);die;
        //Binding the decrypted response data to the entity model
        $decryptedResponse = $this->modelBindingHelper->getPaymentResponseData($decryptResponseData);

        //return decrypted data
        return $decryptedResponse;
    }

    /**
     * Process the response after the form data has been requested.
     *
     * @return array De-serialize the decrypted response
     *
     */
    public function getCheckoutResponse($response)
    {
        // Decrypt the response from the checkout API
        $decryptResponse = $this->hesabeCrypt::decrypt($response, $this->secretKey, $this->ivKey);

        if (!$decryptResponse) {
            $decryptResponse = $response;
        }

        // De-serialize the JSON string into an object
        $decryptResponseData = json_decode($decryptResponse, true);

        //Binding the decrypted response data to the entity model
        $decryptedResponse = $this->modelBindingHelper->getCheckoutResponseData($decryptResponseData);

        //return encrypted and decrypted data
        return [$response , $decryptedResponse];
    }

    public function setDataForWalletPayment($user,$type,$transactionAmount)
    {
        $this->merchantCode   			=  config('hesabe.MERCHANT_CODE');
        $this->paymentURL               =  config('hesabe.PAYMENT_URL');
        $this->user 			        =  $user;
        $this->paymentType 		        =  $type;
        $this->orderAmount              =  $transactionAmount;
        return $this->makePaymentForWallet();
    }

    public function makePaymentForWallet()
    {
        
        $this->paymentInfo = [
            'merchantCode'         => $this->merchantCode, 
            'amount'               => number_format($this->orderAmount,3,'.',''),
            'paymentType'          => $this->paymentType,  //1 : Knet 2: Card
            'currency'		       => 'KWD',
            'orderReferenceNumber' => $this->user->id,
            'responseUrl'          => url('/wallet/payment/success'),
            'failureUrl'           => url('/wallet/payment/failure'),
            'version'	           => 2.0,
            'variable1'            => $this->user->id,
            'variable2'            => $this->user->amount,
            'variable3'            => number_format($this->orderAmount,3,'.',''),
            'variable4'            => 'Complete order payment',
            'variable5'            => ''
        ];
    
        return $this->walletPayment();
    }

    public function walletPayment(){
        // Initialize the Payment request encryption/decryption library using the Secret Key and IV Key from the configuration
           $paymentHandler = new PaymentHandler($this->paymentApiUrl, $this->merchantCode, $this->secretKey, $this->ivKey, $this->accessCode);

           // Getting the payment data into request object
           $requestData = $this->modelBindingHelper->getCheckoutRequestData($this->paymentInfo);

           // POST the requested object to the checkout API and receive back the response
           $response = $paymentHandler->checkoutRequest($requestData);

           //Get encrypted and decrypted checkout data response
           [$encryptedResponse , $hesabeCheckoutResponseModel] = $this->getCheckoutResponse($response);

           // check the response and validate it
           if ($hesabeCheckoutResponseModel->status == false && $hesabeCheckoutResponseModel->code != Constants::SUCCESS_CODE) {
               echo "<p style='word-break: break-all;'> <b>Encrypted Data</b>:- ".$encryptedResponse."</p>";
               echo "<p><b>Decrypted Data</b>:- </p>";
               echo "<pre>";
               print_r($hesabeCheckoutResponseModel);
               exit;
           }
           $informationForPayment['status'] = 1;
           $informationForPayment['message'] = $hesabeCheckoutResponseModel->message;
           $informationForPayment['data'] = [
               "token"      => $hesabeCheckoutResponseModel->response['data'],
               "paymenturl" => $this->paymentApiUrl."/payment?data={$hesabeCheckoutResponseModel->response['data']}"
           ];
           $informationForPayment["SuccessCallBackURL"] =  url('/wallet/payment/success');
           $informationForPayment["FailedCallBackURL"]  =  url('/wallet/payment/failure');
           $informationForPayment['user_id']            =  $this->user->id;
           $informationForPayment['totalBalance']       =  $this->user->amount + $this->orderAmount;
           $informationForPayment['paymentUrl']         =  $this->paymentApiUrl."/payment?data={$hesabeCheckoutResponseModel->response['data']}";
           
           return  $informationForPayment;
    

    }

    public function setDataForAddonsPayment($order,$type,$transactionAmount,$orderPaymentType,$extraAddonId,$walletAmount)
    {
        $this->merchantCode   			=  config('hesabe.MERCHANT_CODE');
        $this->paymentURL               =  config('hesabe.PAYMENT_URL');
        $this->order 			        =  $order;
        $this->paymentType 		        =  $type;
        $this->orderAmount              =  $transactionAmount;
        $this->orderPaymentType         =  $orderPaymentType;
        $this->extraAddonId             =  $extraAddonId;
        $this->walletAmount             =  $walletAmount;
        return $this->makePaymentForAddons();
    }

    public function makePaymentForAddons()
    {
        
        $this->paymentInfo = [
            'merchantCode'         => $this->merchantCode, 
            'amount'               => number_format($this->orderAmount,3,'.',''),
            'paymentType'          => $this->paymentType,  //1 : Knet 2: Card
            'currency'		       => 'KWD',
            'orderReferenceNumber' => $this->order->id,
            'responseUrl'          => url('/addons/service/payment/success'),
            'failureUrl'           => url('/addons/service/payment/failure'),
            'version'	           => 2.0,
            'variable1'            => $this->order->id,
            'variable2'            => $this->orderAmount,
            'variable3'            => $this->orderPaymentType,
            'variable4'            => $this->extraAddonId,
            'variable5'            => $this->walletAmount
        ];
    
        return $this->addonPayment();
    }

    public function addonPayment(){
        // Initialize the Payment request encryption/decryption library using the Secret Key and IV Key from the configuration
           $paymentHandler = new PaymentHandler($this->paymentApiUrl, $this->merchantCode, $this->secretKey, $this->ivKey, $this->accessCode);

           // Getting the payment data into request object
           $requestData = $this->modelBindingHelper->getCheckoutRequestData($this->paymentInfo);

           // POST the requested object to the checkout API and receive back the response
           $response = $paymentHandler->checkoutRequest($requestData);

           //Get encrypted and decrypted checkout data response
           [$encryptedResponse , $hesabeCheckoutResponseModel] = $this->getCheckoutResponse($response);

           // check the response and validate it
           if ($hesabeCheckoutResponseModel->status == false && $hesabeCheckoutResponseModel->code != Constants::SUCCESS_CODE) {
               echo "<p style='word-break: break-all;'> <b>Encrypted Data</b>:- ".$encryptedResponse."</p>";
               echo "<p><b>Decrypted Data</b>:- </p>";
               echo "<pre>";
               print_r($hesabeCheckoutResponseModel);
               exit;
           }
           $informationForPayment['status'] = 1;
           $informationForPayment['message'] = $hesabeCheckoutResponseModel->message;
           $informationForPayment['data'] = [
               "token"      => $hesabeCheckoutResponseModel->response['data'],
               "paymenturl" => $this->paymentApiUrl."/payment?data={$hesabeCheckoutResponseModel->response['data']}"
           ];
           $informationForPayment["SuccessCallBackURL"] =  url('/addons/service/payment/success');
           $informationForPayment["FailedCallBackURL"]  =  url('/addons/service/payment/failure');
           $informationForPayment['order_id']           =  $this->order->id;
           $informationForPayment['total_amount']       =  $this->orderAmount;
           $informationForPayment['paymentUrl']         =  $this->paymentApiUrl."/payment?data={$hesabeCheckoutResponseModel->response['data']}";
           
           return  $informationForPayment;
    

    }

 }