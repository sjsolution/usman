<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
class Suborders extends Authenticatable
{
  use Notifiable;
  protected $guard = 'suborders';
  protected $table = 'suborders';

  protected $guarded = [];

  public function addons()
  {
    return $this->hasMany('\App\SubOrderAddons','suborder_id','id')->with('subOrderAddon');
  }

  public function service()
  {
    return $this->hasOne('\App\Models\Service','id','service_id')->with('category');
  } 

  public function insurance()
  {
    return $this->hasOne('\App\Models\Insurancecardetails','id','insurance_car_id')->with('vehicle');
  }

}
