<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserBookingDetailsResource extends JsonResource
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
                'name'                => $this->full_name_en,
                'contactNumber'       => $this->mobile_number
              
            ];

        }else if($request->header('X-localization')=='ar'){
            
            return [
                'id'                  => $this->id,
                'name'                => $this->full_name_en,
                'contactNumber'       => $this->mobile_number
            ];
        }
    }
}
