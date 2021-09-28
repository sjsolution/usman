<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BookedTechnician extends Model
{
    protected $guarded = [];
 
    public function technician()
    { 
        return $this->belongsTo('\App\User','technician_id','id')->with('deviceInfo');
    }

    public function serviceProvider()
    {
        return $this->belongsTo('\App\User','service_provider_id','id');
    }

    public function order()
    {
        return $this->belongsTo('\App\User','order_id','id');
    }
}
