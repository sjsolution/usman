<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class VehicleModelResource extends JsonResource
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
                'id'                => $this->id,
                'vehicle_brand_id'  => $this->vehicle_brand_id,
                'name'              => $this->name_en,
                'manufacture_year'  => $this->when(empty($request->detail_by_type),VehicleManufactureResource::collection($this->manufacture))
            ];
        }else if($request->header('X-localization')=='ar'){
            return [
                'id'                => $this->id,
                'vehicle_brand_id'  => $this->vehicle_brand_id,
                'name'              => $this->name_ar,
                'manufacture_year'  => $this->when(empty($request->detail_by_type),VehicleManufactureResource::collection($this->manufacture))
            ];
        }
    }
}
