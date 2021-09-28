<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    protected $guarded = [];

    public function assignedUser()
    {
        return $this->hasMany('\App\UserCoupon')->with('user');
    }

    public function assignedCategory()
    {
        return $this->hasMany('\App\CouponCategory')->with('category');
    }

    public function assignedServiceProvider()
    {
        return $this->hasMany('\App\ServiceProviderCoupon')->with('user');
    }

    public function assignedServices()
    {
        return $this->hasMany('\App\ServiceCoupon')->with('service');
    }
}
