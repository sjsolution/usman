<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
class Wallet extends Authenticatable
{
  use Notifiable;
  protected $guard = 'userwallet';
  protected $table = 'userwallet';

  protected $guarded = [];


}
