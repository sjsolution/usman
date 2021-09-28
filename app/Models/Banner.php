<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
class Banner extends Authenticatable
{
    use Notifiable;
    protected $guard = 'banners';
    protected $fillable = [
        'title_en','title_ar','banner_image','description_en','description_ar','type','is_active'
    ];
}
