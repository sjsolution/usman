<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CityAreaResource extends JsonResource
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
                'id'               => $this->id,
                'areaName'         => $this->name_en,
                'latitude'         => !empty($this->latitude)  ? (double)$this->latitude : '',
                'longitude'        => !empty($this->longitude) ? (double)$this->longitude : ''
            ];    
        }else if($request->header('X-localization')=='ar'){
            return [
                'id'               => $this->id,
                'areaName'         => $this->name_ar,
                'latitude'         => !empty($this->latitude)  ? (double)$this->latitude : '',
                'longitude'        => !empty($this->longitude) ? (double)$this->longitude : ''
            ]; 
        }
    }
}
