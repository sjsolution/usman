<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Orders;
use Api;
use Validator;
use App\Http\Resources\UserExtraAddonRequestResource;
use App\Http\Resources\UserExtraAddonDeatilsRequestResource;
use App\User;
use App\SubOrderExtraAddonPaymentHistory;
use DB;
use App\Models\Wallet;
use App\Traits\PushNotifications;
use App\Http\Services\HesabeOrderPaymentServices;



class UserExtraServiceRequestController extends Controller
{
    use PushNotifications;

    public function __construct(Orders $orders)
    {
       $this->orders = $orders;
    }

    public function extraAddonRequestDetail(Request $request)
    {
        $validator= Validator::make($request->all(),[
            'order_id'          => 'required|exists:orders,id'
        ]);

        if($validator->fails()){

            return response()->json(Api::apiValidationResponse($validator->errors()->first()),422);
        }

        $order = $this->orders->where('id',$request->order_id)->first();

        if($order->extraAddonOrder->count()){

            return response()->json(Api::apiSuccessResponse('Extra addons request confirmation details',new UserExtraAddonRequestResource($order)));

        }else{

            return response()->json(Api::apiErrorResponse('Invalid request...please contact you related service provider'),422);

        }

    }

    public function addonPaymentDetails(Request $request){
        $validator= Validator::make($request->all(),[
            'order_id'          => 'required|exists:orders,id',
            'notification_type' => 'required'
        ]);
        if($request->notification_type =="101"){
            $payment_type = "2";
            $seleted_Payment_type = "2";

        }elseif($request->notification_type =="102"){
            $payment_type = "1";
            $seleted_Payment_type = "1";
        }
        $order = $this->orders->where('id',$request->order_id)->with(['extraAddonOrder' =>function ($q) use($payment_type){
            $q->join('sub_order_extra_addon_payment_histories as od','sub_order_extra_addons.sub_extra_payment_history_id','=','od.id')
            ->where('od.payment_type',$payment_type)->where('od.payment_status','0');
        }])->first();
        if(($order))
        if($order->extraAddonOrder->isempty()){
            $order = $this->orders->where('id',$request->order_id)->with(['extraAddonOrder' =>function ($q) use($payment_type){
                $q->join('sub_order_extra_addon_payment_histories as od','sub_order_extra_addons.sub_extra_payment_history_id','=','od.id')
                ->where('od.payment_type',$payment_type);
            }])->first();
        }
        if($order){
            $orderTotalAmount =0;
            foreach($order->extraAddonOrder as $totalpaid){
                $orderTotalAmount += $totalpaid->amount;
            }
            $totalPaidAmount = $orderTotalAmount;     
        }
      
        $getTotalOrder = SubOrderExtraAddonPaymentHistory::where('order_id',$request->order_id)->where('payment_type',$payment_type)->where('payment_status',"0")->get();
     
        if($getTotalOrder){
            $orderAmount = 0;
            foreach($getTotalOrder as $orderd){
                $orderAmount += $orderd->amount;
            }
            $totalAmount = $orderAmount;
        }
        if($validator->fails()){
         return response()->json(Api::apiValidationResponse($validator->errors()->first()),422);
        }
        if($totalAmount > 0 && $request->notification_type == "101"){
            $is_pay = true;
        }else{
            $is_pay =false;
        }
       if($order){
            $totalWalletAmount = User::find($order->user_id);
            $data = ["order"=>new UserExtraAddonDeatilsRequestResource($order),'wallet_amount'=> $totalWalletAmount['amount'],'total_payment_amount'=>$totalAmount,'is_pay'=>$is_pay,'seleted_Payment_type'=> $seleted_Payment_type,'total_addOn_amount'=>$totalPaidAmount];
            return response()->json(Api::apiSuccessResponse('Extra addons request confirmation details',$data));
        }else{
            return response()->json(Api::apiErrorResponse('Invalid request...please contact you related service provider'),422);
        }
        
    }

    //addon service  payment by  User 

    public function addOnServiceMakePayment(Request $request)
    {
        try{ 
           $validator= Validator::make($request->all(),[
              'payment_type'    => 'required|in:1,2,3,4,5', //1 : Wallet Payment , 2 : Knet 3 : knet & wallet, 4: Credit Card, 5: Credit Card & Wallet
              'order_id'        => 'required|exists:orders,id',
              'total_amount'    => 'required',
              'wallet_amount'   => 'required',
              'extra_addon_id'  => 'required',
            ]);
            if($validator->fails()){
                return response()->json(['status'=>0,'message' => $validator->errors()->first()]);
            }
            $order = $this->orders->where('id',$request->order_id)->first();
            $user = User::find($order->user_id);
            if($request->payment_type == 1 || $request->payment_type == 3 || $request->payment_type == 5){
                if($user->amount <= 0){
                     return response()->json(['status'=>0,'message' =>"You wallet have insufficient balance"]);
                }
                
            }
           
          //wallet payment
            if($request->payment_type == 1){
                if($user->amount > 0){
                    $remaningTotalAmount = (double)$user->amount - (double)$request->total_amount;
                        if($remaningTotalAmount >= 0) {
                            $user->amount            = $remaningTotalAmount;
                            $user->save();
                            $user->wallet()->create([
                                'transaction_amount' => $request->total_amount,
                                'closing_amount'     => $remaningTotalAmount,
                                'transaction_type'   => '2', //debit amount from wallet
                                'description'        => "Debited from wallet",
                                'description_ar'     => "مدين من المحفظة"
                            ]);
                           
                            $getid = $order->extraAddonOrderPaymentHistory()->where(['order_id'=>$request->order_id,'payment_type'=>'2','payment_status'=>'0'])->update([
                                'paid_by'   => '1',
                                'payment_status' => '1',
                                'paid_from_wallet' => $request->total_amount
                            ]);
                            
                            //send notification to technician
                            if(!empty($order->serviceProvider[0]->technician)){
                            
                                foreach($order->serviceProvider[0]->technician->deviceInfo as $key){
                                
                                    if(!empty($key->device_token)){
                        
                                        if($order->user->is_language=='ar'){
                                            $title     = 'دفع إضافي على خدمة الإضافات';
                                            $subject   = 'دفع إضافي خدمة إضافية ناجح';
                                        }else{
                                            $title   = 'Extra addon service payment';
                                            $subject = 'Payment successful';
                                        }
                    
                                        
                                        \Log::info('extra addons payment push'.$order->serviceProvider[0]->technician->id);
                    
                                        $this->sendNotification($title,$subject,$key->device_token,'1',$order->id,'2');
                    
                    
                                        // $this->sendNotification($title,$subject,$key->device_token,'booking_detail_timer_screen',$order->id,'2');
                                
                                    }
                                    
                                }
                            }

                          return response()->json(['status'=>1,'message' =>"Payment has been successfully","wallet_amount"=>$remaningTotalAmount]);
                        }else{
                            return response()->json(['status'=>0,'message' =>"You wallet have insufficient balance"]);
                        }
                }else{
                  return response()->json(['status'=>0,'message' =>"You wallet have insufficient balance"]);
                }
       
             } 
            elseif($request->payment_type == 2)//Knet Only
            {  
                // $response = $this->paymentUrl((double)$request->total_amount,$request->order_id,1,"2",$request->extra_addon_id,$request->wallet_amount);
             
                $hesabe         = new HesabeOrderPaymentServices;
                $response       = $hesabe->setDataForAddonsPayment($order,1,$request->total_amount,$request->payment_type,$request->extra_addon_id,$request->wallet_amount);
               
                // $resArr = [
                //   'status'        => 1,
                //   'message'       => $response['message'],
                //   'order_id'      => $request->order_id,
                //   'total_amount'  => $request->total_amount,
                //   'paymentUrl'    => (config('app.env') == 'staging') ? $response['data']['paymenturl'].$response['data']['token'] : $response['data']['paymenturl'].$response['data']['token'].$response['data']['ptype']
                // ];
                // /dd($response);
                DB::commit();
      
                return  $response ;
      
            }elseif($request->payment_type == 3)//knet + Wallet
            {
                if($user->amount > 0)
                {
                   
                    $remaningTotalAmount = (double)$user->amount - (double)$request->wallet_amount;
                    if($remaningTotalAmount >= 0) 
                        {
                            $final_amount =  (double)$request->total_amount-(double)$request->wallet_amount;
                            // $response = $this->paymentUrl((double)$final_amount,$request->order_id,1,"3",$request->extra_addon_id,$request->wallet_amount);

                            $hesabe         = new HesabeOrderPaymentServices;
                            $response       = $hesabe->setDataForAddonsPayment($order,1,$final_amount,$request->payment_type,$request->extra_addon_id,$request->wallet_amount);

                            // $resArr = [
                            // 'status'        => 1,
                            // 'message'       => $response['message'],
                            // 'order_id'      => $order->id,
                            // 'wallet_amount' => $user->amount - $request->wallet_amount,
                            // 'paymentUrl'    => (config('app.env') == 'staging') ? $response['data']['paymenturl'].$response['data']['token'] : $response['data']['paymenturl'].$response['data']['token'].$response['data']['ptype']
                            // ];
                
                            DB::commit();
                
                            return $response;
                        } 
                    else{
                        return response()->json(['status'=>0,'message' =>"You wallet have insufficient balance"]);
                    }
                }else{
                    return response()->json(['status'=>0,'message' =>"You wallet have insufficient balance"]);
                }
                
            }elseif($request->payment_type == 4)//Credit Card
            {
                // $response = $this->paymentUrl((double)$request->total_amount,$request->order_id,2,"4",$request->extra_addon_id,$request->wallet_amount);
                // $resArr = [
                //   'status'        => 1,
                //   'message'       => $response['message'],
                //   'order_id'      => $order->id,
                //   'wallet_amount' => $user->amount - $request->wallet_amount,
                //   'paymentUrl'    => (config('app.env') == 'staging') ? $response['data']['paymenturl'].$response['data']['token'] : $response['data']['paymenturl'].$response['data']['token'].$response['data']['ptype']
                // ];

                $hesabe         = new HesabeOrderPaymentServices;
                $response       = $hesabe->setDataForAddonsPayment($order,2,$request->total_amount,$request->payment_type,$request->extra_addon_id,$request->wallet_amount);
                DB::commit();
      
                return $response;
            }elseif($request->payment_type == 5){  //Credit Card & Wallet
                if($user->amount > 0)
                {
                    $remaningTotalAmount = (double)$user->amount - (double)$request->wallet_amount;
                        if($remaningTotalAmount >= 0) 
                        {
                            $final_amount =  (double)$request->total_amount-(double)$request->wallet_amount;
                            // $response = $this->paymentUrl((double)$final_amount,$request->order_id,2,"5",$request->extra_addon_id,$request->wallet_amount);
                
                            // $resArr = [
                            // 'status'        => 1,
                            // 'message'       => $response['message'],
                            // 'order_id'      => $request->order_id,
                            // 'wallet_amount' => $user->amount - $request->wallet_amount,
                            // 'paymentUrl'    => (config('app.env') == 'staging') ? $response['data']['paymenturl'].$response['data']['token'] : $response['data']['paymenturl'].$response['data']['token'].$response['data']['ptype']
                            // ];

                            $hesabe         = new HesabeOrderPaymentServices;
                            $response       = $hesabe->setDataForAddonsPayment($order,2,$final_amount,$request->payment_type,$request->extra_addon_id,$request->wallet_amount);
                
                            DB::commit();
                
                            return $response;
                        }
                        else{
                            return response()->json(['status'=>0,'message' =>"You wallet have insufficient balance"]);
                        }
                }else{
                    return response()->json(['status'=>0,'message' =>"You wallet have insufficient balance"]);
                }
      
            }


        }catch (\Exception $e) {
           
            DB::rollBack();
            return response()->json(Api::apiErrorResponse('something went wrong'),422);
        }
    }

    public function paymentUrl($amount=0,$orderId='',$pType=1,$payment_type='',$paymentId,$wallet)
    {
        
      try{
          
        $data = [
            'MerchantCode' => config('app.payment_merchant_code'), 
            'Amount'       => number_format($amount,3),
            'Ptype'        => $pType,  //1 : Knet 3 : knet & wallet, 4: Credit Card, 5: Credit Card & Wallet
            'SuccessUrl'   => url('/addons/service/payment/success'),
            'FailureUrl'   => url('/addons/service/payment/failure'),
            'Variable1'    => $orderId,
            'Variable2'    => $amount,
            'Variable3'    => $payment_type, //2 : Knet 3 : knet & wallet, 4: Credit Card, 5: Credit Card & Wallet
            'Variable4'    => $paymentId,
            'Variable5'    => $wallet
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

    public function success(Request $request)
    {
      try 
        { 
            //Hesabe Payment
            $hesabe                     = new HesabeOrderPaymentServices;
            $hesabeSuccessResponse      = $hesabe->getPaymentResponse($request->data);
            //  
            
            $order = $this->orders->where('id',$hesabeSuccessResponse['response']['variable1'])->first();

            \Log::info('addon_paymeny'.$order->id);

            $user = User::find($order->user_id);

            if($hesabeSuccessResponse['response']['variable3'] == 3 || $hesabeSuccessResponse['response']['variable3'] == 5){
                $remaningTotalAmount = (double)$user->amount - (double)$hesabeSuccessResponse['response']['variable5'];
                $user->amount        = $remaningTotalAmount;
                $user->save();
                $user->wallet()->create([
                    'transaction_amount' => $hesabeSuccessResponse['response']['variable5'],
                    'closing_amount'     => $remaningTotalAmount,
                    'transaction_type'   => '2', //debit amount from wallet
                    'description'        => "Debited from wallet",
                    'description_ar'     => "مدين من المحفظة"
                ]);
            }

            $data = json_encode([
                'payment_status'  => '1',
                'paymentToken'    =>  $hesabeSuccessResponse['response']['paymentToken'] ?? null,
                'paymentId'       =>  $hesabeSuccessResponse['response']['paymentId'] ?? null,
                'paidOn'          =>  $hesabeSuccessResponse['response']['paidOn'] ?? null,
                'method'          =>   $hesabeSuccessResponse['response']['method'] ?? null,
                'administrativeCharge'   => $hesabeSuccessResponse['response']['administrativeCharge'] ?? null,
               
            ]);

            $extraAddpaymnetOrderId = explode(',',$hesabeSuccessResponse['response']['variable4']);

            $payment = SubOrderExtraAddonPaymentHistory::whereIn('id',$extraAddpaymnetOrderId)->update([
                'payment_status' => '1',
                'paid_by'        => $hesabeSuccessResponse['response']['variable3'],
                'paid_from_account'=> $hesabeSuccessResponse['response']['variable2'],
                'paid_from_wallet' => $hesabeSuccessResponse['response']['variable5']?? null,
                'paymentToken'   => $hesabeSuccessResponse['response']['paymentToken'] ?? null,
                'paymentId'      => $hesabeSuccessResponse['response']['paymentId']  ?? null,
                'payment_details'=> $data
            ]);
            // $order = $this->orders->where('id',$request->Variable1)->first();
            //send notification to technician
            if(!empty($order->serviceProvider[0]->technician)){
               
                foreach($order->serviceProvider[0]->technician->deviceInfo as $key){
                
                    if(!empty($key->device_token)){
        
                        if($order->user->is_language=='ar'){
                            $title     = 'دفع إضافي على خدمة الإضافات';
                            $subject   = 'دفع إضافي خدمة إضافية ناجح';
                        }else{
                            $title   = 'Extra addon service payment';
                            $subject = 'Payment successful';
                        }
    
                        
                        \Log::info('extra addons payment push'.$order->serviceProvider[0]->technician->id);
    
                        $this->sendNotification($title,$subject,$key->device_token,'1',$order->id,'2');
    
    
                        // $this->sendNotification($title,$subject,$key->device_token,'booking_detail_timer_screen',$order->id,'2');
                
                    }
                    
                }
            }

            
            return response()->json(['message' =>'Payment is successful']);
     
        }catch(\Exception $e){
            return response()->json(['status'=>1,'message' =>'Something went wrong']);
        }
    }

    public function failure(Request $request)
    {
      return response()->json(['message' => 'Payment Fail...please try again....!']);
    }

    public function testTechnotification()
    {
        $order = $this->orders->where('id',2790)->first();
        if(!empty($order->serviceProvider[0]->technician)){
               
            foreach($order->serviceProvider[0]->technician->deviceInfo as $key){
            
                if(!empty($key->device_token)){
    
                    if($order->user->is_language=='ar'){
                        $title     = 'دفع إضافي على خدمة الإضافات';
                        $subject   = 'دفع إضافي خدمة إضافية ناجح';
                    }else{
                        $title   = 'Extra addon service payment';
                        $subject = 'Payment successful';
                    }

                    
                    \Log::info('extra addons payment push'.$order->serviceProvider[0]->technician->id);

                    $this->sendNotification($title,$subject,$key->device_token,'1',$order->id,'2');


                    // $this->sendNotification($title,$subject,$key->device_token,'booking_detail_timer_screen',$order->id,'2');
            
                }
                
            }
        }

        //$this->sendNotification('Hello Testing','testing','fLDIFr8dSjw:APA91bF4hrwoRJcZnll0GsHbenSLngycK571tIGM4dyJ622yuTJxL0cfavOXHP27JvJw8-8mrTS2vKjkmi4u0P59666v2F7CoW_dUcwbBbaccLRKvt_50dluOTCt8tQYVBd5-kXliD0r','1',2754,'2');
    }
}
