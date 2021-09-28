<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;
use App\Models\Useraddress;
use App\SubOrderExtraAddon;
use App\SubOrderExtraAddonPaymentHistory;
use App\SubOrderAddons;
use App\Models\Suborders;

class UserBookingListResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $address = Useraddress::find($this->user_address_id);

        $isExpire = 0;
       
        if(!empty($this->serviceProvider[0])){

            $bookingDate = Carbon::parse($this->serviceProvider[0]->booking_date.' '.$this->serviceProvider[0]->booking_time);
           
            if($bookingDate->diffInHours(Carbon::now()) <= 6  && $bookingDate->diffInDays(Carbon::now()) == 0){
                $isExpire = 1;
            }
        }
        $orderAmount = 0;
        $subAddOnAmount = 0;
        //Extra Add On Amoount Total
        $getTotalOrder = SubOrderExtraAddonPaymentHistory::where('order_id',$this->id)->get();
        if($getTotalOrder){
            $orderAmount = 0;
            foreach($getTotalOrder as $order){
                    $orderAmount += $order->amount;
            }
            $totalAmount = $orderAmount;
        }
       
        //Sub Order Amount Total 
        
      /*$getSubaddonId =   Suborders::where('order_id',$this->id)->first();
        if($getSubaddonId){
            $getSubAmount =  SubOrderAddons::where('suborder_id',$getSubaddonId->id)->get();
            if($getSubAmount){
                foreach($getSubAmount as $suamount){
                    $subAddOnAmount +=$suamount->amount;
                }
            }
        }*/
       
        $earlier_total_amount = $this->total_amount;
        $addOnplusEarlierserviceAmount = $earlier_total_amount+$totalAmount;
       
        if($request->header('X-localization')=='en'){
            
            return [
                'id'                       => $this->id,
                'user_id'                  => $this->user_id,
                'categoryName'             => !empty($this->catgeory->name_en) ? $this->catgeory->name_en : '',
                'categoryType'             => $this->category_type,
                'isRated'                  => !empty($this->rating) ? 1 : 0,
                'isBookingExpire'          => (int)$isExpire,
                'addOnPlusOlsAmount'       => $addOnplusEarlierserviceAmount,
                'orderNumber'              => $this->order_number,
                'subTotalAmount'           => $this->sub_amount,
                'totalAmount'              => $this->total_amount,
                'finalAmount'              => $this->final_amount,
                'walletAmount'             => $this->wallet_amount,
                'couponAmount'             => $this->coupon_amount,
                'serviceCharge'            => $this->service_charge,
                'couponCode'               => $this->coupon_code,
                'paymentType'              => $this->payment_type,
                'paymentStatus'            => $this->payment_status,
                'currentStatus'            => $this->status,
                'transactionId'            => $this->transaction_id,
                'userApplicableFee'        => $this->user_applicable_fee,
                'userApplicableFeeLabel'   => 'User Applicable Fee',
                'isApplyUserApplicableFee' => ($this->is_apply_user_applicable_fee == 1 ) ? true : false,
                'referenceNumber'          => $this->reference_number,
                'created_at'               => $this->created_at,
                'orderStatus'              => !empty($this->orderStatus) ? BookingStatusResource::collection($this->orderStatus) : [],
                'serviceProvider'          => !empty($this->serviceProvider) ? ServiceProviderBookingResource::collection($this->serviceProvider) : [],
                'orderedService'           => !empty($this->subOrder) ? BookedServicesResource::collection($this->subOrder) : [],
                'orderedAddress'           => !empty($address) ? $address->floor.' '.$address->building.' '.$address->block.' '.$address->street.' '.$address->address : '',
                'insuranceDetails'	       => !empty($this->subOrder[0]) ? new InsuranceDetailsResource($this->subOrder[0]->insurance) : [],
                'extraAddOnservice'        => ExtraAddOnServiceWithPaymentType::collection(\App\SubOrderExtraAddon::where('order_id',$this->id)->get()),
                
            ];   

        }else if($request->header('X-localization')=='ar'){

            return [
                'id'                       => $this->id,
                'user_id'                  => $this->user_id,
                'categoryName'             => !empty($this->catgeory->name_ar) ? $this->catgeory->name_ar : '',
                'categoryType'             => $this->category_type,
                'isRated'                  => !empty($this->rating) ? 1 : 0,
                'isBookingExpire'          => (int)$isExpire,
                'addOnPlusOlsAmount'       => $addOnplusEarlierserviceAmount,
                'orderNumber'              => $this->order_number,
                'totalAmount'              => $this->total_amount,
                'finalAmount'              => $this->final_amount,
                'walletAmount'             => $this->wallet_amount,
                'couponAmount'             => $this->coupon_amount,
                'serviceCharge'            => $this->service_charge,
                'couponCode'               => $this->coupon_code,
                'paymentType'              => $this->payment_type,
                'paymentStatus'            => $this->payment_status,
                'currentStatus'            => $this->status,
                'transactionId'            => $this->transaction_id,
                'userApplicableFee'        => $this->user_applicable_fee,
                'userApplicableFeeLabel'   => 'رسوم المستخدم المطبقة',
                'isApplyUserApplicableFee' => ($this->is_apply_user_applicable_fee == 1 ) ? true : false,
                'referenceNumber'          => $this->reference_number,
                'created_at'               => $this->created_at,
                'orderStatus'              => !empty($this->orderStatus) ? BookingStatusResource::collection($this->orderStatus) : [],
                'serviceProvider'          => !empty($this->serviceProvider) ? ServiceProviderBookingResource::collection($this->serviceProvider) : [],
                'orderedService'           => !empty($this->subOrder) ? BookedServicesResource::collection($this->subOrder) : [],
                'orderedAddress'           => !empty($address) ? $address->floor.' '.$address->building.' '.$address->block.' '.$address->street.' '.$address->address : '',
                'insuranceDetails'	       => !empty($this->subOrder[0]) ? new InsuranceDetailsResource($this->subOrder[0]->insurance) : [],
                'extraAddOnservice'        => ExtraAddOnServiceWithPaymentType::collection(\App\SubOrderExtraAddon::where('order_id',$this->id)->get()),

            ];
        }
    }
}
