<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
class Insurancecardetails extends Authenticatable
{
  use Notifiable;
  protected $guard = 'insurance_car_details';
  protected $table = 'insurance_car_details';
  protected $guarded = [];
 
  public function vehicle()
  {
    return $this->hasOne('App\Models\Vehicles','id','user_vehicle_id')->withTrashed()->with('vehilcesdata','brands','models');
  }

}
