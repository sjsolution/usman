<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class VehicleBrandResource extends JsonResource
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
                'vehicle_type_id'  => $this->vehicle_type_id,
                'name'             => $this->name_en,
                'models'           => $this->when(empty($request->detail_by_type),VehicleModelResource::collection($this->brandmodel))
            ];
        }else if($request->header('X-localization')=='ar'){
            return [
                'id'               => $this->id,
                'vehicle_type_id'  => $this->vehicle_type_id,
                'name'             => $this->name_ar,
                'models'           => $this->when(empty($request->detail_by_type),VehicleModelResource::collection($this->brandmodel))
            ];
        }
    }
}
