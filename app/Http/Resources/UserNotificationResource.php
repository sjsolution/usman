<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserNotificationResource extends JsonResource
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
                'title'               => $this->title_en,
                'body'                => $this->body_en,
                'notificationType'    => $this->notification_type,
                'orderId'             => $this->order_id
            ];

        }else if($request->header('X-localization')=='ar'){

            return [
                'id'                  => $this->id,
                'title'               => $this->title_ar,
                'body'                => $this->body_ar,
                'notificationType'    => $this->notification_type,
                'orderId'             => $this->order_id
            ];
        }
    }
}
