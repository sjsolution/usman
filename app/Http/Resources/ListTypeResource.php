<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ListTypeResource extends JsonResource
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
                'name'                => $this->name_en
            ];
        }else if($request->header('X-localization')=='ar'){
            return [
                'id'                  => $this->id,
                'name'                => $this->name_ar
            ];
        }
    }
}
