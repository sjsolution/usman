<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
class Days extends Authenticatable
{
    use Notifiable;

  protected $guard = 'days';
  protected $table = 'days';
  //protected $primaryKey = 'id';
  //protected $fillable = ['day_id','start_time', 'end_time','slot_length','buffer_length','status'];

  public function admintimeslots()
  {
      return $this->hasOne('App\Models\TimeSlot','day_id','id')->where('is_active','1')->where('status','1');
  }

  public function serviceProviderTimeSlots()
  {
      return $this->hasMany('App\Models\SpTimeSlot','day_id','id')->where('is_active',1)->where('status',1);
  }

  
}
