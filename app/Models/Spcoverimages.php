<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
class Spcoverimages extends Authenticatable
{
  use Notifiable;
  //protected $guarded = [];
  protected $guard = 'sp_cover_images';
  protected $table = 'sp_cover_images';

}
