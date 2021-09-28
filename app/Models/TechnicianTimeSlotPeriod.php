<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
class TechnicianTimeSlotPeriod extends Authenticatable
{
  use Notifiable;
  //protected $guarded = [];
  protected $guarded = [];

  public function timeSlot()
  {
      return $this->belongsTo('App\Models\TechnicianTimeSlots');
  }


}
