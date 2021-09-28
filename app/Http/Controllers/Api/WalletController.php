<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Wallet;
use App\User;
use App\Models\Adminwallet;
use Illuminate\Support\Facades\Validator;
use Response;
use App\Http\Services\HesabeOrderPaymentServices;

class WalletController extends Controller
{
   
  public function wallethistory(Request $request)
  {
   
      $mesage            = '';
      $mesage_ar         = '';
      $wallethistories   = Wallet::where('user_id',$request->user_id)->orderBy('id','desc')->get();
      $totalWalletAmount = User::find($request->user_id);
      $walletData        = [];

      if(!empty($wallethistories))
      {
        foreach ($wallethistories as $wallet) {

          if($wallet['transaction_type']=='0'){
            $mesage      = "Add money";
            $mesage_ar   = "إضافة المال";
          }else if($wallet['transaction_type']=='1'){
            $mesage      = "Credit Amount";
            $mesage_ar   = "مبلغ الائتمان";

          }else if($wallet['transaction_type']=='2'){
            $mesage      = "Debit money";
            $mesage_ar   = "مدين";
          }

          $walletData[]=[
            'id'                 => $wallet['id'],
            'transaction_amount' => number_format($wallet['transaction_amount'],3),
            'closing_amount'     => number_format($wallet['closing_amount'],3),
            'transaction_type'   => $wallet['transaction_type'],
            'description'        => (app()->getLocale() == "en" ) ? $wallet['description'] : $wallet['description_ar'] ?? $mesage_ar ,
            'description_ar'     => $mesage_ar,
            'message'            => (app()->getLocale() == "ar" ) ? $mesage_ar : $mesage,
            'transaction_time'   => date("h:i A",strtotime($wallet['created_at'])),
            //'transaction_time'=>$wallet['created_at'],
          ];
        }
      }

      if($walletData || $totalWalletAmount){
        return response()->json(['status'=>1,'message' =>'success','wallet_amount'=>$totalWalletAmount['amount'],'walllet'=>$walletData]);
      }else{
        return response()->json(['status'=>0,'message' =>__('message.notRecordFound')]);
      }

    
  }

      
  public function addmoneytowallet(Request $request)
  {
    $validator= Validator::make($request->all(),[
      'user_id'                =>'required|exists:users,id',
      'wallet_suggestion_id'   =>'required|exists:adminwallet,id',
      'payment_type'           => 'in:1,2'
    ]);

    if($validator->fails()){
      return response()->json(['status'=>0,'message'=> $validator->errors()->first()]);
    }

    $user = \App\User::find($request->user_id);
    $pType = !empty($request->payment_type) ? $request->payment_type : 1;

    $adminwallet  = Adminwallet::find($request->wallet_suggestion_id);
    /*Offer money not add in pament amount while ayment so code comment
    / by kajal
    */
    $addAmount  = $adminwallet['amount'] + $adminwallet['credit_amount'];
    $paymentAmount  = $adminwallet['amount'];
 
    // $response =  $this->paymentUrl((double)$paymentAmount,$request->user_id,$pType,$addAmount);

    //Hesabe Payment

    $hesabe         = new HesabeOrderPaymentServices;
    $response       = $hesabe->setDataForWalletPayment($user,$pType,$paymentAmount);

    // $resArr = [
    //   'status'       => 1,
    //   'message'      => $response['message'],
    //   'paymentUrl'   => (config('app.env') == 'staging') ? $response['data']['paymenturl'].$response['data']['token'] : $response['data']['paymenturl'].$response['data']['token'].$response['data']['ptype'],
    //   'totalBalance' => (double)($user->amount + $addAmount)
    // ];

    return $response;

  }


  public function paymentUrl($amount=0,$userId=null,$pType=1,$addAmount=0)
  {

    try{

      $data = [
          'MerchantCode' => config('app.payment_merchant_code'), 
          'Amount'      => number_format($amount,3),
          'Ptype'       => $pType,
          'SuccessUrl'  => url('/wallet/payment/success'),
          'FailureUrl'  => url('/wallet/payment/failure'),
          'Variable1'   => $userId,
          'Variable2'   => $amount,
          'Variable3'   => $addAmount //total amount+ offer amount
      ];

      $client   = new \GuzzleHttp\Client();
    
      $url      = config('app.payment_url'). "?". http_build_query($data);
    
      $res      = $client->post($url);

      $response =  json_decode((string)$res->getBody(), true);
    
      if(!empty($response) && $response['status'] == 'success'){
          return $response;
      }else{
          return false;
      }

    }catch (\Exception $e) {
      return response()->json(['Payment gateway not responding'],200);
    }
    

  }

  public function amountsuggestionforwallet(Request $request)
  {
    if($request->Method('post')){
      $validator= Validator::make($request->all(),[
        'user_id'     =>'required|exists:users,id',
      ]);
      if($validator->fails()){
        return response()->json(['status'=>0,'message'=> $validator->errors()->first()]);
      }
      $userAmount = User::find($request->user_id);
      $adminwalletdata = Adminwallet::get();
      $walletData=[];
      if(!empty($adminwalletdata)){
        foreach ($adminwalletdata as $usercreditamount) {
          $walletData[]=[
            'wallet_suggestion_id'=>$usercreditamount['id'],
            'amount'=>$usercreditamount['amount'],
            'credit_amount'=>$usercreditamount['credit_amount'],
            //'amount'=>number_format($usercreditamount['amount'],3),
            //'credit_amount'=>number_format($usercreditamount['credit_amount'],3),
          ];
        }
      }
      if($walletData){
        return response()->json(['status'=>1,'message' =>'success','wallet_balance'=>$userAmount['amount'],'walllet_suggestion'=>$walletData]);
      }else{
        return response()->json(['status'=>0,'message' =>__('message.notRecordFound')]);
      }
    }
  }

  public function success(Request $request)
  {
    
    try {

      //Hesabe Payment
      $hesabe                     = new HesabeOrderPaymentServices;
      $hesabeSuccessResponse      = $hesabe->getPaymentResponse($request->data);
      //

      $addedAmount = 0;
      $user = \App\User::find($hesabeSuccessResponse['response']['variable1']);
      $addedAmount = $user->amount + $hesabeSuccessResponse['response']['variable3'];
      $user->amount = $addedAmount;
      $user->save();
        
      $wallet = Wallet::create([
        'user_id'             => $hesabeSuccessResponse['response']['variable1'],
        'closing_amount'      => $addedAmount,
       // 'transaction_amount'  =>  $request->Variable2,
        'transaction_amount'  =>  $hesabeSuccessResponse['response']['variable3'],
        'transaction_type'    => '0',
        'description'         => "Added from knet",
        'description_ar'      => "تمت الإضافة من معقد"
      ]);

      return view('payment_success');

      return response()->json(['status'=>1,'message' =>'success','walllet'=>$wallet]);
    
    }catch(\Exception $e){
  
      return response()->json(['status'=>0,'message' =>'Something went wrong']);

    }
      
  }

  public function failure(Request $request)
  { 
    return view('payment_fail');
  }

}
