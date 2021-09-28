<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
class Vehiclemanufacture extends Authenticatable
{

  use Notifiable;
  protected $guard = 'vehicle_manufacuturing_year';
  protected $table = 'vehicle_manufacuturing_year';
  protected $guarded = [];

  
  public function vehicle()
  {
    return $this->hasOne('App\Models\Vehicletype','id','vehicle_type_id');
  }

  public function vehicleModel()
  {
    return $this->hasOne('App\Models\Vehiclemodel','id','vehicle_model_id');
  }
}
