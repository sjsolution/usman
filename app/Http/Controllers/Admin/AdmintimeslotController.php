<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\TimeSlot;
use App\Http\Requests\TimeSlotCreateRequest;
use App\Models\Days;
use App\Models\SpTimeSlot;

class AdmintimeslotController extends Controller
{
 
    public function __construct(TimeSlot $timeSlot,Days $days,SpTimeSlot $spTimeSlot)
    {
        $this->timeSlot   = $timeSlot;
        $this->days       = $days;
        $this->spTimeSlot = $spTimeSlot;
    }


    public function create(Request $request)
    {
        $days       =  $this->days->get();
        $adminSlots = $this->timeSlot->where('is_active',1);

        return view('admin.time-slot.globletimeslot',compact('days','adminSlots'));
    }


    public function store(Request $request)
    {        
        $days     = ['sun','mon','tue','wed','thus','fri','sat'];
        $timeslot = [];
        $error    = [];

        for($i=0;$i<sizeof($days);$i++){

            $j = $i;
            $start = $days[$i].'_start_time';
            $end   = $days[$i].'_end_time';
            $day   = $days[$i];
           
            if(strtotime($request->$start) < strtotime ($request->$end)){

                $timeslot[$days[$i]]['day'] = ++$j;
                $timeslot[$days[$i]]['status']      = !empty($request->$day.'_status') ? 1 : 0;
                $timeslot[$days[$i]]['start_time']  = $request->$start;
                $timeslot[$days[$i]]['end_time']    = $request->$end;

            }else{

                if($request->has($days[$i].'_status')){
                    $error[$days[$i]] = 'End time always greater than start time';
                }else{
                    $timeslot[$days[$i]]['day'] = ++$j;
                    $timeslot[$days[$i]]['status']      = 0;
                    $timeslot[$days[$i]]['start_time']  = '09:00';
                    $timeslot[$days[$i]]['end_time']    = '09:00';
                }
                
            }
        }

        if(!empty($error)){
            return redirect()->back()->with($error)->withInput($request->all());
        }
        
        $this->timeSlot->where('is_active',1)->update(['is_active' =>0]);
        $this->spTimeSlot->where('is_active',1)->update(['is_active' =>0,'is_changed'=>1]);

        
        foreach($timeslot as $key)
        {
            $time = $this->timeSlot->create([
                'day_id'        => $key['day'],
                'start_time'    => $key['start_time'],
                'end_time'      => $key['end_time'],
                'status'        => $key['status']
            ]);
        }

        toast('Timeslot saved successfully','success')->timerProgressBar();

        return redirect()->back();

    }
}
