<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserExtraAddonServiceDetailtResource extends JsonResource
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
                'serviceAddonId'    => $this->service_addon_id,
                'serviceName'       => $this->serviceAddons->name_en,
                'serviceAmount'     => $this->amount,
                'timeDuration'      => $this->serviceAddons->time_duration
            ];

        }else if($request->header('X-localization')=='ar'){

            return [
                'id'                => $this->id,
                'serviceAddonId'    => $this->service_addon_id,
                'serviceName'       => $this->serviceAddons->name_ar,
                'serviceAmount'     => $this->amount,
                'timeDuration'      => $this->serviceAddons->time_duration
            ];
        }
    }
}
