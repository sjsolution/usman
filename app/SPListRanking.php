<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SPListRanking extends Model
{
    protected $guarded = [];

    public function listType()
    {
        return $this->belongsTo('\App\ListType');
    }
}
