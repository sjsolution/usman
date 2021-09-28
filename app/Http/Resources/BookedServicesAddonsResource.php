<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BookedServicesAddonsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    { 
      //  dd($this);
        if($request->header('X-localization')=='en'){
    
            return [
                'id'                   => $this->id,
                'serviceAddonId'       => $this->service_addon_id,
                'serviceAddonName'     => \App\Models\Serviceaddons::where('id',$this->service_addon_id)->first()->name_en,
                'serviceAddonAmount'   => $this->amount,
            ]; 

        }else if($request->header('X-localization')=='ar'){
            return [
                'id'                   => $this->id,
                'serviceAddonId'       => $this->service_addon_id,
                'serviceAddonName'     => \App\Models\Serviceaddons::where('id',$this->service_addon_id)->first()->name_ar,
                'serviceAddonAmount'   => $this->amount
              
            ];
        }
    }
}
