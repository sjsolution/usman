<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $guarded = [];

    public function notificationStatus()
    {
        return $this->hasMany('App\NotificationStatus');
    }

    public function orders()
    {
        return $this->belongsTo('App\Models\Orders','order_id','id');
    }
}
