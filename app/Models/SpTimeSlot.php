<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
class SpTimeSlot extends Authenticatable
{
  use Notifiable;
  //protected $guarded = [];
  protected $guard = 'sp_time_slot';
  protected $table = 'sp_time_slot';
  protected $primaryKey = 'id';
  protected $fillable = ['user_id','time_slot_id','day_id','start_time', 'end_time','buffer_length','status','break_from','break_to','is_active','break_time_status'];

  public function dayname(){
      return $this->hasOne('App\Models\Days','id','day_id');
  }

  public function timeSlotEmergency()
  {
      return $this->hasOne('App\Models\EmergencyTimeSlot');
  }

  public function adminSlots()
  {
    return $this->belongsTo('App\Models\TimeSlot','time_slot_id','id');
  }

  // public function getStartTimeAttribute($value)
  // {
  //     return strtotime($value);
  // }


  // public function getEndTimeAttribute($value)
  // {
  //     return strtotime($value);
  // }

}
