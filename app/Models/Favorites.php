<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
class Favorites extends Authenticatable
{
  use Notifiable;
  protected $guard = 'favorites';
  protected $table = 'favorites';
}
