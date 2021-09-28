<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
class Servicecategory extends Authenticatable
{

  use Notifiable;
  protected $table = 'service_categories';
  protected $guard = 'service_categories';

}
