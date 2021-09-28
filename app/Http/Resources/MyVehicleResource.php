<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MyVehicleResource extends JsonResource
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
                'user_id'             => request()->user_id,
                'vehicle_type_id'     => $this->vehicle_type_id,
                'brand_id'            => $this->brand_id,
                'model_id'            => $this->model_id,
                'brand_name'          => \App\Models\Vehiclebrand::find($this->brand_id)->name_en,
                'model_number'        => \App\Models\Vehiclemodel::find($this->model_id)->name_en,
                'registration_number' => $this->registration_number,
                'year_of_manufacture' => $this->year_of_manufacture
            ];
        }else if($request->header('X-localization')=='ar'){
            return [
                'id'                  => $this->id,
                'user_id'             => request()->user_id,
                'vehicle_type_id'     => $this->vehicle_type_id,
                'brand_id'            => $this->brand_id,
                'model_id'            => $this->model_id,
                'brand_name'          => \App\Models\Vehiclebrand::find($this->brand_id)->name_ar,
                'model_number'        => \App\Models\Vehiclemodel::find($this->model_id)->name_ar,
                'registration_number' => $this->registration_number,
                'year_of_manufacture' => $this->year_of_manufacture
            ];
        }
    }
}
