<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SubOrderAddons extends Model
{
    protected $guarded = [];

    public function subOrderAddon()
    {
      return $this->belongsTo('App\Models\Serviceaddons','service_addon_id','id');
    }

}
