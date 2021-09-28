<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ServiceProviderSubCategoryServiceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    { 
        $category = \App\Models\Category::find($this->category_id);

        if($category->type == 2){  

            $amount = ((request()->vehicle_value * $this->insurance_percentage) / 100) + $this->fixed_price;
            //Add 120 KD Logic here
            if(request()->vehicle_value > 0 && $this->service_type == 1 ){
            
                if($amount >= config('app.insurance_service_fixed_price')){
                    // $fixedServicePrice = $amount;
                    $amount = $amount;

                }else{

                    $amount = config('app.insurance_service_fixed_price');

                }
    
            }
  
        }else{
            $amount = $this->amount;
        }

        if($request->header('X-localization')=='en'){
            return [
                'id'               => $this->id,
                'name'             => $this->name_en,
                'special_note'     => $this->special_note_en,
                'duration'         => $this->time_duration.' Min',
                'amount'           => (double)$amount,
                'sub_category_id'  => $this->sub_category_id,
                'description'      => $this->servicedescription->pluck('description_en'),
                'addons'           => SPSubCategoryAddonsResource::collection($this->addons)
            ];

        }else if($request->header('X-localization')=='ar'){
            return [
                'id'               => $this->id,
                'name'             => $this->name_ar,
                'special_note'     => $this->special_note_ar,
                'duration'         => 'دقيقة  '.$this->time_duration,
                'amount'           => (double)$amount,
                'sub_category_id'  => $this->sub_category_id,
                'description'      => $this->servicedescription->pluck('description_ar'),
                'addons'           => SPSubCategoryAddonsResource::collection($this->addons)
            ];
        }
    }
}
