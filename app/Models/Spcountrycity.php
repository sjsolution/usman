<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
class Spcountrycity extends Authenticatable
{
  use Notifiable;
  protected $guarded = [];
  protected $guard = 'sp_country_city';
  protected $table = 'sp_country_city';



  public function coutrycitywisearea()
  {
      return $this->hasMany('App\Models\Spcountrycityarea','country_city_id','id');
  }

}
