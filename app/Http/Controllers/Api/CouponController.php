<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Coupon;
use Validator;
use Api;
use Carbon\Carbon;

class CouponController extends Controller
{
    public function __construct(Coupon $coupon)
    {
        $this->coupon = $coupon;
    }

    public function applyCoupon(Request $request)
    {
        $validator= Validator::make($request->all(),[
            'user_id'             => 'required|exists:users,id',
            'service_provider_id' => 'exists:users,id',
            'coupon_code'         => 'required',
            'category_id'         => 'required|exists:categories,id',
            'service_id'          => 'required|exists:service,id',
            'service_amount'      => 'required|numeric'
        ]);

        if($validator->fails()){
            return response()->json(Api::apiValidationResponse($validator->errors()->first()),422);
        }

        $coupons = $this->coupon->where('code',$request->coupon_code)->where('status',1);

        if($coupons->exists()){

            $coupon = $coupons->first();

            //Check valid till date
            if(!empty($coupon->valid_till)){

                if($coupon->valid_till >= Carbon::now()->format('Y-m-d')){

                    
                }else{

                    return response()->json(Api::apiErrorResponse(__('message.couponExpired')),422);
                }
            }

            //Coupan user limit
            if(!empty($coupon->user_limit)){

                $couponHistories = \App\Models\Orders::where('coupon_id',$coupon->id)->distinct('user_id')->count();
                if($coupon->user_limit > $couponHistories){

                    
                }else{

                    return response()->json(Api::apiErrorResponse(__('message.couponUserLimit')),422);
                }
            }

            //Check min-max value 
            if($coupon->coupon_min_value <= $request->service_amount && $coupon->coupon_max_value >= $request->service_amount){
                
                
            }else{

                return response()->json(Api::apiErrorResponse('Service amount limit must lie between'.$coupon->coupon_min_value.'-'.$coupon->coupon_max_value),422);
            }



            //Check specific user
            if($coupon->assignedUser->count()){

                $userCouponApplicable = $coupon->assignedUser->first(function ($value, $key) {
                   
                    return ($value->user_id == (int)request()->user_id);
                   
                });
                
                if(!empty($userCouponApplicable)){
                   

                }else{

                    return response()->json(Api::apiErrorResponse(__('message.couponSelectedUser')),422);

                }
 
            }

            //check specfic catgeory
            if($coupon->assignedCategory->count()){

                $categoryCouponApplicable = $coupon->assignedCategory->first(function ($value, $key) {
                   
                    return ($value->category_id == (int)request()->category_id);
                   
                });
                
                if(!empty($categoryCouponApplicable)){
                   

                }else{

                    return response()->json(Api::apiErrorResponse(__('message.couponSelectedCategory')),422);

                }
 
            }

            //check specific service provider
            if($coupon->assignedServiceProvider->count()){

                $serviceProviderCouponApplicable = $coupon->assignedServiceProvider->first(function ($value, $key) {
                   
                    return ($value->user_id == (int)request()->service_provider_id);
                   
                });
                
                if(!empty($serviceProviderCouponApplicable)){
                   


                }else{

                    return response()->json(Api::apiErrorResponse(__('message.couponSelectedSP')),422);

                }
 
            }

            //check specific service
            if($coupon->assignedServices->count()){

                $serviceCouponApplicable = $coupon->assignedServices->first(function ($value, $key) {
                   
                    return ($value->service_id == (int)request()->service_id);
                   
                });
                
                if(!empty($serviceCouponApplicable)){
                   

                }else{

                    return response()->json(Api::apiErrorResponse(__('message.couponSelectedService')),422);

                }
 
            }

            $discountAmount = 0;

            if($coupon->type == '1'){

                $discountAmount = ($request->service_amount * $coupon->coupon_value ) / 100 ;

            }elseif($coupon->type == '2'){

                if($coupon->coupon_value <= $request->service_amount){

                    $discountAmount = $coupon->coupon_value;

                }else{

                    return response()->json(Api::apiErrorResponse(__('message.couponAmtLessThanServeAmt')),422);

                }
            }

            $resArr = [
                'coupon_id'      => $coupon->id,
                'coupon_code'    => $coupon->code,
                'discountAmount' => (double)$discountAmount,
                'serviceAmount'  => (double)$request->service_amount

            ];

            return response()->json(Api::apiSuccessResponse(__('message.couponApplied'),$resArr));


        }else{
            
            return response()->json(Api::apiErrorResponse(__('message.couponInvalid')),422);
        }

    }

}
