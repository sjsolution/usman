<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
class ServiceproviderTimeSlots extends Authenticatable
{
  use Notifiable;
  //protected $guarded = [];
  protected $guard = 'sp_time_slot';
  protected $table = 'sp_time_slot';
  protected $primaryKey = 'id';
  protected $fillable = ['user_id','time_slot_id','day_id','start_time', 'end_time','slot_length','buffer_length','status'];
  // public function sptimeSlotPeriods()
  // {
  //     return $this->hasMany('App\Models\SpTimeSlotPeriod');
  // }


  public function timeSlotPeriod()
  {
      return $this->hasMany('App\Models\SpTimeSlotPeriod');

  }

  public function existtimeperiodsofsp(){
    return $this->hasOne('App\Models\TimeSlotPeriod');
  }


}
