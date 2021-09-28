<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
class Emailtemplates extends Authenticatable
{
  use Notifiable;
  protected $table = 'email_templates';
  protected $guard = 'email_templates';


}
