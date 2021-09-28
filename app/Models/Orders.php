<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
class Orders extends Authenticatable
{
  use Notifiable;
  protected $guard = 'orders';
  protected $table = 'orders';

  protected $guarded = [];

  public function subOrder()
  {
    return $this->hasMany('\App\Models\Suborders','order_id','id')->with('addons','service','insurance');
  }

  public function transaction()
  {
    return $this->hasOne('\App\Transaction','order_id','id');
  }

  public function serviceProvider()
  {
    return $this->hasMany('App\BookedTechnician','order_id','id')->with('serviceProvider','technician');
  } 

  public function bookedServiceProvider()
  {
    return $this->hasOne('App\BookedTechnician','order_id','id')->with('serviceProvider','technician');
  } 
 
  public function orderStatus()
  {
    return $this->hasMany('App\OrderStatus','order_id','id');
  }

  public function revenue()
  {
    return $this->hasMany('App\Revenue','order_id','id');
  }

  public function user()
  {
    return $this->belongsTo('\App\User','user_id','id');
  }

  public function catgeory()
  {
    return $this->belongsTo('\App\Models\Category','category_id','id');
  }

  public function extraAddonOrder()
  {
    return $this->hasMany('\App\SubOrderExtraAddon','order_id','id')->with('serviceAddons');
  }
 
  public function extraAddonOrderPaymentHistory()
  {
    return $this->hasOne('\App\SubOrderExtraAddonPaymentHistory','order_id','id');
  }

  public function review()
  {
    return $this->hasOne('\App\UserReview','order_id','id');
  }

  public function userAddress()
  {
     return $this->hasOne('\App\Models\Useraddress','id','user_address_id');
  }

  public function rating()
  {
    return $this->hasOne('\App\Models\Ratings','order_id','id');
  }

  public function coupon()
  {
    return $this->hasOne('\App\Coupon','id','coupon_id');
  }

  public function notification()
  {
    return $this->hasMany('App\Notification','order_id','id');

  }

  public function technicianTracking()
  {
    return $this->hasMany('App\OrderTracker','order_id','id');

  }

  public function category()
  {
    return $this->belongsTo('App\Models\Category');
  }

  public function userNotification()
  {
    return $this->hasMany('App\UserNotification','order_id','id');
  }

  public function extraAddonOrdeTotal()
  {
    return $this->hasMany('\App\SubOrderExtraAddonPaymentHistory','order_id','id');
  }

  public function extraAddonOrderRequest()
  {
    return $this->hasMany('\App\SubOrderExtraAddonPaymentHistory','order_id','id');
  }
  
}
