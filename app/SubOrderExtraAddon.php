<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SubOrderExtraAddon extends Model
{
    protected $guarded = [];

    public function serviceAddons()
    {
        return $this->belongsTo('\App\Models\Serviceaddons','service_addon_id','id');
    }
    public function extraAddonOrderPaymentHistoryDeatils()
    {
        return $this->belongsTo('\App\SubOrderExtraAddonPaymentHistory','sub_extra_payment_history_id','id');
    }
}
