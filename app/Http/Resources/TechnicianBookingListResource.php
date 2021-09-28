<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;
use App\SubOrderAddons;
use App\SubOrderExtraAddonPaymentHistory;
use App\Models\Suborders;

class TechnicianBookingListResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $service = \App\Models\Service::find($this->subOrder[0]->service_id);
        $subAddOnAmount   = 0;
        $extraAddOnAmount = 0;
        $extraAddonsPaymentStatus = 0;

        //subAdd On Amount
        $getSubaddonId =   Suborders::where('order_id',$this->id)->first();
        if($getSubaddonId){
            $getSubAmount =  SubOrderAddons::where('suborder_id',$getSubaddonId->id)->get();
            if($getSubAmount){
                foreach($getSubAmount as $suamount){
                    $subAddOnAmount +=$suamount->amount;
                }
            }
        }
       
        $getTotalOrder = SubOrderExtraAddonPaymentHistory::where('order_id',$this->id)->get();
     
        if($getTotalOrder){
            $extraorderAmount =0;
            foreach($getTotalOrder as $extraAddon){
                $extraAddOnAmount += $extraAddon->amount;
            }
        } 
        //Total Amount 
         $finalFullAmount = $this->total_amount + $extraAddOnAmount;
        //Service Amount 
        $serviceAmount =  $this->total_amount - $subAddOnAmount;
        //check payment status for extra AddOn
        /*Note :- This will return 0 in case of Cash Payment */
        $findUnpaid = SubOrderExtraAddonPaymentHistory::where(['order_id'=>$this->id,'payment_type'=>'2'])->get();
        if($findUnpaid){
           foreach($findUnpaid as $check){
                if($check->payment_status == '0'){
                    $extraAddonsPaymentStatus = 0;
                }else{
                    $extraAddonsPaymentStatus = 1;
                }
            }
        }else{
            $extraAddonsPaymentStatus = 0;
        }

        if($request->header('X-localization')=='en'){
            // dd(Carbon::parse($this->serviceProvider[0]->booking_date.' '.$this->serviceProvider[0]->booking_time)->lte(Carbon::now()->addHour(2)));
            return [
                'id'                       => $this->id,
                'serviceId'                => $service->id,
                'serviceName'              => $service->name_en,
                'serviceAmount'            => $serviceAmount,
                'finalAmount'              => $finalFullAmount,
                'isBookingProcessing'      => Carbon::parse($this->serviceProvider[0]->booking_date.' '.$this->serviceProvider[0]->booking_time)->lte(Carbon::now()->addHour(2)) ? true : false,
                'serviceAddress'           => new UserBookingAddresssResource(\App\Models\Useraddress::find($this->user_address_id)),
                'bookingDate'              => Carbon::parse($this->serviceProvider[0]->booking_date)->format('jS M y'),
                'bookingTime'              => Carbon::parse($this->serviceProvider[0]->booking_time)->format('g:i A'),
                'bookingEndTime'           => Carbon::parse($this->serviceProvider[0]->booking_end_time)->format('g:i A'),
                'userDetails'              => new UserBookingDetailsResource($this->user),
                'bookinAmount'             => $this->total_amount,
                'bookingStatus'            => $this->status, //0=pending,1=processing,2=Done,3=Failed,4=canceled,
                'paymentStatus'            => $this->payment_status,
                'paymentType'              => $this->payment_type,
                'serviceAddons'            => !empty($this->subOrder[0]->addons) ? TechnicianSubOrderAddons::collection($this->subOrder[0]->addons) : [],
                'isAddonAdded'             => $this->extraAddonOrder()->exists(),
                'addonPaymentType'         => !empty($this->extraAddonOrderPaymentHistory) ? $this->extraAddonOrderPaymentHistory->payment_type : 0,
                'serviceExtraAddons'       => !empty($this->extraAddonOrder) ? ExtraAddonResource::collection($this->extraAddonOrder) : [],
                'extraAddonsPaymentStatus' =>  $extraAddonsPaymentStatus,//!empty($this->extraAddonOrderPaymentHistory) &&  ($this->extraAddonOrderPaymentHistory->payment_status == '1' ) ? 1 : 0,
                'bookingCurrentStatus'     => ($this->orderStatus->count()) ? $this->orderStatus->last()->status : 0,
                'bookingCurrentStatusTime' => ($this->orderStatus->count()) ? strtotime(Carbon::parse($this->orderStatus->last()->created_at)) : 0,
                'seriveTimeDuration'       => gmdate('H:i:s',Carbon::parse($this->serviceProvider[0]->booking_end_time)->diffInSeconds(Carbon::parse($this->serviceProvider[0]->booking_time))),
                'extraAddonRequest'        => ExtraAddonRequestResource::collection($this->extraAddonOrderRequest) ?? []
            ]; 

        }else if($request->header('X-localization')=='ar'){

            return [
                'id'                       => $this->id,
                'serviceId'                => $service->id,
                'serviceName'              => $service->name_ar,
                'serviceAmount'            => $serviceAmount,
                'finalAmount'              => $finalFullAmount,
                'isBookingProcessing'      => Carbon::parse($this->serviceProvider[0]->booking_date.' '.$this->serviceProvider[0]->booking_time)->lte(Carbon::now()->addHour(2)) ? true : false,
                'serviceAddress'           => new UserBookingAddresssResource(\App\Models\Useraddress::find($this->user_address_id)),
                'bookingDate'              => Carbon::parse($this->serviceProvider[0]->booking_date)->format('jS M y'),
                'bookingTime'              => Carbon::parse($this->serviceProvider[0]->booking_time)->format('g:i A'),
                'bookingEndTime'           => Carbon::parse($this->serviceProvider[0]->booking_end_time)->format('g:i A'),
                'userDetails'              => new UserBookingDetailsResource($this->user),
                'bookinAmount'             => $this->total_amount,
                'bookingStatus'            => $this->status, //0=pending,1=processing,2=Done,3=Failed,4=canceled,
                'paymentStatus'            => $this->payment_status,
                'paymentType'              => $this->payment_type,
                'serviceAddons'            => !empty($this->subOrder[0]->addons) ? TechnicianSubOrderAddons::collection($this->subOrder[0]->addons) : [],
                'isAddonAdded'             => $this->extraAddonOrder()->exists(),
                'addonPaymentType'         => !empty($this->extraAddonOrderPaymentHistory) ? $this->extraAddonOrderPaymentHistory->payment_type : 0,
                'serviceExtraAddons'       => !empty($this->extraAddonOrder) ? ExtraAddonResource::collection($this->extraAddonOrder) : [],
                'extraAddonsPaymentStatus' => $extraAddonsPaymentStatus,//!empty($this->extraAddonOrderPaymentHistory) &&  ($this->extraAddonOrderPaymentHistory->payment_status == '1' ) ? 1 : 0,
                'bookingCurrentStatus'     => ($this->orderStatus->count()) ? $this->orderStatus->last()->status : 0,
                'bookingCurrentStatusTime' => ($this->orderStatus->count()) ? strtotime(Carbon::parse($this->orderStatus->last()->created_at)) : 0,
                'seriveTimeDuration'       => gmdate('H:i:s',Carbon::parse($this->serviceProvider[0]->booking_end_time)->diffInSeconds(Carbon::parse($this->serviceProvider[0]->booking_time))),
                'extraAddonRequest'        => ExtraAddonRequestResource::collection($this->extraAddonOrderRequest) ?? []

            ];

        }
    }
}
