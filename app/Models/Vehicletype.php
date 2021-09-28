<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
class Vehicletype extends Authenticatable
{
  use Notifiable;
  protected $guard = 'vehicle_type';
  protected $table = 'vehicle_type';
  protected $guarded = [];

  /*********This function is used to get vehicle brands****************/
  public function brands()
  { 
      return $this->hasMany('App\Models\Vehiclebrand','vehicle_type_id','id')->with('brandmodel');
  }
  /*********This function is used to get vehicle manufactring years****************/
  public function manufacture()
  {
      return $this->hasMany('App\Models\Vehiclemanufacture','vehicle_type_id','id');
  }

}
