<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\SubOrderExtraAddonPaymentHistory;

class ExtraAddonResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        if($this->extraAddonOrderPaymentHistoryDeatils->payment_type == '1'){
            $en_payment_type = 'Cash';
            $ar_payment_type = 'السيولة النقدية';
        }else{
            $en_payment_type = 'Online';
            $ar_payment_type = 'عبر الانترنت';
        }
        if($request->header('X-localization')=='en'){

            return [
                'sub_extra_addon_id'  => $this->id,
                'id'                  => $this->serviceAddons->id,
                'name'                => $this->serviceAddons->name_en,
                'amount'              => $this->serviceAddons->amount,
                'extra_add_on_amount'   => $this->amount,
                'sub_extra_payment_history_id'=> $this->sub_extra_payment_history_id,
                'payment_type'       => $this->extraAddonOrderPaymentHistoryDeatils->payment_type,
                'payment_mode'       =>  $en_payment_type,
                'paid_by'            => $this->extraAddonOrderPaymentHistoryDeatils->paid_by,
                'payment_status'     => $this->extraAddonOrderPaymentHistoryDeatils->payment_status
            ];

        }else if($request->header('X-localization')=='ar'){

            return [
                'id'                  => $this->serviceAddons->id,
                'name'                => $this->serviceAddons->name_ar,
                'amount'              => $this->serviceAddons->amount,
                'extra_add_on_amount'   => $this->amount,
                'sub_extra_payment_history_id'=> $this->sub_extra_payment_history_id,
                'payment_type'       => $this->extraAddonOrderPaymentHistoryDeatils->payment_type,
                'payment_mode'       =>  $ar_payment_type,
                'paid_by'            => $this->extraAddonOrderPaymentHistoryDeatils->paid_by,
                'payment_status'     => $this->extraAddonOrderPaymentHistoryDeatils->payment_status
            ];
            
        }
    }
}
