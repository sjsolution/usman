<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Service;
use App\User;
use App\Http\Resources\SearchResource;
use Api;
use Validator;


class SearchController extends Controller
{
    public function __construct(Service $service,User $user)
    {
        $this->service = $service;
        $this->user    = $user;
    }

    public function search(Request $request)
    {
        $validator= Validator::make($request->all(),[
            'user_id'      => 'required',
            'search_term'  => 'required'
        ]);

        if($validator->fails()){
            return response()->json(Api::apiValidationResponse($validator->errors()->first()),422);
        }

        $sericeProvider = $this->user->where('user_type','1')
            ->where('is_active',1)
            ->with(['service' => function($q) use ($request){
               $q->where('name_en', 'LIKE', '%'.$request->search_term.'%');
            }])
            ->whereHas('service',function($query) use ($request){
                $query->where('name_en', 'LIKE', '%'.$request->search_term.'%');
            })
            ->get(); 

        if($sericeProvider->count()){
            return response()->json(Api::apiSuccessResponse('Service provider details fetch successfully',SearchResource::collection($sericeProvider)),200);
        }else{
            return response()->json(Api::apiErrorResponse(__('message.notRecordFound')));
        }

    }

    
}
