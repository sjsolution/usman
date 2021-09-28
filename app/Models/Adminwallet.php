<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
class Adminwallet extends Authenticatable
{
  use Notifiable;
  protected $table = 'adminwallet';
  protected $guard = 'adminwallet';

}
