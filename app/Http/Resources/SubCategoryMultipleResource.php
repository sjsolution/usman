<?php

namespace App\Http\Resources;
use App\Models\Category;


use Illuminate\Http\Resources\Json\JsonResource;

class SubCategoryMultipleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {  
       
        $category  = Category::where('parent_id',request()->category_id)->where('id',$this->sub_category_id)->first();
    
        if($request->header('X-localization')=='en'){
            return [
                'id'        => !empty($category['id'])      ? $category['id'] : '',
                'name'      => !empty($category['name_en']) ? $category['name_en'] : '',
                'services'  => !empty($category) && isset( $category) ? ServiceProviderSubCategoryServiceResource::collection($category['servicesForSubcategory']->where('user_id',request()->service_provider_id)->where('is_active','1')) : [] 
            ];
        }else if($request->header('X-localization')=='ar'){
            return [
                'id'        => !empty($category['id'])      ? $category['id'] : '',
                'name'      => !empty($category['name_ar']) ? $category['name_ar'] : '',
                'services'  => !empty($category['servicesForSubcategory']) ? ServiceProviderSubCategoryServiceResource::collection($category['servicesForSubcategory']->where('user_id',request()->service_provider_id)->where('is_active','1')) : [],
            ];
        }
    }
}
