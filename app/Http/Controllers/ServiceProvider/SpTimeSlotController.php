<?php
namespace App\Http\Controllers\ServiceProvider;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\TimeSlot;
use App\Models\SpTimeSlot;
use App\Models\EmergencyTimeSlot;
use App\Http\Requests\SpTimeSlotRequests;
use auth;
use App\Models\TechnicianTimeSlot;

class SpTimeSlotController extends Controller
{
  public function __construct(SpTimeSlot $spTimeSlot,TechnicianTimeSlot $technicianTimeSlot)
  {
      $this->middleware('auth');
      $this->spTimeSlot = $spTimeSlot;
      $this->technicianTimeSlot = $technicianTimeSlot;
  }

  public function create(Request $request)
  {

    if(Auth::user()->setup_complete != '1' &&  Auth::user()->setup_complete == '4'){
    
      toast('Please update your timeslots first.','question')->timerProgressBar();

    }  

    $open_time = strtotime("1:00");
    $close_time = strtotime("23:59");
    $now = time();
    $output = [];

    for( $i=$open_time; $i<$close_time; $i+=300) {
      $output[] =date("H:i",$i);
    } 

    $user = Auth::user();

    $spTimeSlots = $user->providerAllTimeSlots;

    $spEmergencySlots = $user->userEmergencyTimeSlot;
    
    $adminTimeSlots = TimeSlot::with('dayname')->where(['is_active'=>'1','status'=>'1']);
  
    $bufferDuration=['10'=>'10 min','15'=>'15 min','20'=>'20 min','30'=>'30 min','60'=>'1 hour'];
    
    return view('service-providers.sp-time-slot',compact('bufferDuration','adminTimeSlots','output','spTimeSlots','spEmergencySlots'));
  
  }

  public function store(SpTimeSlotRequests $request)
  {
    
    $days     = TimeSlot::with('dayname')->where(['is_active'=>'1','status'=>'1'])->get()->pluck('dayname.name_en','dayname.id')->toArray();
    $timeCmp  = TimeSlot::with('dayname')->where(['is_active'=>'1','status'=>'1'])->select('start_time','end_time','day_id')->get()->toArray();

    $timeslot = [];
    $error    = [];

    $isEmergency    = $request->is_emergency;
  
    $k=0;

    foreach($days as $dayId => $dayName)
    {
     
      $start            = strtolower($dayName).'_start_time';
      $end              = strtolower($dayName).'_end_time';
      $breakFrom        = strtolower($dayName).'_break_from';
      $breakTo          = strtolower($dayName).'_break_to';
      $adminTimeSlotId  = strtolower($dayName).'_time_slot';
      $day              = strtolower($dayName);
      

      if($request->has($start) && $request->has($end) && $request->has(strtolower($dayName).'_break_status')){
        
        if(strtotime($request->$start) < strtotime ($request->$end)){
        
          if(strtotime($request->$breakFrom) > strtotime ($request->$breakTo)){

            $error[strtolower($dayName)] = 'Break to time always greater than from time';
         
          }else{

            if(($timeCmp[$k]['day_id'] ==  $dayId) &&  (strtotime($timeCmp[$k]['start_time']) <=  strtotime($request->$start)) && strtotime($request->$start) <= strtotime($timeCmp[$k]['end_time'])){

              if(($timeCmp[$k]['day_id'] ==  $dayId) &&  (strtotime($timeCmp[$k]['end_time']) >=  strtotime($request->$end)) &&  (strtotime($timeCmp[$k]['start_time']) <  strtotime($request->$end))){

                if(($timeCmp[$k]['day_id'] ==  $dayId) &&  (strtotime($request->$start) <=  strtotime($request->$breakFrom)) && strtotime($request->$end) >= strtotime($request->$breakFrom)){

                  if(($timeCmp[$k]['day_id'] ==  $dayId) &&  (strtotime($request->$start) <=  strtotime($request->$breakTo)) &&  (strtotime($request->$end) >  strtotime($request->$breakTo))){
                   
                    $timeslot[strtolower($dayName)]['day'] = $dayId;
                    $timeslot[strtolower($dayName)]['status']      = !empty($request->$day.'_status') ? 1 : 0;
                    $timeslot[strtolower($dayName)]['start_time']  = $request->$start;
                    $timeslot[strtolower($dayName)]['end_time']    = $request->$end;
                    $timeslot[strtolower($dayName)]['break_from']  = $request->$breakFrom;
                    $timeslot[strtolower($dayName)]['break_to']    = $request->$breakTo;
                    $timeslot[strtolower($dayName)]['time_slot_id'] = $request->$adminTimeSlotId;
                    $timeslot[strtolower($dayName)]['break_time_status']      = $request->has(strtolower($dayName).'_break_status') ? 1 : 0;

                 
                  }else{
                 
                    $error[strtolower($dayName)] = 'Break to  time should be lie between '.$request->$start.'-'.$request->$end;
                 
                  }
                
                }else{
                
                  $error[strtolower($dayName)] = 'Break from  time should be lie between '.$request->$start.'-'.$request->$end;
                }
               
              }else{

                $error[strtolower($dayName)] = 'End time should be lie between '.$timeCmp[$k]['start_time'].'-'.$timeCmp[$k]['end_time'];
              }

            }else{
             
              $error[strtolower($dayName)] = 'Start time should be lie between '.$timeCmp[$k]['start_time'].'-'.$timeCmp[$k]['end_time'];
            
            }

          }
         
        }else{

          $error[strtolower($dayName)] = 'End time always greater than start time';
        
        }

      }elseif($request->has($start) && $request->has($end)){

        if(strtotime($request->$start) < strtotime ($request->$end)){
        
            if(($timeCmp[$k]['day_id'] ==  $dayId) &&  (strtotime($timeCmp[$k]['start_time']) <=  strtotime($request->$start)) && strtotime($request->$start) <= strtotime($timeCmp[$k]['end_time'])){

              if(($timeCmp[$k]['day_id'] ==  $dayId) &&  (strtotime($timeCmp[$k]['end_time']) >=  strtotime($request->$end)) &&  (strtotime($timeCmp[$k]['start_time']) <  strtotime($request->$end))){
                   
                    $timeslot[strtolower($dayName)]['day'] = $dayId;
                    $timeslot[strtolower($dayName)]['status']      = !empty($request->$day.'_status') ? 1 : 0;
                    $timeslot[strtolower($dayName)]['start_time']  = $request->$start;
                    $timeslot[strtolower($dayName)]['end_time']    = $request->$end;
                    $timeslot[strtolower($dayName)]['break_from']  = $request->$start; //break time check
                    $timeslot[strtolower($dayName)]['break_to']    = $request->$start; //break time check
                    $timeslot[strtolower($dayName)]['time_slot_id'] = $request->$adminTimeSlotId;
                    $timeslot[strtolower($dayName)]['break_time_status']      =  0;

              }else{

                $error[strtolower($dayName)] = 'End time should be lie between '.$timeCmp[$k]['start_time'].'-'.$timeCmp[$k]['end_time'];
              }

            }else{
             
              $error[strtolower($dayName)] = 'Start time should be lie between '.$timeCmp[$k]['start_time'].'-'.$timeCmp[$k]['end_time'];
            
            }

        }else{

          $error[strtolower($dayName)] = 'End time always greater than start time';
        
        }

      }else{

        if($request->has(strtolower($dayName).'_status')){
            $error[strtolower($dayName)] = 'End time always greater than start time';
        }else{
            $timeslot[strtolower($dayName)]['day'] = $dayId;
            $timeslot[strtolower($dayName)]['status']      = 0;
            $timeslot[strtolower($dayName)]['start_time']  = '09:00';
            $timeslot[strtolower($dayName)]['end_time']    = '09:00';
            $timeslot[strtolower($dayName)]['break_from']  = '09:00';
            $timeslot[strtolower($dayName)]['break_to']    = '09:00';
            $timeslot[strtolower($dayName)]['time_slot_id'] = 0;
        }
      }

      $k++;

    }

    if(!empty($isEmergency)){
      if(strtotime($request->em_from_time) > strtotime ($request->em_to_time))
        $error['emergency'] = 'Error : Emergency to time always greater than from time';
    }


    if(!empty($error)){
      return redirect()->back()->with($error)->withInput($request->all());
    }

    $user = Auth::user();

    $this->spTimeSlot->where('user_id',Auth::user()->id)
      ->where('is_active','1')
      ->update(['is_active' =>'0']);

    $this->technicianTimeSlot->where('user_id',Auth::user()->id)
      ->where('is_active','1')
      ->update(['is_active' =>'0','is_changed'=>1]);

    $user->userEmergencyTimeSlot()->update([
      'is_active' => 0,
      'status'    => 0
    ]);
    
  
    foreach($timeslot as $key)
    {
        $time = $this->spTimeSlot->create([
            'day_id'                => $key['day'],
            'user_id'               => Auth::user()->id,
            'time_slot_id'          => $key['time_slot_id'],
            'start_time'            => $key['start_time'],
            'end_time'              => $key['end_time'],
            'buffer_length'         => $request['buffer_lenght'],
            'status'                => $key['status'],
            'break_from'            => $key['break_from'],
            'break_to'              => $key['break_to'],
            'is_active'             => '1',
            'break_time_status'     => isset($key['break_time_status']) ? $key['break_time_status'] : 0,
        ]);
        
        if(!empty($isEmergency)){

          $time->timeSlotEmergency()->create([
              'day_id'        => $key['day'],
              'user_id'       => Auth::user()->id,
              'start_time'    => $request->em_from_time,
              'end_time'      => $request->em_to_time,
              'is_active'     => 1,
              'status'        => 1
          ]);
        
        }

    }

    if($user->setup_complete !='1'){

      $user->setup_complete = '5';
      $user->save();
    
      toast('Please update the timeslots for technician','question')->timerProgressBar();

      return redirect('serviceprovider/technician');

    }

    toast('Timeslot successfully saved','success')->timerProgressBar();

    return redirect()->back();
     
  }

}
