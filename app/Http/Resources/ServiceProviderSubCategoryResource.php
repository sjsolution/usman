<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Category;

class ServiceProviderSubCategoryResource extends JsonResource
{
    private $isSubCategory;
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */

    public function __construct($resource,$isSubCategory)
    {
        parent::__construct($resource);
        $this->resource      = $resource;
        $this->isSubCategory = $isSubCategory;
    }

    public function toArray($request)
    {   
       
        $data['service'] = [];
        $spServices      = [];
        if($this->isSubCategory == 1){
            $category        = Category::where('id',request()->category_id)->first();
            // dd($category);
            if($request->header('X-localization')=='en'){
                // dd($this->resource[0]->categoryname);
                return [
                   /* 'isApplyUserApplicableFee'  => isset($this->resource[0]) && ($this->resource[0]->categoryname->is_apply_user_app_fee == 1) ? true : false,*/
                   'isApplyUserApplicableFee'  => isset($category->is_apply_user_app_fee) && ($category->is_apply_user_app_fee == 1) ? true : false,
                   /* 'userApplicableFeeLabel'    => isset($this->resource[0]) &&  !empty($this->resource[0]->categoryname->user_applicable_fee_name) ?  $this->resource[0]->categoryname->user_applicable_fee_name : '',*/
                    'userApplicableFeeLabel'    =>!empty($category->user_applicable_fee_name) ?  $category->user_applicable_fee_name : '',
                   /* 'fixedPrice'                => isset($this->resource[0]) && !empty($this->resource[0]->categoryname->fixed_price) ?  (double)$this->resource[0]->categoryname->fixed_price : 0,*/
                    'fixedPrice'                => !empty($category->fixed_price) ?  (double)$category->fixed_price : 0,
                   /* 'commissionPercent'         => isset($this->resource[0]) && !empty($this->resource[0]->categoryname->commission_percent) ?   (double)$this->resource[0]->categoryname->commission_percent : 0,*/
                     'commissionPercent'         => !empty($category->commission_percent) ?   (double)$category->commission_percent : 0,
                    'subCategory' => SubCategoryMultipleResource::collection($this->resource ?? [])
                ]; 
    
            }else if($request->header('X-localization')=='ar'){
 
                return [
                    /* 'isApplyUserApplicableFee'  => isset($this->resource[0]) && ($this->resource[0]->categoryname->is_apply_user_app_fee == 1) ? true : false,*/
                   'isApplyUserApplicableFee'  => isset($category->is_apply_user_app_fee) && ($category->is_apply_user_app_fee == 1) ? true : false,
                   /* 'userApplicableFeeLabel'    => isset($this->resource[0]) &&  !empty($this->resource[0]->categoryname->user_applicable_fee_name) ?  $this->resource[0]->categoryname->user_applicable_fee_name : '',*/
                    'userApplicableFeeLabel'    =>!empty($category->user_applicable_fee_name) ?  $category->user_applicable_fee_name : '',
                   /* 'fixedPrice'                => isset($this->resource[0]) && !empty($this->resource[0]->categoryname->fixed_price) ?  (double)$this->resource[0]->categoryname->fixed_price : 0,*/
                    'fixedPrice'                => !empty($category->fixed_price) ?  (double)$category->fixed_price : 0,
                   /* 'commissionPercent'         => isset($this->resource[0]) && !empty($this->resource[0]->categoryname->commission_percent) ?   (double)$this->resource[0]->categoryname->commission_percent : 0,*/
                     'commissionPercent'         => !empty($category->commission_percent) ?   (double)$category->commission_percent : 0,
                    'subCategory' => SubCategoryMultipleResource::collection($this->resource ?? [])
                ];
            }
            
        }else{
 
            $category = \App\Models\Category::where('parent_id',request()->category_id)->first();
          
            if(isset($this->resource[0])){

                if($request->header('X-localization')=='en'){
                    return [
                        'id'                        => !empty($this->resource[0]->categoryname->id)      ? $this->resource[0]->categoryname->id : '',
                        'name'                      => !empty($this->resource[0]->categoryname->name_en) ?  $this->resource[0]->categoryname->name_en : '',
                        'isApplyUserApplicableFee'  => ($this->resource[0]->categoryname->is_apply_user_app_fee == 1) ? true : false,
                        'fixedPrice'                => !empty($this->resource[0]->categoryname->fixed_price) ?  (double)$this->resource[0]->categoryname->fixed_price : 0,
                        'commissionPercent'         => !empty($this->resource[0]->categoryname->commission_percent) ?   (double)$this->resource[0]->categoryname->commission_percent : 0,
                        'userApplicableFeeLabel'    => !empty($this->resource[0]->categoryname->user_applicable_fee_name) ?  $this->resource[0]->categoryname->user_applicable_fee_name : '',
                        'subCategory'               => [],
                        'services'                  => !empty($this->resource) && isset($this->resource)? ServiceProviderSubCategoryServiceResource::collection($this->resource[0]->categoryname->servicesCategory ?? []) : []
                        // 'services'         => !empty($category['servicesForSubcategory']) ? ServiceProviderSubCategoryServiceResource::collection($category['servicesForSubcategory']->where('user_id',request()->service_provider_id)) : null,
                    ]; 
        
                }else if($request->header('X-localization')=='ar'){
                    return [
                        'id'                        => !empty($this->resource[0]->categoryname->id)      ? $this->resource[0]->categoryname->id : '',
                        'name'                      => !empty($this->resource[0]->categoryname->name_ar) ? $this->resource[0]->categoryname->name_ar : '',
                        'isApplyUserApplicableFee'  => ($this->resource[0]->categoryname->is_apply_user_app_fee == 1) ? true : false,
                        'fixedPrice'                => !empty($this->resource[0]->categoryname->fixed_price) ?  (double)$this->resource[0]->categoryname->fixed_price : 0,
                        'commissionPercent'         => !empty($this->resource[0]->categoryname->commission_percent) ?   (double)$this->resource[0]->categoryname->commission_percent : 0,
                        'userApplicableFeeLabel'    => !empty($this->resource[0]->categoryname->user_applicable_fee_name_ar) ?  $this->resource[0]->categoryname->user_applicable_fee_name_ar : '',
                        'subCategory'               => [],
                        'services'                  => !empty($this->resource) && isset($this->resource)? ServiceProviderSubCategoryServiceResource::collection($this->resource[0]->categoryname->servicesCategory ?? []) : [],
                    ];
                }

            }
            
        }            
    
    }
    
}
