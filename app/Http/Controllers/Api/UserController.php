<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Useraddress;
use App\Models\Partner;
use App\Http\Resources\UserAddressesResource;
use Validator;
use Api;
use App\Http\Resources\OurPartnersResource;
use App\User;
use App\UserNotification;
use App\Http\Resources\UserProfileResource;
use App\Http\Resources\UserNotificationResource;
use Illuminate\Support\Facades\Auth;



class UserController extends Controller
{
  public function __construct(UserNotification $userNotification)
  {
    $this->userNotification = $userNotification;
  }

  public function addresses(Request $request)
  {
      $validator= Validator::make($request->all(),[
          'user_id'  => 'required|exists:users,id'
      ]);

      if($validator->fails()){
          return response()->json(['status'=>0,'message' => $validator->errors()->first()],422);
      }

      $user = User::find($request->user_id);

      $wallet_amt = 0;

      if(!empty($user)){
        $wallet_amt = $user->amount;
      }


      $addresses = Useraddress::where('user_id',$request->user_id)->where('is_active',1)->get();

      return response()->json(['status' => 1 ,'wallet_amount' => $wallet_amt,'message' => 'user addresses fetch successfully','data' => UserAddressesResource::collection($addresses)]);
  }

  public function ourparterns(Request $request)
  {
    $partner = Partner::where('is_active','1')->get();
  
    return response()->json(Api::apiSuccessResponse('Our partners records fetch successfully',OurPartnersResource::collection($partner)));
  }

  public function deleteAddress(Request $request)
  {
    $validator= Validator::make($request->all(),[
      'user_id'        => 'required|exists:users,id',
      'userAddressId'  => 'required|exists:user_address,id'
    ]);

    if($validator->fails()){
      return response()->json(Api::apiValidationResponse($validator->errors()->first()),422);
    }

    $userAddress = Useraddress::where([
      'user_id' => $request->user_id,
      'id'      => $request->userAddressId
    ]);

    if($userAddress->exists()){

      $userAddress->delete();

      $addresses = Useraddress::where('user_id',$request->user_id)->where('is_active',1)->get();

      return response()->json(['status' => 1 ,'message' => __('message.userAddressDeleted'),'data' => UserAddressesResource::collection($addresses)]);

    
    }else{

      return response()->json(Api::apiErrorResponse(__('message.notRecordFound')),422);

    }

  }

  public function editAddress(Request $request)
  {
    $validator= Validator::make($request->all(),[
      'user_address_id'  => 'required|exists:user_address,id',
      'address_type'     => 'required'
    ]);

    if($validator->fails()){
      return response()->json(Api::apiValidationResponse($validator->errors()->first()),422);
    }

    /*********0=home,1=office,2=appartment******/
    if($request->address_type == 0){
      
      $validator= Validator::make($request->all(),[
        'address_type'   => 'required',
        'block'          => 'required',
        'street'         => 'required',
        'house'          => 'required',
        'country_code'   => 'required'
      ]);
    }

    //office
    if($request->address_type == 1){
      $validator= Validator::make($request->all(),[
        'address_type'    => 'required',
        'block'           => 'required',
        'street'          => 'required',
        'office'          => 'required',
        'building'        => 'required',
        'floor'           => 'required',
        'country_code'    => 'required',
      ]);
    }

    //appartment
    if($request->address_type == 2){
        $validator= Validator::make($request->all(),[
        'address_type'      => 'required',
        'block'             => 'required',
        'street'            => 'required',
        'building'          => 'required',
        'floor'             => 'required',
        'house'             => 'required',
        'appartment_number' => 'required',
        'country_code'      => 'required',
      ]);

    } 

    if($validator->fails()){
      return response()->json(Api::apiValidationResponse($validator->errors()->first()),422);
    }

    $userAddr = Useraddress::where('id',$request->user_address_id);

    if($userAddr->exists()){

      $useraddress                       = $userAddr->first();
      $useraddress->address_type         = $request->address_type;
      $useraddress->block                = isset($request->block)?$request->block:'';
      $useraddress->street               = isset($request->street)?$request->street:'';
      $useraddress->avenue               = isset($request->avenue)?$request->avenue:'';
      $useraddress->building             = isset($request->building)?$request->building:'';
      $useraddress->house                = isset($request->house)?$request->house:'';
      $useraddress->floor                = isset($request->floor)?$request->floor:'';
      $useraddress->office               = isset($request->office)?$request->office:'';
      $useraddress->appartment_number    = isset($request->appartment_number)?$request->appartment_number:'';
      $useraddress->additional_direction = isset($request->direction)?$request->direction:'';
      $useraddress->country_code	       = isset($request->country_code)?$request->country_code:'';
      $useraddress->mobile_number	       = isset($request->mobile_number)?$request->mobile_number:'';
      $useraddress->landline_number	     = isset($request->landline)?$request->landline:'';
      $useraddress->address	             = isset($request->address)?$request->address:'';
      $useraddress->location_latitude	   = isset($request->location_latitude)?$request->location_latitude:'';
      $useraddress->location_longitude	 = isset($request->location_longitude)?$request->location_longitude:'';
      $useraddress->save();

      $userAddr = Useraddress::where('id',$request->user_address_id)->first();

      $array = [
        'block'                => 'block',
        'street'               => 'street',
        'avenue'               => 'avenue',
        'building'             => 'building',
        'floor'                => 'floor',
        'office'               => 'office',
        'house'                => 'house',
        'appartment_number'    => 'appartment_number',
        'country_code'         => 'country_code',
        'mobile_number'        => 'mobile_number',
        'landline_number'      => 'landline_number',
        'additional_direction' =>'additional_direction'
      ];

      $data[] = [
        'is_required' => 1,
        'is_type'     => 0,
        'type'        => $array['house'],
        'value'       => $userAddr->house
      ];
      

      $address['home'] = $data;

    
      $data1[] = [
        'is_required' => 1,
        'is_type'     => 0,
        'type'        => $array['building'],
        'value'       => $userAddr->building
      ];

      $data1[] = [
        'is_required'   =>  1,
        'is_type'       =>  0,
        'type'          => $array['floor'],
        'value'         => $userAddr->floor
      ];

      $data1[] = [
        'is_required'  => 1,
        'is_type'      => 0,
        'type'         => $array['office'],
        'value'        => $userAddr->office
      ];
      
      $address['office']=$data1;
    
      $data2[] = [
        'is_required'  =>  1,
        'is_type'      => 0,
        'type'         => $array['building'],
        'value'        => $userAddr->building
      ];

      $data2[] = [
        'is_required'  => 1,
        'is_type'      => 0,
        'type'         => $array['floor'],
        'value'        => $userAddr->floor
      ];

      $data2[] = [
        'is_required'  => 1,
        'is_type'      => 0,
        'type'         => $array['house'],
        'value'        => $userAddr->house
      ];

      $data2[] = [
        'is_required'  => 1,
        'is_type'      => 0,
        'type'         => $array['appartment_number'],
        'value'        => $userAddr->appartment_number
      ];
    
      $address['appartment']=$data2;
      $address['address_type']=['home','office','appartment'];

      $user = \App\User::find( $userAddr->user_id);

      $address['name']                    =  $user->full_name_en;
      $address['email']                   =  $user->email;
      $address['block']                   =  $userAddr->block;
      $address['street']                  =  $userAddr->street;
      $address['building']                =  $userAddr->building;
      $address['floor']                   =  $userAddr->floor;
      $address['house']                   =  $userAddr->house;
      $address['office']                  =  $userAddr->office;
      $address['appartment_number']       =  $userAddr->appartment_number;
      $address['direction']               =  $userAddr->additional_direction;
      $address['landline']                =  $userAddr->landline_number;
      $address['avenue']                  =  $userAddr->avenue;
      $address['country_code']            =  $userAddr->country_code;
      $address['mobile_number']           =  $userAddr->mobile_number;
      $address['address']                 =  $userAddr->address;
      $address['location_latitude']       =  $userAddr->location_latitude;
      $address['location_longitude']      =  $userAddr->location_longitude;

      return response()->json(['status'=>1,'message'=>'success','address_data'=>$address]);

      // return response()->json(Api::apiSuccessResponse('User addresses edited successfully'));


    }else{

      return response()->json(Api::apiErrorResponse('Something went wrong'),422);

    }

  }

  public function userProfile(Request $request)
  {
    $validator= Validator::make($request->all(),[
      'user_id'  => 'required|exists:users,id'
    ]);

    if($validator->fails()){
      return response()->json(Api::apiValidationResponse($validator->errors()->first()),422);
    }

    $user = User::where('id',$request->user_id)->get();

    return response()->json(Api::apiSuccessResponse('User profile successfully',UserProfileResource::collection($user)));

  }

  public function notifications(Request $request)
  {
    $validator= Validator::make($request->all(),[
      'user_id'        => 'required|exists:users,id'
    ]);

    if($validator->fails()){
      return response()->json(Api::apiValidationResponse($validator->errors()->first()),422);
    }

    $notifications =  $this->userNotification->where('user_id',$request->user_id)->where('title_en','!=','')->orderBy('id','desc')->get();

   
    if($notifications->count()){

      return response()->json(Api::apiSuccessResponse('User notification fetch successfully',UserNotificationResource::collection($notifications)));

    }else{

      return response()->json(Api::apiSuccessResponse(__('message.noRecordFound'),[]));
    
    }
  }
  
}
