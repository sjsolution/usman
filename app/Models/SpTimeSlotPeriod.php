<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SpTimeSlotPeriod extends Model
{
  protected $guarded = [];

  public function timeSlot()
  {
      return $this->belongsTo('App\Models\SpTimeSlot');
  }
}
