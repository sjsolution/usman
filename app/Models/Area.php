<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
class Area extends Authenticatable
{
  use Notifiable;
  protected $table = 'city_areas';
  protected $guard = 'city_areas';

}
