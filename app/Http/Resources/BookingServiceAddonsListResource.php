<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BookingServiceAddonsListResource extends JsonResource
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
                'id'                       => $this->id,
                'service_addon_id'         => $this->id,
                'serviceId'                => $this->service_id,
                'serviceName'              => $this->name_en,
                'serviceAmount'            => $this->amount,
                'serviceTimeDuration'      => !empty($this->time_duration) ? $this->time_duration : 0
            ];

        }else if($request->header('X-localization')=='ar'){
            
            return [
                'id'                       => $this->id,
                'service_addon_id'         => $this->id,
                'serviceId'                => $this->service_id,
                'serviceName'              => $this->name_ar,
                'serviceAmount'            => $this->amount,
                'serviceTimeDuration'      => !empty($this->time_duration) ? $this->time_duration : 0
            ];
        }
    }
}
