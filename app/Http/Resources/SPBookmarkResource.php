<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SPBookmarkResource extends JsonResource
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
                'id'                   => $this->id,
                'userId'               => $this->user_id,
                'serviceProviderId'    => $this->service_provider_id,
                'isBookmarked'         => $this->is_marked
            ];

        }else if($request->header('X-localization')=='ar'){
            return [
                'id'                   => $this->id,
                'userId'               => $this->user_id,
                'serviceProviderId'    => $this->service_provider_id,
                'isBookmarked'         => $this->is_marked
            ];
        }
    }
}
