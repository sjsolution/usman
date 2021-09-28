<?php

namespace App\Http\Controllers\ServiceProvider;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\SpTimeSlot;
use App\User;
use Auth;
use App\Models\TechnicianTimeSlot;
use App\Http\Requests\TechnicianTimeSlotRequests;
class TechnicianTimeSlotController extends Controller
{
    public function __construct(TechnicianTimeSlot $techniciantimeSlot)
    {
        $this->middleware('auth');
        $this->techniciantimeSlot = $techniciantimeSlot;
    }

    public function create(Request $request, $id)
    {
        $userid          = Auth::user()->id;
        $technicians     = User::where('created_by',$userid)->where('id',$id)->first();
        $technicianSlots = $technicians->technicainAllTimeSlots;
        $adminTimeSlots  = SpTimeSlot::with('dayname')->where(['user_id'=>$userid,'is_active'=>'1','status'=>'1']);
        return view('technician.tech-time-slot',compact('adminTimeSlots','technicians','technicianSlots'));
    }


    public function store(TechnicianTimeSlotRequests $request ,$id)
    {
            
        $days  = SpTimeSlot::with('dayname')->where([
            'is_active'   =>   '1',
            'status'      =>   '1',
            'user_id'     =>   Auth::user()->id
        ])->get()->pluck('dayname.name_en','dayname.id')->toArray();

        $timeCmp  = SpTimeSlot::with('dayname')->where([
            'is_active'   =>   '1',
            'status'      =>   '1',
            'user_id'     =>   Auth::user()->id
        ])->select('start_time','end_time','day_id')->get()->toArray();
         
    
        $timeslot = [];
        $error    = [];
        $k        = 0;


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
                                        $timeslot[strtolower($dayName)]['break_time_status']      = 1;

                                
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

            }else if($request->has($start) && $request->has($end)){

                if(strtotime($request->$start) < strtotime ($request->$end)){


                        if(($timeCmp[$k]['day_id'] ==  $dayId) &&  (strtotime($timeCmp[$k]['start_time']) <=  strtotime($request->$start)) && strtotime($request->$start) <= strtotime($timeCmp[$k]['end_time'])){

                            if(($timeCmp[$k]['day_id'] ==  $dayId) &&  (strtotime($timeCmp[$k]['end_time']) >=  strtotime($request->$end)) &&  (strtotime($timeCmp[$k]['start_time']) <  strtotime($request->$end))){
              
                                $timeslot[strtolower($dayName)]['day'] = $dayId;
                                $timeslot[strtolower($dayName)]['status']      = !empty($request->$day.'_status') ? 1 : 0;
                                $timeslot[strtolower($dayName)]['start_time']  = $request->$start;
                                $timeslot[strtolower($dayName)]['end_time']    = $request->$end;
                                $timeslot[strtolower($dayName)]['break_from']  = $request->$start;
                                $timeslot[strtolower($dayName)]['break_to']    = $request->$start;
                                $timeslot[strtolower($dayName)]['time_slot_id'] = $request->$adminTimeSlotId;
                                $timeslot[strtolower($dayName)]['break_time_status']      = 0;

                                
                             
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
                    $timeslot[strtolower($dayName)]['break_time_status']   = 0;
                }
            }

            $k++;

        }

        if(!empty($error)){
            return redirect()->back()->with($error)->withInput($request->all());
        }
    
        $this->techniciantimeSlot->where(['is_active'=>'1','technician_id' =>$id])->update(['is_active' =>'0']);
        
        foreach($timeslot as $key)
        {
            $time = $this->techniciantimeSlot->create([
                'day_id'          => $key['day'],
                'start_time'      => $key['start_time'],
                'end_time'        => $key['end_time'],
                'user_id'         => Auth::user()->id,
                'technician_id'   => $id,
                'sp_time_slot_id' => $key['time_slot_id'],
                'status'          => $key['status'],
                'is_active'       => '1',
                'break_from'      => $key['break_from'],
                'break_to'        => $key['break_to'],
                'break_time_status'     => isset($key['break_time_status']) ? $key['break_time_status'] : 0,

            ]);

        }

        $user = Auth::user();

        if($user->setup_complete !='1'){

            $user->setup_complete = '1';
            $user->save();
          
            toast('Your profile successfully setup','success')->timerProgressBar();
      
            return redirect('home');
      
        }

        toast('Timeslot successfully saved','success')->timerProgressBar();

        return redirect()->back();

    }

}
