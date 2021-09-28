<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
class Device extends Authenticatable
{

  use Notifiable;
  protected $table = 'users_device';
  protected $guard = 'users_device';
  protected $guarded = [];

  public function user()
  {
    return $this->belongsTo('App\User');
  }

  public function order()
  {
    return $this->belongsTo('App\Models\Orders','user_id','user_id');
  }

}
