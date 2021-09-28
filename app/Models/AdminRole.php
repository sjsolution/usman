<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdminRole extends Model
{
    protected $guarded = [];

    public function roleAssign()
    {
        return $this->hasMany('App\Models\Role','id','role_id');
    }
}
