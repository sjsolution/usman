<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;


class Useraddress extends Authenticatable
{

  use Notifiable,SoftDeletes;

  protected $guard = 'user_address';
  protected $table = 'user_address';

  protected $dates = ['deleted_at'];

}
