<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TimeSlotPeriod extends Model
{
  protected $guarded = [];

  public function timeSlot()
  {
      return $this->belongsTo('App\Models\TimeSlot');
  }
}
