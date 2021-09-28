<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class User extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
      
      $full_name = "full_name_".app()->getLocale();
      return [
         'id' => $this->id,
          $full_name=> $this->$full_name,
         'email' => $this->email,
         'country_code' => $this->country_code,
         'mobile_number' => $this->mobile_number,
         'profile_pic' => isset($this->profile_pic)?$this->profile_pic:'',
     ];

    }
}
