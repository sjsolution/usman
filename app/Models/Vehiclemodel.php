<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
class Vehiclemodel extends Authenticatable
{

  use Notifiable;
  protected $guard = 'vehicle_model';
  protected $table = 'vehicle_model';
  protected $guarded = [];

  
  public function brands()
  {
      return $this->hasOne('App\Models\Vehiclebrand','id','vehicle_brand_id');

  }

  public function manufacture()
  {
      return $this->hasMany('App\Models\Vehiclemanufacture','vehicle_model_id','id');
  }
}
