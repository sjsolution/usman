<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CouponResource extends JsonResource
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
                'name'                => $this->name_en,
                'code'                => $this->code,
                'description'         => $this->description_en,
                'image'               => !empty($this->image) ? $this->image : '',
                'type'                => $this->type,
                'couponValue'         => $this->coupon_value,
                'validTill'           => $this->valid_till,
                'couponMinValue'      => $this->coupon_min_value,
                'couponMaxValue'      => $this->coupon_max_value,
                'userLimit'           => $this->user_limit,
                'couponPerUserLimit'  => $this->coupon_per_user_limit
            ];

        }else if($request->header('X-localization')=='ar'){

            return [
                'id'                  => $this->id,
                'name'                => $this->name_ar,
                'description'         => $this->description_ar,
                'image'               => !empty($this->image) ? $this->image : '',
                'type'                => $this->type,
                'couponValue'         => $this->coupon_value,
                'validTill'           => $this->valid_till,
                'couponMinValue'      => $this->coupon_min_value,
                'couponMaxValue'      => $this->coupon_max_value,
                'userLimit'           => $this->user_limit,
                'couponPerUserLimit'  => $this->coupon_per_user_limit
            ];
            
        }
    }
}
