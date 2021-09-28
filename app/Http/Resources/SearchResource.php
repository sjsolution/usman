<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SearchResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $isBookmark = \App\BookmarkServiceProvider::where('user_id',request()->user_id)
            ->where('service_provider_id', $this->id)
            ->where('is_marked',1)
            ->exists();

        $minPrice = \App\Models\Service::where('type','<>',2)->where('user_id',$this->id)->min('amount');

        if($request->header('X-localization')=='en'){
            return [
                'id'                   => $this->id,
                'full_name'            => $this->full_name_en,
                'profile_pic'          => !empty($this->profile_pic) ? $this->profile_pic : '',
                'rating'               => !empty($this->ratingreviews->avg('rating')) ? $this->ratingreviews->avg('rating') : 0.0,
                'reviews'              => $this->ratingreviews->count(),
                'is_busy'              => 0,
                'starting_price'       => !empty($minPrice) ? (double)$minPrice : 0,
                'minimum_service_time' => !empty($this->servicetime->time_duration) ? $this->servicetime->time_duration : 0,
                'is_marked'            => $isBookmark,
                'services'             => SearchServicesResource::collection($this->service)
            ]; 

        }else if($request->header('X-localization')=='ar'){
            return [
                'id'                   => $this->id,
                'full_name'            => $this->full_name_ar,
                'profile_pic'          => !empty($this->profile_pic) ? $this->profile_pic : '',
                'rating'               => !empty($this->ratingreviews->avg('rating')) ? $this->ratingreviews->avg('rating') : 0.0,
                'reviews'              => $this->ratingreviews->count(),
                'is_busy'              => 0,
                'starting_price'       => !empty($minPrice) ? (double)$minPrice : 0,
                'minimum_service_time' => !empty($this->servicetime->time_duration) ? $this->servicetime->time_duration : 0,
                'is_marked'            => $isBookmark,
                'services'             => SearchServicesResource::collection($this->service)
            ]; 
        }
    }
}
