<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
class Serviceprovidersubcategory extends Authenticatable
{
    use Notifiable;
    protected $guard = 'service_provider_with_sub_category';
    protected $table = 'service_provider_with_sub_category';
    /************/
    public function subcategoryname()
    {
      //return $this->hasOne('App\Models\Category','sub_category_id','id')->with(['services']);
      return $this->hasOne('App\Models\Category','id','sub_category_id')->with(['services' => function($query) {
        //$query->whereExists('sub_category_id');
        $query->take(5);
      }]);
    }


    public function categoryname()
    {
      return $this->hasOne('App\Models\Category','id','sub_category_id');
    }

}
