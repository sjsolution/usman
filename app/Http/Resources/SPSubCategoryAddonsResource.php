<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SPSubCategoryAddonsResource extends JsonResource
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
            if($this->is_delete =='0'){
              
              return [
                  'id'               => $this->id,
                  'name'             => $this->name_en,
                  'description'      => $this->description_en,
                  'amount'           => (double)$this->amount
              ];
            }
        }else if($request->header('X-localization')=='ar'){
          if($this->is_delete =='0'){
            return [
                'id'               => $this->id,
                'name'             => $this->name_ar,
                'description'      => $this->description_ar,
                'amount'           => (double)$this->amount
            ];
          }
        }
    }
}
