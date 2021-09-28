<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SearchServicesResource extends JsonResource
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
                'categoryId'          => $this->category_id,
                'serivceName'         => $this->name_en,
                'type'                => $this->type
            ];
        }else if($request->header('X-localization')=='ar'){
            return [
                'id'                  => $this->id,
                'categoryId'          => $this->category_id,
                'serivceName'         => $this->name_ar,
                'type'                => $this->type
            ];
        }
    
    }
}
