<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
class TimeSlot extends Authenticatable
{
    use Notifiable;

  //protected $guarded = [];
  protected $guard = 'time_slot';
  protected $table = 'time_slot';
  protected $primaryKey = 'id';
  protected $fillable = ['day_id','start_time', 'end_time','status'];

  public function dayname(){
      return $this->hasOne('App\Models\Days','id','day_id');
  }



}
