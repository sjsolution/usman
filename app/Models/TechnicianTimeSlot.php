<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
class TechnicianTimeSlot extends Authenticatable
{
  use Notifiable;

  protected $guard = 'technician_time_slot';
  protected $table = 'technician_time_slot';
  protected $primaryKey = 'id';
  protected $fillable = ['user_id','technician_id','sp_time_slot_id','day_id','start_time','end_time','status','break_from','break_to','is_active','break_time_status'];

  public function dayname()
  {
    return $this->hasOne('App\Models\Days','id','day_id');
  }

  public function spTimeSlots()
  {
    return $this->belongsTo('App\Models\SpTimeSlot','sp_time_slot_id','id'); 
  }
}
