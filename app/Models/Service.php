<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
class Service extends Authenticatable
{

  use Notifiable;
  protected $guard = 'service';
  protected $table = 'service';

  protected $fillable = [
    'name_en','name_ar','amount','description_en','description_ar',
  ];


  /*******This function is used to get service providers*************/
  public function serviceprovider(){
    return $this->hasOne('App\User','id','user_id')->where('user_type','1')->where('is_active',1)->with(['userlocation','ratingreviews','servicetime' => function($query){
      return $query->orderBy('time_duration')->min('time_duration');
    },'startingprice'=>function($q){
        return $q->orderBy('amount')->min('amount');
    },'favoritesp']);
  }


  // public function serviceprovider(){
  //   return $this->hasOne('App\User','id','user_id')->where('user_type','1')->with(['userlocation','ratingreviews','servicetime' => function($query){
  //     return $query->orderBy('time_duration')->min('time_duration');
  //   },'startingprice'=>function($q){
  //       return $q->orderBy('amount')->min('amount');
  //   },'favoritesp','timeperiodexist']);
  // }

  /*******This function is used to get categories*************/
  public function category()
  {
    return $this->hasOne('App\Models\Category','id','category_id');
  }
  /*******This function is used to get sub categories*************/
  public function subcategory()
  {
    return $this->hasOne('App\Models\Category','id','sub_category_id');
  }

  /*******This function is used to get service addons*************/
  public function serviceaddonsdata(){
    return $this->hasMany('App\Models\Serviceaddons','service_id','id')->where('is_delete','0');
  }
  /*******This function is used to get service description*************/
  public function servicedescription(){
    return $this->hasMany('App\Models\Servicedescription','service_id','id');
  }

  /*******This function is used to get vehicle*************/
  public function vehicle()
  {
    return $this->hasOne('App\Models\Vehicletype','id','vehicle_type_id');
  }

  /*************Service provider details*************/
  // public function providers()
  // {
  //   return $this->hasOne('App\User','id','user_id');
  // }
  //
  //
  //
  /*******This function is used to get service addons*************/
  public function addons()
  {
    return $this->hasMany('App\Models\Serviceaddons','service_id','id');
  }

  public function vehicleType()
  {
    return $this->hasMany('App\ServiceVehicle','service_id','id')->where('status',1)->with('vehicle');
  }

  public function serviceProviderDetails()
  {
    return $this->hasMany('App\User','id','user_id');
  }




}
