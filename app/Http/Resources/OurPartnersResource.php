<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OurPartnersResource extends JsonResource
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
                'name'                => $this->partnername_en,
                'image'               => $this->partner_image
            ];
        }else if($request->header('X-localization')=='ar'){
            return [
                'id'                  => $this->id,
                'name'                => $this->partnername_ar,
                'image'               => $this->partner_image
            ];
        }
    }
}
