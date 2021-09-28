<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserOrderTrackingResource extends JsonResource
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
                'orderId'               => $this->id,
                'finalLatitude'         => $this->userAddress->location_latitude,
                'finalLongitude'        => $this->userAddress->location_longitude,
                'liveTracking'          => $this->technicianTracking
            ];

        }else if($request->header('X-localization')=='ar'){

            return [
                'orderId'               => $this->id,
                'finalLatitude'         => $this->userAddress->location_latitude,
                'finalLongitude'        => $this->userAddress->location_longitude,
                'liveTracking'          => $this->technicianTracking
            ];
        }
    }
}
