<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
class Vehiclebrand extends Authenticatable
{

  use Notifiable;
  protected $guard = 'vehicle_brand';
  protected $table = 'vehicle_brand';
  protected $guarded = [];


  /*****************This function is used to get vehicle brand models******************/
  public function brandvehicle()
  {
      return $this->hasOne('App\Models\Vehicletype','id','vehicle_type_id');
  }
  /*****************This function is used to get all vehicle brand models******************/
  public function brandmodel()
  {
        return $this->hasMany('App\Models\Vehiclemodel','vehicle_brand_id','id');
  }

}
