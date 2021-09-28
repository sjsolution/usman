<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ServiceCoupon extends Model
{
    protected $guarded = [];

    public function service()
    {
        return $this->belongsTo('App\Models\Service','service_id','id');
    }
}
