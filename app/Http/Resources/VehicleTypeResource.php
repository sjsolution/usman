<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class VehicleTypeResource extends JsonResource
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
                'id'            => $this->id,
                'name'          => $this->name_en,
                'image'         => $this->image,
                'vehicle_brand' => $this->when(empty($request->detail_by_type),VehicleBrandResource::collection($this->brands))
            ];
        }else if($request->header('X-localization')=='ar'){
            return [
                'id'            => $this->id,
                'name'          => $this->name_ar,
                'image'         => $this->image,
                'vehicle_brand' => $this->when(!empty($request->detail_by_type),VehicleBrandResource::collection($this->brands))
            ];
        }
    }
}
