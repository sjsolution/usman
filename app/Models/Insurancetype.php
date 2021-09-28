<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
class Insurancetype extends Authenticatable
{
  use Notifiable;
  protected $guard = 'insurancetype';
  protected $table = 'insurancetype';

} 
