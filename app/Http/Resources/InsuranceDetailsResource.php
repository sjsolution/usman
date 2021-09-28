<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class InsuranceDetailsResource extends JsonResource
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
                'vehicleType'               => $this->vehicle['vehilcesdata']['name_en'],
                'vehicleBrand'              => $this->vehicle['brands']['name_en'],
                'vehicleModel'              => $this->vehicle['models']['name_en'],
                'vehicleManufacturingYear'  => $this->vehicle['year_of_manufacture'],
                'plateNumber'               => $this->vehicle['registration_number'],
                'vehicleValue'              => $this->vehicle['vehicle_value'],
                'civilIdFront'              => $this->civil_id_front,
                'civilIdBack'               => $this->civil_id_back,
                'chassisNumber'             => $this->chassis_number,
                'description'               => $this->description,
                'images'                    => explode(',',$this->images),
                'mobileNumber'              => $this->mobile_number,
                'insuranceStartDate'        => $this->insurance_start_date

            ];

        }else if($request->header('X-localization')=='ar'){

            return [
                'vehicleType'               => $this->vehicle['vehilcesdata']['name_ar'],
                'vehicleBrand'              => $this->vehicle['brands']['name_ar'],
                'vehicleModel'              => $this->vehicle['models']['name_ar'],
                'vehicleManufacturingYear'  => $this->vehicle['year_of_manufacture'],
                'plateNumber'               => $this->vehicle['registration_number'],
                'vehicleValue'              => $this->vehicle['vehicle_value'],
                'civilIdFront'              => $this->civil_id_front,
                'civilIdBack'               => $this->civil_id_back,
                'chassisNumber'             => $this->chassis_number,
                'description'               => $this->description,
                'images'                    => explode(',',$this->images),
                'mobileNumber'              => $this->mobile_number,
                'insuranceStartDate'        => $this->insurance_start_date
            ];
            
        }
    }
}
