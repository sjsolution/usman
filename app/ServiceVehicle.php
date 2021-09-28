<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ServiceVehicle extends Model
{
    protected $guarded = [];

    public function vehicle()
    {
        return $this->hasOne('\App\Models\Vehicletype','id','vehicle_type_id');
    }
}
