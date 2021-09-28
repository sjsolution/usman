<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Vehicles;
use App\Traits\CommonTrait;



class ServiceProviderDetailResource extends JsonResource
{
    use CommonTrait;

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {  
        $guestuserdetails = $this->userexist($this->id);

        $isSubCategory = 0;

        $isBookmark = \App\BookmarkServiceProvider::where('user_id',request()->user_id)
                ->where('service_provider_id',request()->service_provider_id)
                ->where('is_marked',1)
                ->exists();



        if(!empty($guestuserdetails['guest_id'])){
           $user_vehicle_id = Vehicles::where(['user_id'=>$guestuserdetails['user_id'],'guest_id'=>$guestuserdetails['guest_id'],'is_primary'=>'1'])->first();
        }else{
           $user_vehicle_id = Vehicles::where(['user_id'=>$request->user_id,'is_primary'=>'1'])->first();
        }

        $category = \App\Models\Category::where('parent_id',request()->category_id);

        if($category->exists()){
            $spCategory    = $this->subCategoryCategoryWise;
            $isSubCategory = 1;
        }else{
            $spCategory    = $this->categoryCategoryWise;
            $isSubCategory = 0;
        } 


        if($request->header('X-localization')=='en'){
            return [
                'id'               => $this->id,
                'full_name'        => $this->full_name_en,
                'profile_pic'      => !empty($this->profile_pic) ? $this->profile_pic : '',
                'coverImages'      => !empty($this->coverimages) ? SPCoverImagesResource::collection($this->coverimages) : [],
                'about'            => $this->about,
                'rating'           => !empty($this->ratingreviews->avg('rating')) ? $this->ratingreviews->avg('rating') : 0,
                'reviews'          => $this->ratingreviews->count(),
                'is_category'      => '',
                'is_like'          => 0,  
                'isBookmarked'     => $isBookmark, 
                'date'             => isset($user_vehicle_id['service_date'])?$user_vehicle_id['service_date']:'',
                'time'             => isset($user_vehicle_id['service_time'])?$user_vehicle_id['service_time']:'',
                'location'         => isset($user_vehicle_id['location'])?$user_vehicle_id['location']:'',
                'category'         => [ new ServiceProviderSubCategoryResource($spCategory, $isSubCategory ?? []) ],
                //'category'         => ServiceProviderSubCategoryResource::collection($subCategoryCategoryWise),
            ];   
        }else if($request->header('X-localization')=='ar'){
            return [
                'id'               => $this->id,
                'full_name'        => $this->full_name_ar,
                'profile_pic'      => !empty($this->profile_pic) ? $this->profile_pic : '',
                'coverImages'      => !empty($this->coverimages) ? SPCoverImagesResource::collection($this->coverimages) : [],
                'about'            => $this->about_ar,
                'rating'           => !empty($this->ratingreviews->avg('rating')) ? $this->ratingreviews->avg('rating') : 0,
                'reviews'          => $this->ratingreviews->count(),
                'is_category'      => '',
                'is_like'          => 0,  
                'isBookmarked'     => $isBookmark, 
                'date'             => isset($user_vehicle_id['service_date'])?$user_vehicle_id['service_date']:'',
                'time'             => isset($user_vehicle_id['service_time'])?$user_vehicle_id['service_time']:'',
                'location'         => isset($user_vehicle_id['location'])?$user_vehicle_id['location']:'',
                'category'         => [ new ServiceProviderSubCategoryResource($spCategory, $isSubCategory ?? []) ],
                //'category'         => ServiceProviderSubCategoryResource::collection($subCategoryCategoryWise),
            ];
        }
    }
}
