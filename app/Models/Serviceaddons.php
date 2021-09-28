<?php

namespace App\Models;

  use Illuminate\Database\Eloquent\Model;
  use Illuminate\Notifications\Notifiable;
  use Illuminate\Foundation\Auth\User as Authenticatable;
  class Serviceaddons extends Authenticatable
  {

    use Notifiable;
    protected $guard = 'service_addons';
    protected $table = 'service_addons';

    public function subOrderAddon()
    {
      return $this->hasMany('App\SubOrderAddons','service_addon_id','id');
    }

    public function subOrderExtraAddon()
    {
      return $this->hasMany('App\SubOrderExtraAddon','service_addon_id','id');
    }

  
}
