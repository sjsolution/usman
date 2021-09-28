<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class VehicleManufactureResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        
        $years = [];

        // if(($this->from_year > 0) && ($this->from_year <= $this->to_year)){
        //     for($i=$this->from_year ; $i <= $this->to_year ;$i++){
        //      $years[] =  (int)$i;
        //     }
        // }
            
        return [
            'id'                => $this->id,
            'vehicle_type_id'   => $this->vehicle_model_id,
            'fromYear'          => (int)$this->from_year,
            'toYear'            => (int)$this->to_year,
            // 'years'             => $years
        ];
      
    }
}
