<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
class Partner extends Authenticatable
{
  use Notifiable;

  protected $table = 'partners';
  protected $guard = 'our_partners';


}
