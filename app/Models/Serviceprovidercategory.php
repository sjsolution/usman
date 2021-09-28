<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
class Serviceprovidercategory extends Authenticatable
{
    use Notifiable;
    protected $guard = 'service_provider_with_category';
    protected $table = 'service_provider_with_category';


    public function categoryname()
    {
      return $this->hasOne('App\Models\Category','id','category_id')->with(['services','listType' => function($q){
        $q->where('user_id',request()->service_provider_id);
      }]);
    }

    public function category()
    {
      return $this->hasOne('App\Models\Category','id','category_id')->with(['services' => function($query){
       
          $query->whereHas('vehicleType',function($join){
             $join->where('vehicle_type_id',request()->vehicle_type_id);
          });
      }]);
    }

    public function user()
    {
      return $this->belongsTo('\App\User');
    }
}
