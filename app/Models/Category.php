<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
class Category extends Authenticatable
{

  use Notifiable;
  protected $table = 'categories';
  protected $guard = 'categories';
  
  public function subcategory()
 {

    return $this->hasMany('App\Models\Category','parent_id','id');
  }

  public function subcategorydata()
 {
    return $this->hasMany('App\Models\Category','parent_id', 'id');

  }
  public function categories()
  {
    return $this->hasOne('App\Models\Category','id','parent_id');
  }


  /***********This function is used to get userselect category wise data***************/
  public function serviecategory()
  {
    return $this->hasOne('App\Models\Serviceprovidercategory','category_id','id');
  }

  /***********This function is used to get user select sub-category wise data***************/
  public function serviesubcategory()
  {
    return $this->hasOne('App\Models\Serviceprovidersubcategory','sub_category_id','id');
  }

  public function services()
  {
    return $this->hasMany('App\Models\Service','sub_category_id','id')->where('service_type',1)->with('addons','servicedescription');
  }

  public function servicesForSubcategory()
  {
    return $this->hasMany('App\Models\Service','sub_category_id','id')->whereHas('vehicleType',function($join){
      $join->where('vehicle_type_id',request()->vehicle_type);
    })->with('addons','servicedescription');
    
  }

  public function servicesCategory()
  {
     return $this->hasMany('\App\Models\Service','category_id','id')->where('is_active','1')->where('user_id',request()->service_provider_id)
    
     ->whereHas('vehicleType',function($join){
       $join->where('vehicle_type_id',request()->vehicle_type);
     })->with('addons','servicedescription');
  }

  public function listType()
  {
      return $this->hasMany('\App\SPListRanking','category_id','id')->with('listType');
  }

}
