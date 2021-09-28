<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $guarded = [];

    public function subMenu()
    {
        return $this->hasMany('App\Models\Menu','parent_id','id');
    }

    public function rolePermission()
    {
        return $this->hasMany('App\Models\RolePermission');
    }
}
