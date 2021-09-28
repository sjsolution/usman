<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\SubOrderAddons;
use App\Models\Suborders;
class BookedServicesResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {  
        $subAddOnAmount = 0;
        //subAdd On Amount
        $getSubaddonId =   Suborders::where('order_id',$this->order_id)->first();
        if($getSubaddonId){
            $getSubAmount =  SubOrderAddons::where('suborder_id',$getSubaddonId->id)->get();
            if($getSubAmount){
                foreach($getSubAmount as $suamount){
                    $subAddOnAmount +=$suamount->amount;
                }
            }
        }
        //service Amount Minus Sub Order Amount 
        $serviceAmtMinusSubOrderAmount = $this->sub_amount- $subAddOnAmount;
        if($request->header('X-localization')=='en'){
            // dd($this->addons);
            return [
                'id'                => $this->id,
                'serviceId'         => $this->service_id,
               // 'Order'         => $this->order_number_id,
                'serviceName'       => \App\Models\Service::where('id',$this->service_id)->first()->name_en,
                'serviceAmount'     => $serviceAmtMinusSubOrderAmount,//$this->sub_amount,
                'serviceAddons'     => BookedServicesAddonsResource::collection($this->addons),
               
            ]; 

        }else if($request->header('X-localization')=='ar'){
            return [
                'id'                => $this->id,
                'serviceId'         => $this->service_id,
                'serviceName'       => \App\Models\Service::where('id',$this->service_id)->first()->name_ar,
                'serviceAmount'     => $this->sub_amount,
                'serviceAddons'     => BookedServicesAddonsResource::collection($this->addons)
            ];
        }
    
    }
}
