<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $guarded = [];

    public function rolePermission()
    {
        return $this->hasMany('App\Models\RolePermission')->with('menus');
    }

    public function adminRolePermission()
    {
        return $this->hasMany('App\Models\RolePermission')->where('is_write',1)->orWhere('is_read',1);
    }
}
