<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SubOrderExtraAddonPaymentHistory extends Model
{
    protected $guarded = [];

    public function orders()
  {
    return $this->HasOne('\App\Models\Orders','id','order_id');
  }
}
