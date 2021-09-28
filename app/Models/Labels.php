<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
class Labels extends Authenticatable
{
// class Labels extends Model
// {
  use Notifiable;
  protected $guard = 'labels';
  protected $guarded = [];

}
