<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TechnicianTimeSlotRequests extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
     public function rules()
     {
         return [
             // 'sun_start_time'   => 'required',
             // 'sun_end_time'     => 'required',
             // 'mon_start_time'   => 'required',
             // 'mon_end_time'     => 'required',
             // 'tues_start_time'  => 'required',
             // 'tues_end_time'    => 'required',
             // 'wed_start_time'   => 'required',
             // 'wed_end_time'     => 'required',
             // 'thurs_start_time' => 'required',
             // 'thurs_end_time'   => 'required',
             // 'fri_start_time'   => 'required',
             // 'fri_end_time'     => 'required',
             // 'sat_start_time'   => 'required',
             // 'sat_end_time'     => 'required'
         ];
     }

     public function messages()
     {
         return [
             // 'sun_start_time.required'   => 'Sunday start time cannot be blank',
             // 'sun_end_time.required'     => 'Sunday end time cannot be blank',
             // 'mon_start_time.required'   => 'Monday start time cannot be blank',
             // 'mon_end_time.required'     => 'Monday end time cannot be blank',
             // 'tues_start_time.required'  => 'Tuesday start time cannot be blank',
             // 'tues_end_time.required'    => 'Tuesday end time cannot be blank',
             // 'wed_start_time.required'   => 'Wednesday start time cannot be blank',
             // 'wed_end_time.required'     => 'Wednesday end time cannot be blank',
             // 'thurs_start_time.required' => 'Thursday start time cannot be blank',
             // 'thurs_end_time.required'   => 'Thursday end time cannot be blank',
             // 'fri_start_time.required'   => 'Friday start time cannot be blank',
             // 'fri_end_time.required'     => 'Friday end time cannot be blank',
             // 'sat_start_time.required'   => 'Saturday start time cannot be blank',
             // 'sat_end_time.required'     => 'Saturday end time cannot be blank'
         ];
     }
}
