<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
class Spcountrycityarea extends Authenticatable
{ 
  use Notifiable;
  protected $guarded = [];
  protected $guard = 'sp_country_city_area';
  protected $table = 'sp_country_city_area';

}
