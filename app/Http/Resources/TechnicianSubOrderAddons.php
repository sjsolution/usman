<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TechnicianSubOrderAddons extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
    //    / dd($this);
        if($request->header('X-localization')=='en'){
            
            return [
                'addonId'                  => $this->service_addon_id,
                'addonName'                => \App\Models\Serviceaddons::find($this->service_addon_id)->name_en,
                'addonAmount'              => $this->amount
            ];

        }else if($request->header('X-localization')=='ar'){

            return [
                'addonId'                  => $this->service_addon_id,
                'addonName'                => \App\Models\Serviceaddons::find($this->service_addon_id)->name_ar,
                'addonAmount'              => $this->amount
            ];

        }
    }
}
