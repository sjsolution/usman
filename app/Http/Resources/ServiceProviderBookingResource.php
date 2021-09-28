<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class ServiceProviderBookingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $serviceProderId = !empty($this->technician_id) ? \App\User::find($this->technician_id)->created_by : $this->service_provider_id;
        
        $serviceProvider = \App\User::find($serviceProderId);

        if($request->header('X-localization')=='en'){
            return [
                'id'                   => $this->id,
                'serviceProviderId'    => $serviceProvider->id,
                'serviceProviderName'  => $serviceProvider->full_name_en,
                'serviceProviderImage' => $serviceProvider->profile_pic,
                'averageRating'        => !empty($serviceProvider->ratingreviews->avg('rating')) ? $serviceProvider->ratingreviews->avg('rating') : 0,
                'totalReviews'         => $serviceProvider->rating->count(),
                'technicianId'         => $this->technician_id,
                'bookingDate'          => Carbon::parse($this->booking_date)->format('d M Y'),
                'bookingTime'          => Carbon::parse($this->booking_time)->format('h:i A'),
                'status'               => $this->status
            ];
        }else if($request->header('X-localization')=='ar'){
            return [
                'id'                   => $this->id,
                'serviceProviderId'    => $serviceProvider->id,
                'serviceProviderName'  => $serviceProvider->full_name_ar,
                'serviceProviderImage' => $serviceProvider->profile_pic,
                'averageRating'        => !empty($serviceProvider->ratingreviews->avg('rating')) ? $serviceProvider->ratingreviews->avg('rating') : 0,
                'totalReviews'         => $serviceProvider->rating->count(),
                'technicianId'         => $this->technician_id,
                'bookingDate'          => Carbon::parse($this->booking_date)->format('d M Y'),
                'bookingTime'          => $this->booking_time,
                'status'               => $this->status
            ];
        }
    }
}
