<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;


class User extends Authenticatable
{ 
  use HasApiTokens, Notifiable, SoftDeletes;

  protected $guard = 'users';

 
  /**
    * The attributes that should be mutated to dates.
    *
    * @var array
    */

  protected $dates = ['deleted_at'];

  /**
   * The attributes that should be hidden for arrays.
   *
   * @var array
   */

  protected $guarded = [];

  protected $hidden = ['password', 'remember_token'];

  /**
   * The attributes that should be cast to native types.
   *
   * @var array
   */
  protected $casts = [
      'email_verified_at' => 'datetime',
  ];


  /****This function is used get service provider location***/
  public function userlocation()
  {
    return $this->hasMany('App\Models\Useraddress','user_id','id')->withTrashed();
  }

  /****This function is used get service provider rating and reviews overall***/
  public function ratingreviews()
  {
    return $this->hasMany('App\Models\Ratings','service_provider','id');
  }

  /****This function is used get service provider minimum service time***/
  public function servicetime()
  {
    return $this->hasOne('App\Models\Service','user_id','id');
  }

  /****This function is used get service provider minimum service price***/
  public function startingprice()
  {
    return $this->hasOne('App\Models\Service','user_id','id');
  }

  public function favoritesp()
  {
    return $this->hasOne('App\Models\Favorites','user_id','id');
  }

  public function services()
  {
    return $this->hasMany('App\Models\Service','user_id','id')->where(['type'=>'2'])->with('serviceaddonsdata','servicedescription');
  }

  public function service()
  {
    return $this->hasMany('App\Models\Service','user_id','id')->with('serviceaddonsdata','servicedescription');
  }


  public function servicesdata()
  {
    return $this->hasMany('App\Models\Service','user_id','id')->where(['type'=>'1'])->with('serviceaddonsdata','servicedescription','subcategoryforservice');
  }

  public function insuranceDetails()
  {
    return $this->hasMany('App\Models\Insurancecardetails');
  }

  public function category()
  {
    return $this->hasMany('App\Models\Serviceprovidercategory','user_id','id')->with('categoryname');
  }

  public function spListRanking()
  {
    return $this->hasMany('App\SPListRanking')->with('listType');
  }

  public function subcategory()
  {
    return $this->hasMany('App\Models\Serviceprovidersubcategory','user_id','id');
  }


  public function providersubcategory()
  {
    return $this->hasMany('App\Models\Serviceprovidersubcategory','user_id','id')->with('subcategoryname');
  }

  public function withoutcategory(){
    //->where('type','!=','2')
    return $this->hasMany('App\Models\Service','user_id','id')->whereNotNull('sub_category_id')->orWhereNotNull('sub_category_id')->with('addons','servicedescription');
    //return $this->hasMany('App\Models\Service','user_id','id')->whereNull('sub_category_id')->orWhereNotNull('sub_category_id')->with('addons','servicedescription');
  }


  /*************This function is used to get technican active time slots**********/
  public function technicaintimeslots()
  {
    return $this->hasMany('App\Models\TechnicianTimeSlot','technician_id','id')->where('is_active','1')->where('status',1)->with('dayname');
  }


  public function providertimeslots()
  {
    return $this->hasMany('App\Models\SpTimeSlot','user_id','id')->where('is_active','1')->where('status','1')->with('dayname','adminSlots');
  }

  public function providerAllTimeSlots()
  {
    return $this->hasMany('App\Models\SpTimeSlot','user_id','id')->where('is_active','1')->with('dayname','adminSlots');
  }

  //Code by Sanu
  public function subCategoryCategoryWise()
  {
    return $this->hasMany('App\Models\Serviceprovidersubcategory','user_id','id');
  }

  public function categoryCategoryWise()
  {
    return $this->hasMany('App\Models\Serviceprovidercategory','user_id','id')->where('category_id',request()->category_id)->with('categoryname');
  }


  public function userServices()
  {
    return $this->hasMany('App\Models\Service','user_id','id')->with('serviceaddonsdata','servicedescription');
  }

  public function userTimeSlot()
  {
    return $this->hasMany('App\Models\SpTimeSlot','user_id','id')->where('is_active',1)->where('status',1);
  }

  public function wallet()
  {
    return $this->hasMany('App\Models\Wallet');
  }

  public function bookingTechnican()
  {
    return $this->hasMany('\App\BookedTechnician');
  }

  public function bookedTechnican()
  {
    return $this->hasMany('\App\BookedTechnician','technician_id','id');
  }

  public function bookedTechnicanAsServiceProviders()
  {
    return $this->hasMany('\App\BookedTechnician','service_provider_id','id');
  }
  //This code is added by anil chauhan//
  public function cityareas()
  {
    return $this->hasMany('App\Models\Spcountrycity','user_id','id')->with('coutrycitywisearea');
  } 

  public function coverimages()
  {
    return $this->hasMany('App\Models\Spcoverimages','user_id','id');
  }

  public function userEmergencyTimeSlot()
  {
    return $this->hasMany('App\Models\EmergencyTimeSlot','user_id','id')->where('is_active',1)->where('status',1);
  }

  public function rating()
  {
    return $this->hasMany('\App\Models\Ratings','service_provider','id');
  }

  public function spRatings()
  {
    return $this->hasMany('\App\SPListRanking','user_id','id');
  }

  public function spCityArea()
  {
    return $this->hasMany('\App\Spcountrycityarea');
  }

  public function deviceInfo()
  {
    return $this->hasMany('\App\Models\Device','user_id','id');
  }

  public function serviceProviderForTechnician()
  {
      return $this->hasMany('App\User','id','created_by');
  }

  public function technicainAllTimeSlots()
  {
    return $this->hasMany('App\Models\TechnicianTimeSlot','technician_id','id')->where('is_active','1')->with('dayname');
  }
  public function providerGroups()
  {
    return $this->hasMany('\App\Models\ProviderGroup','user_id','id');
  }


}
