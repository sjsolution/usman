<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ExtraAddonRequestResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        if($request->header('X-localization')=='en'){

            return [
                'id'                  => $this->id,
                'orderId'             => $this->order_id,
                'amount'              => $this->amount,
                'paymentType'         => $this->payment_type,
                'paymentStatus'       => $this->payment_status,
                'paidBy'              => $this->paid_by
            ]; 

        }else if($request->header('X-localization')=='ar'){

            return [
                'id'                  => $this->id,
                'orderId'             => $this->order_id,
                'amount'              => $this->amount,
                'paymentType'         => $this->payment_type,
                'paymentStatus'       => $this->payment_status,
                'paidBy'              => $this->paid_by
            ];
            
        }
    }
}
