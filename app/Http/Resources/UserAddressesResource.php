<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserAddressesResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        //Home
        if($this->address_type == 0){
            return [
                'id'               => $this->id,
                'block'            => $this->block,
                'street'           => $this->street,
                'house'            => $this->house,
                'addressType'      => $this->address_type,
                'address'          => $this->address,
                'AddressNameType'  => 'Home',
                'AddressDetails'   =>  $this->block.' '.$this->street.' '.$this->house.' '.$this->address
            ];  
        //Office
        }elseif($this->address_type == 1){
            return [
                'id'               => $this->id,
                'block'            => $this->block,
                'street'           => $this->street,
                'building'         => $this->building,
                'floor'            => $this->floor,
                'house'            => $this->house, 
                'addressType'      => $this->address_type,
                'address'          => $this->address,
                'AddressNameType'  => 'Office',
                'AddressDetails'   => $this->block.' '.$this->street.' '.$this->building.' '.$this->floor.' '.$this->house.' '.$this->address 
            ];
        //Apartment
        }else if($this->address_type == 2){
            return [
                'id'               => $this->id,
                'block'            => $this->block,
                'street'           => $this->street,
                'building'         => $this->building,
                'floor'            => $this->floor,
                'house'            => $this->house,
                'appartmentNumber' => $this->appartment_number,
                'addressType'      => $this->address_type,
                'address'          => $this->address,
                'AddressNameType'  => 'Apartment',
                'AddressDetails'   =>  $this->block.' '.$this->street.' '.$this->building.' '.$this->floor.' '.$this->house.' '.$this->appartment_number.' '.$this->address 
            ];
        }else{
            return [
                'id'               => $this->id,
                'block'            => $this->block,
                'street'           => $this->street,
                'avenue'           => $this->avenue,
                'building'         => $this->building,
                'floor'            => $this->floor,
                'house'            => $this->house, 
                'office'           => $this->office,
                'appartmentNumber' => $this->appartment_number,
                'addressType'      => $this->address_type,
                'address'          => $this->address,
                'AddressNameType'  => 'Home',
                'AddressDetails'   =>  $this->address 
                
            ];
        }
        
    }
}
