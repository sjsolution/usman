<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CityResource extends JsonResource
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
                'cityName'         => $this->name_en,
                'cityAreas'        => CityAreaResource::collection($this->areas)
            ];    
        }else if($request->header('X-localization')=='ar'){
            return [
                'id'               => $this->id,
                'cityName'         => $this->name_ar,
                'cityAreas'        => CityAreaResource::collection($this->areas)
            ]; 
        }
    }
}
