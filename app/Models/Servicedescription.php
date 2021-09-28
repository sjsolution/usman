<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
class Servicedescription extends Authenticatable
{
    use Notifiable;
    protected $guard = 'service_description';
    protected $table = 'service_description';
    protected $primaryKey = 'id';
    protected $fillable = ['id','service_id', 'description_en', 'description_ar'];


}
