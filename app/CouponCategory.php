<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CouponCategory extends Model
{
    protected $guarded = [];

    public function category()
    {
        return $this->belongsTo('App\Models\Category');
    }
}
