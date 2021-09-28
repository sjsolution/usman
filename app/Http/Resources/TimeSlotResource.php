<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TimeSlotResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'time'     => $this['time'],
            'isActive' => 1
            // 'isActive' =>  strtotime($this['time']) >= strtotime(\Carbon\Carbon::now()->format('H:i')) ? 1 : 0
        ];
    }
}
