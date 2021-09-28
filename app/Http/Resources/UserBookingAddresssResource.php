<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserBookingAddresssResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $type = '';

        if($this->address_type==0){
            $type = 'Home';
        }elseif($this->address_type==1){
            $type = 'Office';
        }elseif($this->address_type==2){
            $type = "Apartment";
        }

        if($request->header('X-localization')=='en'){

            return [
                'id'                  => $this->id,
                'address'             => $this->block.' '.$this->street.' '.$this->avenue.' '.$this->floor.' '.$this->building.' '.$this->house.' '.$this->office.' '.$this->address ,
                'latitude'            => $this->location_latitude,
                'longitude'           => $this->location_longitude,
                'addressType'         => $type
            ];

        }else if($request->header('X-localization')=='ar'){
            
            return [
                'id'                  => $this->id,
                'address'             => $this->block.' '.$this->street.' '.$this->avenue.' '.$this->floor.' '.$this->building.' '.$this->house.' '.$this->office.' '.$this->address ,
                'latitude'            => $this->location_latitude,
                'longitude'           => $this->location_longitude,
                'addressType'         => $type
            ];
        }
    }
}
