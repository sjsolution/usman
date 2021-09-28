<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Revenue extends Model
{
    protected $guarded = [];

    public function orders()
    {
        return $this->belongsTo('App\Models\Orders','order_id','id');
    }
}
