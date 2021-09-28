<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
class Toprated extends Authenticatable
{
  use Notifiable;
  protected $guard = 'toprateds';
  protected $table = 'toprateds';
}
