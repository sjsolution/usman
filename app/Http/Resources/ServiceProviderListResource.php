<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ServiceProviderListResource extends JsonResource
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
 
        if($request->header('X-localization')=='en'){

            return [
                'id'                   => $this->id,
                'full_name'            => $this->full_name_en,
                'profile_pic'          => !empty($this->profile_pic) ? $this->profile_pic : '',
                'rating'               => !empty($this->sp_rating) ? (double)$this->sp_rating : 0,
                'reviews'              => $this->ratingreviews->count(),
                'is_busy'              => 0,
                'starting_price'       => !empty($this->min_price) ? (double)$this->min_price : 0.0,
                'minimum_service_time' => !empty($this->servicetime->time_duration) ? $this->servicetime->time_duration : 0,
                'is_marked'            => $isBookmark,
                'is_available'         => $this->is_available,
            ]; 

        }else if($request->header('X-localization')=='ar'){
            
            return [
                'id'                   => $this->id,
                'full_name'            => $this->full_name_ar,
                'profile_pic'          => !empty($this->profile_pic) ? $this->profile_pic : '',
                'rating'               => !empty($this->sp_rating) ? (double)$this->sp_rating : 0,
                'reviews'              => $this->ratingreviews->count(),
                'is_busy'              => 0,
                'starting_price'       => !empty($this->min_price) ? (double)$this->min_price : 0.0,
                'minimum_service_time' => !empty($this->servicetime->time_duration) ? $this->servicetime->time_duration : 0,
                'is_marked'            => $isBookmark,
                'is_available'         => $this->is_available,
                
            ];
        }
    }
}
