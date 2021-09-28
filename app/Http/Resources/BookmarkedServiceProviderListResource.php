<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BookmarkedServiceProviderListResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    { 
        $user = \App\User::find($this->service_provider_id);
 
        if($request->header('X-localization')=='en'){
            return [
                'id'               => $user->id,
                'full_name'        => $user->full_name_en,
                'profile_pic'      => !empty($user->profile_pic) ? $user->profile_pic : '',
                'rating'           => !empty($user->ratingreviews->avg('rating')) ? $user->ratingreviews->avg('rating') : 0,
                'reviews'          => $user->ratingreviews->count()
            ];
        }else if($request->header('X-localization')=='ar'){

            return [
                'id'               => $user->id,
                'full_name'        => $user->full_name_ar,
                'profile_pic'      => $user->profile_pic,
                'rating'           => !empty($user->ratingreviews->avg('rating')) ? $user->ratingreviews->avg('rating') : 0,
                'reviews'          => $user->ratingreviews->count()
            ];
        }
    }
}
