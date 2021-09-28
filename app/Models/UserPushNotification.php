<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserPushNotification extends Model
{
    protected $guarded = [];

    public function specificUser()
    {
        return $this->hasMany('App\Models\SpecificUserPushNotification')->with('user');
    }
}
