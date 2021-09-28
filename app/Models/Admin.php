<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
class Admin extends Authenticatable
{
    use Notifiable;

    protected $table = 'admins';
    protected $guard = 'admins';
    protected $fillable = [
        'name', 'email', 'password','profile_pic','mobile_number','address','is_active'
    ];
    protected $hidden = [
        'password', 'remember_token',
    ];
 
    public function role()
    {
        return $this->hasMany('App\Models\AdminRole')->with('roleAssign');
    }
}
