<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
class Providercategory extends Authenticatable
{
  use Notifiable;
  protected $guard = 'service_provider_with_category';
  protected $table = 'service_provider_with_category';

}
