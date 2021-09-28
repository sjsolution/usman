<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RolePermission extends Model
{
    protected $guarded = [];

    public function menus()
    {
        return $this->hasMany('App\Models\Menu','id','menu_id');
    }
}
