<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\City;
use App\Http\Resources\CityResource;

class CityAreaController extends Controller
{
    public function __construct(City $city)
    {
        $this->city = $city;
    }

    public function areaList()
    {
        $cities = $this->city->with('areas')->get();
        return response()->json([
            'status'   => 1,
            'message'  => 'City areas fetch successfully',
            'data'     =>  CityResource::collection($cities)
        ]);
      
    }
}
