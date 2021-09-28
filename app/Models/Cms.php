<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
class Cms extends Authenticatable
{
  use Notifiable;
  protected $guard = 'usercms';
  protected $table = 'usercms';

}
