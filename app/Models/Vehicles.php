<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
class Vehicles extends Authenticatable
{

  use Notifiable,SoftDeletes; 
  protected $guard = 'vehicles';
  protected $table = 'vehicles';
  protected $guarded = [];


  public function vehilcesdata()
  {
    return $this->hasOne('App\Models\Vehicletype','id','vehicle_type_id');
  }

  public function brands()
  {
      return $this->hasOne('App\Models\Vehiclebrand','id','brand_id');
  }

  public function models()
  {
    return $this->hasOne('App\Models\Vehiclemodel','id','model_id');
  }

}
