<?php

namespace App\Traits;
use Illuminate\Support\Facades\Storage;
use App\User;
use App\Models\Device;
use App\Models\Days;
use App\Models\TimeSlot;

trait CommonTrait
{
  public function userexist($guest_id)
  {
    $users         = Device::where('guest_id',$guest_id)->first();

    $existinguser  = [
      'guest_id'   =>  $users['guest_id'] ?? 0,
      'user_id'    =>  $users['user_id'] ?? 0
    ];

    return $existinguser;
  }

   
  public function slotperiods($isandroid=false)
  {
    $name = "name_".app()->getLocale();
    $day=date("w")+1;
    
    // $adminTimSlots  = Days::with('admintimeslots')
    //       ->selectRaw("days.*,IF(((id + (9 - {$day})) = 8), 7, (id + (9 - {$day})) % 8) as daysOrder")
    //       ->orderBy('daysOrder','ASC')
    //       ->get();

  
  if(!empty(request()->category_id)){
    $categories = [(int)request()->category_id];
  }else{
    $categories = [3,12,15];
  }


   $adminTimSlots =  \DB::table('sp_time_slot') 
        ->join('days','days.id', '=', 'sp_time_slot.day_id')
        ->join('users as u','u.id', '=', 'sp_time_slot.user_id')
        ->join('service_provider_with_category as sc','sc.user_id', '=', 'sp_time_slot.user_id')
        ->join('categories as cat','cat.id', '=', 'sc.category_id')

        ->select('sp_time_slot.day_id','days.*', 'u.full_name_en as name',
          \DB::raw("MIN(sp_time_slot.start_time) AS min_start_time"), 
          \DB::raw("MAX(sp_time_slot.end_time) AS max_end_time"),
          \DB::raw("IF(((days.id + (9 - {$day})) = 8), 7, (days.id + (9 - {$day})) % 8) as daysOrder")
        )
        ->where('sp_time_slot.is_active',1)
        ->where('u.is_active',1)
        ->where('cat.type',1)
        ->whereIn('sc.category_id',$categories)
        ->where('sp_time_slot.status',1)
        ->groupBy('sp_time_slot.day_id')
        ->orderBy('daysOrder','ASC')
        ->get();
        if($isandroid){
        if($adminTimSlots->count()<7){
          $returnDay =  $adminTimSlots->map(function($adminTimSlots){
           return $adminTimSlots->day_id;
          })->toArray();
          $missing = array_diff([1,2,3,4,5,6,7],$returnDay);
          $data = [];
          foreach ($missing as $value) {
            $day = \DB::table('days')->where('id',$value)->first();
            $data[]=["day_id" => $value,"id" => $value,"name_en"=>$day->name_en,"name_ar"=>$day->name_ar];
           
          }
        }
       }
        // dd($data[0]);
    $adminTimeSlost =[];

    // foreach ($adminTimSlots as $key => $value) {
      
    //   $timSlots     =  [];
    //   $startTime    = strtotime ($value['admintimeslots']['start_time']);
    //   $endTime      = strtotime ($value['admintimeslots']['end_time']);
    //   $addMins      = config('timeslot.time_slot_period') * 60;
      
    //   while ($startTime < $endTime)
    //   {
    //     $timSlots[] = ['time'=>date ("H:i", $startTime)];
    //     $startTime += $addMins;
    //   }
      
    //   $adminTimeSlost[]=[
    //     'id'=>$value['id'],
    //     'name'=>$value[$name],
    //     'time_slot'=>$timSlots
    //   ];

    // }

    foreach($adminTimSlots as $key)
    {
      $timSlots     =  [];
      $startTime    = strtotime ($key->min_start_time);
      $endTime      = strtotime ($key->max_end_time);
      $addMins      = config('timeslot.time_slot_period') * 60;
      
      while ($startTime < $endTime)
      {
        $timSlots[] = ['time'=>date ("H:i", $startTime)];
        $startTime += $addMins;
      }
      
      $adminTimeSlost[]=[
        'id'=>$key->id,
        'name'=>$key->$name,
        'time_slot'=>$timSlots
      ];
        
    }
     if($isandroid && !empty($data)){
        foreach ($data as $value) {
          // dd($data);
          $adminTimeSlost[]=[
            'id'=>$value['id'],
            'name'=>$value[$name],
            'time_slot'=>[]
         ];
        }
     }

    return $adminTimeSlost;
    
  }

}
