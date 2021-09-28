<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
class AdminTimeSlot extends Authenticatable
{
  use Notifiable;
  protected $table = 'admin_time_slot';
  protected $guard = 'admin_time_slot';
}
