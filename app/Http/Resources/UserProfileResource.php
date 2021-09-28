<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserProfileResource extends JsonResource
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
                'name'                => !empty($this->full_name_en) ? $this->full_name_en : $this->full_name_ar,
                'phoneNumber'         => $this->mobile_number,
                'walletAmount'        => $this->amount,
                'profileImage'        => $this->profile_pic,
                'notificationStatus'  => $this->is_notification,
                'referralCode'        => !empty($this->my_referal_code) ? $this->my_referal_code : 'MAAK-'.$this->id,
                'contactPageUrl'      => 'http://maak.live/'
            ];

        }else if($request->header('X-localization')=='ar'){

            return [
                'id'                  => $this->id,
                'name'                => !empty($this->full_name_ar) ? $this->full_name_ar : $this->full_name_en,
                'phoneNumber'         => $this->mobile_number,
                'walletAmount'        => $this->amount,
                'profileImage'        => $this->profile_pic,
                'notificationStatus'  => $this->is_notification,
                'referralCode'        => $this->my_referal_code,
                'contactPageUrl'      => 'http://maak.live/'
            ];
        }
    }
}
