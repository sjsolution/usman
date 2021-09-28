<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
use Api;
use App\BookmarkServiceProvider;
use App\Http\Resources\BookmarkedServiceProviderListResource;
use App\Http\Resources\SPBookmarkResource;

class BookmarkController extends Controller
{ 
    public function __construct(BookmarkServiceProvider $bookmarkServiceProvider)
    {
        $this->bookmarkServiceProvider = $bookmarkServiceProvider;
    }

    public function markUnmark(Request $request)
    {
        $validator= Validator::make($request->all(),[
            'user_id'                => 'required|exists:users,id',
            'service_provider_id'    => 'required|exists:users,id',
            'action'                 => 'required|in:1,2' // 1 : Mark 2: Unmark
        ]);


        if($validator->fails()){
            return response()->json(Api::apiValidationResponse($validator->errors()->first()),422);
        }

        if($request->action == 1){

            $isExists = $this->bookmarkServiceProvider->where([
                'user_id'             => $request->user_id,
                'service_provider_id' => $request->service_provider_id,
                'is_marked'           => 1
            ])->exists();

            if($isExists){
                return response()->json(Api::apiErrorResponse('You are already marked as bookmark'),422);
            }

            $bookmark = $this->bookmarkServiceProvider->updateOrCreate([
                'user_id'             => $request->user_id,
                'service_provider_id' => $request->service_provider_id,
            ],[
                'user_id'             => $request->user_id,
                'service_provider_id' => $request->service_provider_id,
                'is_marked'           => 1
            ]);

            return response()->json(Api::apiSuccessResponse('You are successfully marked as bookmark',$bookmark));

        }else if($request->action == 2){

            $isExists = $this->bookmarkServiceProvider->where([
                'user_id'             => $request->user_id,
                'service_provider_id' => $request->service_provider_id,
                'is_marked'           => 0
            ])->exists();

            if($isExists){
                return response()->json(Api::apiErrorResponse('You are already unmarked as bookmark'),422);
            }

            $bookmark = $this->bookmarkServiceProvider->updateOrCreate([
                'user_id'             => $request->user_id,
                'service_provider_id' => $request->service_provider_id,
            ],[
                'user_id'             => $request->user_id,
                'service_provider_id' => $request->service_provider_id,
                'is_marked'           => 0
            ]);

            return response()->json(Api::apiSuccessResponse('You are successfully unmarked as bookmark',$bookmark));

        }

        
    }

    public function bookmarkedServiceProviders(Request $request)
    {
        $validator= Validator::make($request->all(),[
            'user_id'     => 'required|exists:users,id'
        ]);

        if($validator->fails()){
            return response()->json(Api::apiValidationResponse($validator->errors()->first()),422);
        }

        $bookmarList = $this->bookmarkServiceProvider->where('is_marked',1)->where('user_id',$request->user_id)->get();
        
        return response()->json(Api::apiSuccessResponse('Bookmark listing successfully fetch',BookmarkedServiceProviderListResource::collection($bookmarList)));


    }
}
