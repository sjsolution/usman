<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserReport extends Model
{
    protected $guarded = [];

    public function order()
    {
        return $this->belongsTo('\App\Models\Orders','order_id','id')->with('serviceProvider');
    }

    public function user()
    {
        return $this->belongsTo('\App\User','user_id','id');
    }
}
