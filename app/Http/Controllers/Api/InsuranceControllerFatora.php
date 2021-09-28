<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\Models\Service;
use App\Helpers\FatoraPayments;
use App\Models\Serviceaddons;
use App\Models\Vehicles;
use App\Models\Vehicletype;
use App\Models\Insurancetype;
use App\Models\Insurancecardetails;
use App\Models\Cms;
use App\Models\Orders;
use App\Models\Suborders;
use App\Models\Wallet;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\User as UserResource;
use App\Traits\CommonTrait;
use DB;
use Api;
use App\Traits\PushNotifications;
use App\Http\Services\MyFatoorah\MyFatoorahPaymentServices;
use App\Jobs\SendInvoiceEmailJob;
use App\Models\PaymentGatewaySetting;


class InsuranceControllerFatora extends Controller
{
    use CommonTrait,PushNotifications;

    public function makepayment(Request $request){

        $validator= Validator::make($request->all(),[
            'type'          => 'required|in:1,2', // 1 = Insurance,2 = Normal Or Emergency
            'payment_type'  => 'required|in:0,1,2,3,4,5', //0: None ,1 : Wallet Payment , 2 : Knet 3 : knet & wallet, 4: Credit Card, 5: Credit Card & Wallet
            'user_id'       => 'required|exists:users,id',
            'service_id'    => 'required|exists:service,id',
            'sub_amount'    => 'required',
            'wallet_amount' => 'required',
          ]);
  
          if($validator->fails()){
            return response()->json(['status'=>0,'message' => $validator->errors()->first()]);
          }

          if($validator->fails()){
            return response()->json(['status'=>0,'message' => $validator->errors()->first()]);
          }
  
          $user =  User::where('id',$request->user_id)->first();
  
          if($user->is_active == 0){
  
            return response()->json(Api::apiErrorResponse(__('message.userLogout')),401);
  
          }
  
          if($request->payment_type == 1){
  
            if($user->amount > 0){
          
              if($request->total_amount != $request->wallet_amount || ($request->total_amount - $request->coupon_amount) != $request->wallet_amount)
                return response()->json(['status'=>0,'message' =>"You wallet have insufficient balance"]);
            }else{
              return response()->json(['status'=>0,'message' =>"You wallet have insufficient balance"]);
            }
          }
          $isBooked = 0;
  
          if($request->payment_type == 1){
            $isBooked = 1;
          }else{
            $isBooked = 0;
          }
      
          $orderNumber = time().'-'.$request->user_id;
  
          $bookServiceData = Service::where('id',$request->service_id)->first();

          try{  

          DB::beginTransaction();

            $order = Orders::create([
            'user_id'             => $request->user_id,
            'category_id'         => !empty($bookServiceData) ? $bookServiceData->category->id : null,
            'category_type'       => (string)$request->type,
            'order_number'        => $orderNumber,
            'service_charge'      => isset($request->service_charge) ?  $request->service_charge : 0.000,
            'coupon_amount'       => isset($request->coupon_amount)  ?  $request->coupon_amount:0.00,
            'coupon_code'         => isset($request->coupon_code)    ?  $request->coupon_code:'',
            'coupon_id'           => isset($request->coupon_id)      ?  $request->coupon_id:null,
            'payment_status'      => '0',
            'payment_type'        => $request->payment_type,
            'final_amount'        => isset($request->final_amount)   ?  $request->final_amount:0.00,
            'status'              => '0',
            'total_amount'        => $request->totalServiceAmount,
            'net_payable_amount'  => !empty($request->total_amount) ? $request->total_amount : $request->totalServiceAmount,
            'sub_amount'          => $request->sub_amount,
            'wallet_amount'       => isset($request->wallet_amount) ? $request->wallet_amount:0.00,
            'user_applicable_fee' => !empty($request->user_application_fee) ? $request->user_application_fee : 0,
            'is_apply_user_applicable_fee' => !empty($request->user_application_fee) ? 1 : 0,
            'user_address_id'     => isset($request->user_address_id) ? $request->user_address_id : null
            ]);

        
            $order->orderStatus()->create([
              'status' => '0'
            ]);
            
            //END
        
            DB::commit();

            $defaultPayment = PaymentGatewaySetting::where('is_default',1)->first();

            if($defaultPayment->id == 1){ //Hesabe Payment

            }else if($defaultPayment->id == 2){ //Fatoorah Payment
              $hesabe         = new MyFatoorahPaymentServices;
              $response       = $hesabe->paymentUrl($order->id,$order->payment_type,$order->total_amount,$order->service_charge,$order->net_payable_amount,123456,$order->user_id);
            }
              
            return $response;

            return response()->json(Api::apiSuccessResponse(__('message.PaymentUrl')),200);

    }catch (\Exception $e) {

        DB::rollBack();
        return response()->json(['status' => 0 ,'message' => 'something went wrong'],200);
     
      }

    }
}
