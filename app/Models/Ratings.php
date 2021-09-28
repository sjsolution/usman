<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
class Ratings extends Authenticatable
{
  use Notifiable;
  protected $guard = 'rating';
  protected $table = 'rating';

  protected $guarded = [];

  public function order()
  {
    return $this->belongsTo('App\Models\Orders');
  }

  public function user()
  {
    return $this->belongsTo('App\User');
  }

  public function serviceProvider()
  {
    return $this->belongsTo('App\User','service_provider','id');
  }
}
