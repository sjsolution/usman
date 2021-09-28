<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserExtraAddonRequestResource extends JsonResource
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
                'orderId'                => $this->id,
                'orderNumber'            => $this->order_number,
                'serviceName'            => '',
                'extraAddonServices'     => UserExtraAddonServiceDetailtResource::collection($this->extraAddonOrder),
                'addonPaymentStatus'     => new UserExtraAddonPaymentDetailResource($this->extraAddonOrderPaymentHistory)
            ];

        }else if($request->header('X-localization')=='ar'){

            return [
                'orderId'                => $this->id,
                'orderNumber'            => $this->order_number,
                'extraAddonServices'     => UserExtraAddonServiceDetailtResource::collection($this->extraAddonOrder),
                'addonPaymentStatus'     => new UserExtraAddonPaymentDetailResource($this->extraAddonOrderPaymentHistory)
            ];
        }
    }
}
