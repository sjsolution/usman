<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Serviceaddons;
use App\SubOrderExtraAddonPaymentHistory;

class ExtraAddOnServiceWithPaymentType extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {

        $getPaymentType = SubOrderExtraAddonPaymentHistory::where('id',$this->sub_extra_payment_history_id)->first();
        if($getPaymentType->payment_type == '1'){
            $en_payment_type = 'Cash';
            $ar_payment_type = 'السيولة النقدية';
        }else{
            $en_payment_type = 'Online';
            $ar_payment_type = 'عبر الانترنت';
        }
        if($request->header('X-localization')=='en'){
           
            return [
                'id'                => $this->id,
                'order_id'          => $this->order_id,
                'serviceAddonId'    => $this->service_addon_id,
                'serviceName'       => \App\Models\Serviceaddons::where('id',$this->service_addon_id)->first()->name_en,
                'serviceAmount'     => $this->amount,
                'payment_type'      => $en_payment_type
                
            ];
        }else if($request->header('X-localization')=='ar'){

            return [
                'id'                => $this->id,
                'order_id'          => $this->order_id,
                'serviceAddonId'    => $this->service_addon_id,
                'serviceName'       => \App\Models\Serviceaddons::where('id',$this->service_addon_id)->first()->name_ar,
                'serviceAmount'     => $this->amount,
                'payment_type'      => $ar_payment_type
            ];
        }
    }
}
