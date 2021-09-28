<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Vehicletype;
use App\Models\Vehicles;
use App\Models\Useraddress;
use App\Models\Banner;
use App\Models\Category;
use App\Models\TimeSlot;
use App\Models\Vehiclebrand;
use App\Models\Vehiclemodel;
use App\Models\Vehiclemanufacture;
use Illuminate\Support\Facades\Validator;
use Response;
use App\Traits\CommonTrait;
use App\Http\Resources\TimeSlotShowResource;
use App\Http\Resources\VehicleTypeResource;
use App\Http\Resources\MyVehicleResource;
use App\Http\Resources\VehicleBrandResource;
use App\Http\Resources\VehicleModelResource;
use App\Http\Resources\VehicleManufactureResource;
use Api;
use App\Http\Resources\CouponResource;
use App\Coupon;
use App\User;

class VehicleController extends Controller
{
  use CommonTrait;

  public function __construct(Coupon $coupon)
  {
    $this->coupon = $coupon;
  }
 
  public function vehicletype(Request $request)
  {
    if($request->isMethod('post')){
      $name = "name_".app()->getLocale();
      $query = new Vehicles();
      $query = $query->newQuery();
      $userdetails = $this->userexist($request->user_id);
      if(!empty($userdetails['guest_id'])){
         $userid = $userdetails['guest_id'];
         $query->where(['user_id'=>$userdetails['user_id'],'guest_id'=>$userdetails['guest_id']]);
      }else{
         $userid = $request->user_id;
         $query->where(['user_id'=>$request->user_id]);
      }
      $query->with('brands','models');
      $myvehicles = $query->where(['is_active'=>1,'is_delete'=>0])->get();
      $addedvehicles = [];
      if(!empty($myvehicles)){
        foreach ($myvehicles as $myvehicle) {
          $addedvehicles[]=[
                    'id'=>$myvehicle['id'],
                    'user_id'=>$userid,
                    'vehicle_type_id'=>$myvehicle['vehicle_type_id'],
                    'brand_id'=>$myvehicle['brand_id'],
                    'model_id'=>$myvehicle['model_id'],
                    'brand_name'=>$myvehicle['brands'][$name],
                    'model_number'=>$myvehicle['models'][$name],
                    'registration_number'=>$myvehicle['registration_number'],
                    'year_of_manufacture'=>$myvehicle['year_of_manufacture'],
                    'vehicle_value' => $myvehicle['vehicle_value']
                  ];
                }
      }
      $vehicleTypes = Vehicletype::with('brands','manufacture')->where(['is_active'=>1])->get();
      if($vehicleTypes){
        $vehicleData=[];
        foreach ($vehicleTypes as $vehicleType) {
          $brandData=[];
          foreach ($vehicleType['brands'] as $key => $brandvalue) {
            $modelData=[];
            foreach ($brandvalue['brandmodel'] as $key => $modelvalue) {
              $modelData[]=['id'=>$modelvalue['id'],'vehicle_brand_id'=>$modelvalue['vehicle_brand_id'],'name'=>$modelvalue[$name]];
            }
            $brandData[]=['id'=>$brandvalue['id'],'vehicle_type_id'=>$brandvalue['vehicle_type_id'],'name'=>$brandvalue[$name],'models'=>$modelData];
          }
          $manufacturingData=[];
          foreach ($vehicleType['manufacture'] as $manufactureyears) {
            $actualyear= range(date($manufactureyears['from_year']),date($manufactureyears['to_year']));
            $manfacturingyears=[];
            foreach ($actualyear as $returnyear) {
              $manfacturingyears[] =['date'=>$returnyear];
            }
            $manufacturingData[]=['id'=>$manufactureyears['id'],
                                  'vehicle_type_id'=>$manufactureyears['vehicle_type_id'],
                                  'date'=>$manfacturingyears
                                ];
          }
          $vehicleData[]=[
            'id'=>$vehicleType->id,
            'name'=>$vehicleType->$name,
            'image'=>isset($vehicleType->image)?$vehicleType->image:'',
            'vehicle_brand'=>$brandData,
            'manufacture_year'=>$manufacturingData
          ];
        }
        $allvehicledetails = ['added_vehicles'=>$addedvehicles,'vehicle_type'=>$vehicleData];
        return response()->json(['status'=>1,'message' =>'success','vehicle_daata'=>$allvehicledetails]);
      }else{
        return response()->json(['status'=>0,'message' =>__('message.notRecordFound')]);
      }
    }
  }

  public function createvehicle(Request $request)
  {
    if($request->isMethod('post')){
      $validator= Validator::make($request->all(),[
        'user_id'             => 'required',
        'vehicle_type'        => 'required|exists:vehicle_type,id',
        'model_id'            => 'required',
        'brand_id'            => 'required|exists:vehicle_brand,id',
        'registration_number' => 'required',
        'manufacture'         => 'required',
      ]);
      if($validator->fails()){
        return response()->json(['status'=>0,'message' => $validator->errors()->first()]);
      }
      $vehicles = new Vehicles();
      $userdetails = $this->userexist($request->user_id);
      if(!empty($userdetails['guest_id'])){
        $vehicles->user_id = $userdetails['user_id'];
        $vehicles->guest_id = $userdetails['guest_id'];
      }else{
        $vehicles->user_id = $request->user_id;
      }
      $vehicles->vehicle_type_id = $request->vehicle_type;
      $vehicles->model_id = isset($request->model_id)?$request->model_id:'';
      $vehicles->brand_id = isset($request->brand_id)?$request->brand_id:'';
      $vehicles->registration_number = isset($request->registration_number)?$request->registration_number:'';
      $vehicles->year_of_manufacture = isset($request->manufacture)?$request->manufacture:'';
      $vehicles->is_active    = 1;
      $vehicles->is_primary   = 0;
      $vehicles->save();
      return response()->json(['status'=>1,'message' =>__('message.userVehicleAdded')]); 
    }
  } 

  public function addaddress(Request $request)
  {
  
    if($request->isMethod('post')){
      /*********0=home,1=office,2=appartment******/
      if($request->address_type == 0){ 

        if($request->header('X-localization')=='ar'){

          $validator= Validator::make($request->all(),[
            'user_id'        => 'required|exists:users,id',
            'address_type'   => 'required',
            'block'          => 'required',
            'street'         => 'required',
            // 'منزل'           => 'required',
            'country_code'   => 'required',
            // 'mobile_number'  => 'required',
          ]);

          $request->house = $request->منزل ?? $request->house;

        }else{
          $validator= Validator::make($request->all(),[
            'user_id'        => 'required|exists:users,id',
            'address_type'   => 'required',
            'block'          => 'required',
            'street'         => 'required',
            'house'          => 'required',
            'country_code'   => 'required',
            // 'mobile_number'  => 'required',
          ]);
        }
 
      }
      //office
      if($request->address_type == 1){

        if($request->header('X-localization')=='ar'){

          $validator= Validator::make($request->all(),[
            'user_id'         => 'required|exists:users,id',
            'address_type'    => 'required',
            'block'           => 'required',
            'street'          => 'required',
            // 'مكاتب'           => 'required',
            // 'بناء'            => 'required',
             // 'أرضية'          => 'required',
            'country_code'    => 'required',
            // 'mobile_number'   => 'required',
          ]);

          // $request->office    = $request->مكاتب;
          // $request->floor     = $request->أرضية;
          // $request->building  = $request->بناء;

        }else{

          $validator= Validator::make($request->all(),[
            'user_id'         => 'required|exists:users,id',
            'address_type'    => 'required',
            'block'           => 'required',
            'street'          => 'required',
            'office'          => 'required',
            'building'        => 'required',
            'floor'           => 'required',
            'country_code'    => 'required',
            // 'mobile_number'   => 'required',
          ]);


        }

        
      }

      //appartment
      if($request->address_type == 2){

        if($request->header('X-localization')=='ar'){

          $validator= Validator::make($request->all(),[
            'user_id'           => 'required|exists:users,id',
            'address_type'      => 'required',
            'block'             => 'required',
            'street'            => 'required',
            // 'بناء'          => 'required',
            // 'أرضية'             => 'required',
            // 'house'             => 'required',
            // 'شقة_لا' => 'required',
            'country_code'      => 'required',
            // 'mobile_number'     => 'required',
          ]);

          $request->floor             = $request->أرضية ?? $request->floor;
          $request->building          = $request->بناء ?? $request->building;
          $request->appartment_number = $request->شقة_لا ?? $request->appartment_number;


        }else{

          $validator= Validator::make($request->all(),[
            'user_id'           => 'required|exists:users,id',
            'address_type'      => 'required',
            'block'             => 'required',
            'street'            => 'required',
            'building'          => 'required',
            'floor'             => 'required',
            // 'house'             => 'required',
            'appartment_number' => 'required',
            'country_code'      => 'required',
            // 'mobile_number'     => 'required',
          ]);
        }
         

      } 

      \Log::info($request->all());

      if($validator->fails()){
        return response()->json(['status'=>0,'message' => $validator->errors()->first()]);
      }
 
      $useraddress                       = new Useraddress;
      $useraddress->user_id              = $request->user_id;
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
      $useraddress->area                 = $request->area ?? null;
      $useraddress->save();

      return response()->json(['status'=>1,'message' =>'success']);
    }
  }

  public function dashboard(Request $request)
  {
    
    $validator= Validator::make($request->all(),[
      'user_id'=>'required',
    ]);

    if($validator->fails()){
      return response()->json(['status'=>0,'message' => $validator->errors()->first()]);
    }

    $title = "title_".app()->getLocale();
    $name  = "name_".app()->getLocale();
    $description = "description_".app()->getLocale();
    $banners = Banner::where(['type'=>2,'is_active'=>'0'])->get();
    $bannerData=[];
    
    if($request->user_id){
      $user = User::where('id',$request->user_id)->first();
      
      if(!empty($user)){
        $user->is_language = $request->header('X-localization');
        $user->save();
      }
        
    }

    $banner_image = 'banner_image';
    if(app()->getLocale() == 'ar'){
      $banner_image = 'banner_image_ar';
    }
    foreach ($banners as $banner) {
      $bannerData[]=[
        'id'=>$banner->id,
        'title'=>isset($banner->$title)?$banner->$title:'',
        'description'=>isset($banner->$description)?$banner->$description:'',
        'type'=>$banner->type,
        'image'=>isset($banner->$banner_image)?$banner->$banner_image :''
      ];
    }

    $categories = Category::with('subcategory')->where(['parent_id'=>0])->get();
    $categoryData=[];
    $range = \App\RangeCoverage::where('status',1)->first();
      
    foreach ($categories as $category) {

      if($category->subcategory->count()){
        $flag = 1;
      }else{
        $flag = 0;
      }

      $categoryData[]=[
        'id'                       => isset($category->id)?$category->id:'',
        'name'                     => isset($category->$name)?$category->$name:'',
        'image'                    => isset($category->image)?$category->image:'',
        'type'                     => isset($category->type)?$category->type:'',
        'is_subcategory'           => $flag,
        'startRange'               => (!empty($range) && $category->type == 2 ) ? $range->start_range : 0,
        'endRange'                 => (!empty($range) && $category->type == 2 ) ? $range->end_range : 0,
        'isApplyUserApplicableFee' => ($category->is_apply_user_app_fee == 1) ? true : false,
        'fixedPrice'               => isset($category->fixed_price) ? $category->fixed_price: 0,
        'commissionPercent'        => isset($category->commission_percent)?$category->commission_percent:0,
        'userApplicableFeeLabel'   => isset($category->user_applicable_fee_name) && app()->getLocale() == "en" ? $category->user_applicable_fee_name: $category->user_applicable_fee_name_ar,
      ];

    }

    $coupon = $this->coupon->where('status',1)->where('is_display',1)->get();

    $resultdata = [
      'banner'    => $bannerData,
      'category'  => $categoryData,
      'coupon'    => CouponResource::collection($coupon)
    ];

    return response()->json([
      'status'     => 1 , 
      'message'    => 'success',
      'dashboard'  => $resultdata
    ]);
    
  }

  public function getsubcategory(Request $request)
  {
    if($request->isMethod('post')){
        $validator= Validator::make($request->all(),[
          'category_id'=>'required',
        ]);
      if($validator->fails()){
        return response()->json(['status'=>0,'message' => $validator->errors()->first()]);
      }
      $name = "name_".app()->getLocale();
      $categories = Category::with('categories')->where(['parent_id'=>$request->category_id])->get();
      $categoryData=[];
      $type='';
      $categoriesname='';
      foreach ($categories as $category) {
        $categoriesname=isset($category['categories'][$name])?$category['categories'][$name]:'';
        $type=isset($category['categories']['type'])?$category['categories']['type']:'';
        $categoryData[]=['id'=>isset($category->id)?$category->id:'','name'=>isset($category->$name)?$category->$name:'','image'=>isset($category->image)?$category->image:''];
      }
      return response()->json(['status'=>1,'message' =>'success','category_name'=>$categoriesname,'type'=>$type,'sub_category'=>$categoryData]);
    }
  }

    
  public function vehicleandtimeslot(Request $request)
  { 
    
    $validator= Validator::make($request->all(),[
      'user_id'     => 'required',
      'category_id' => 'exists:categories,id'
    ]);

    if($validator->fails()){
      return response()->json(['status'=>0,'message' => $validator->errors()->first()]);
    }

    $vehicleTypes = Vehicletype::where(['is_active'=>1])->get();

     $timeslotData = $this->slotperiods();

    $query = new Vehicles();

    $query = $query->newQuery();

    $userdetails = $this->userexist($request->user_id);

    if(!empty($userdetails['guest_id'])){
        $userid = $userdetails['guest_id'];
        $query->where(['user_id'=>$userdetails['user_id'],'guest_id'=>$userdetails['guest_id']]);
    }else{
        $userid = $request->user_id;
        $query->where(['user_id'=>$request->user_id]);
    }

    $query->with('brands','models');

    $myvehicles = $query->where(['is_active'=>1,'is_delete'=>0])->get();
        
    return response()->json([
      'status'            => 1,
      'message'           => 'success',
      'vehicle_time_slot' =>  [
        'my_vehicle'     => MyVehicleResource::collection($myvehicles),
        // 'vehicle_type'   => VehicleTypeResource::collection($vehicleTypes),
        'time_slot'      => TimeSlotShowResource::collection(collect($timeslotData)),
        'lastCarType'    => 1
      ]
    ]);
      
  }
  // code by sanjiv for ankit
   public function newvehicleandtimeslot(Request $request)
  { 
    
    $validator= Validator::make($request->all(),[
      'user_id'     => 'required',
      'category_id' => 'exists:categories,id'
    ]);

    if($validator->fails()){
      return response()->json(['status'=>0,'message' => $validator->errors()->first()]);
    }

    $vehicleTypes = Vehicletype::where(['is_active'=>1])->get();

     $timeslotData = $this->slotperiods(true);

    $query = new Vehicles();

    $query = $query->newQuery();

    $userdetails = $this->userexist($request->user_id);

    if(!empty($userdetails['guest_id'])){
        $userid = $userdetails['guest_id'];
        $query->where(['user_id'=>$userdetails['user_id'],'guest_id'=>$userdetails['guest_id']]);
    }else{
        $userid = $request->user_id;
        $query->where(['user_id'=>$request->user_id]);
    }

    $query->with('brands','models');

    $myvehicles = $query->where(['is_active'=>1,'is_delete'=>0])->get();
        
    return response()->json([
      'status'            => 1,
      'message'           => 'success',
      'vehicle_time_slot' =>  [
        'my_vehicle'     => MyVehicleResource::collection($myvehicles),
        // 'vehicle_type'   => VehicleTypeResource::collection($vehicleTypes),
        'time_slot'      => TimeSlotShowResource::collection(collect($timeslotData)),
        'lastCarType'    => 1
      ]
    ]);
      
  }

  //by kajal
  public function listofvehicleandtype(Request $request)
  { 
    
    $validator= Validator::make($request->all(),[
      'user_id'    =>  'required'
    ]);

    if($validator->fails()){
      return response()->json(['status'=>0,'message' => $validator->errors()->first()]);
    }

    $vehicleTypes = Vehicletype::where(['is_active'=>1])->get(); //remove sport brand

    $timeslotData = $this->slotperiods();

    $query = new Vehicles();

    $query = $query->newQuery();

    $userdetails = $this->userexist($request->user_id);

    if(!empty($userdetails['guest_id'])){
        $userid = $userdetails['guest_id'];
        $query->where(['user_id'=>$userdetails['user_id'],'guest_id'=>$userdetails['guest_id']]);
    }else{
        $userid = $request->user_id;
        $query->where(['user_id'=>$request->user_id]);
    }

    $query->with('brands','models');

    $myvehicles = $query->where(['is_active'=>1,'is_delete'=>0])->get();
      
    return response()->json([
      'status'            => 1,
      'message'           => 'success',
      'vehicle_time_slot' =>  [
        'my_vehicle'     => MyVehicleResource::collection($myvehicles),
        'vehicle_type'   => VehicleTypeResource::collection($vehicleTypes),
       // 'time_slot'      => TimeSlotShowResource::collection(collect($timeslotData))
      ]
    ]);
      
  }
  public function getDtailByType(Request $request){
    try{
      $validator= Validator::make($request->all(),[
      'type'    =>  'required|in:0,1,2,3',
      'related_id' => 'required'
      ]);

      if($validator->fails()){
        return response()->json(['status'=>0,'message' => $validator->errors()->first()]);
      }

      $request->merge(['detail_by_type' => 1]);
      switch ($request->type) {
        case 0:
          $vehicleTypes = Vehicletype::where(['is_active'=>1])->get();
          return response()->json(Api::apiSuccessResponse('Record fetch successfully',VehicleTypeResource::collection($vehicleTypes)));
          break;
        case 1:
          $brand = Vehiclebrand::where(['is_active'=>1])->where('vehicle_type_id',$request->related_id)->get();
          return response()->json(Api::apiSuccessResponse('Record fetch successfully',VehicleBrandResource::collection($brand)));
          // VehicleBrandResource::collection($this->brands))
          break;
        case 2:
            
        $model = Vehiclemodel::where(['is_active'=>1])->where('vehicle_brand_id',$request->related_id)->get();
          return response()->json(Api::apiSuccessResponse('Record fetch successfully',VehicleModelResource::collection($model)));
          break;
        case 3:
           $Year = Vehiclemanufacture::where(['is_active'=>1])->where('vehicle_model_id',$request->related_id)->get();
          return response()->json(Api::apiSuccessResponse('Record fetch successfully',VehicleManufactureResource::collection($Year)));
          break;
        
        default:
          # code...
          break;
      }
    }catch(\Exception $e){
      throw $e;
    }
  }

  public function deleteUserVehicle(Request $request)
  {
    $validator= Validator::make($request->all(),[
      'user_id'        => 'required|exists:users,id',
      'userVehicleId'  => 'required|exists:vehicles,id'
    ]);

    if($validator->fails()){
      return response()->json(Api::apiValidationResponse($validator->errors()->first()),422);
    }

    $userVehicle = Vehicles::where([
      'user_id' => $request->user_id,
      'id'      => $request->userVehicleId
    ]);

    if($userVehicle->exists()){
 
      $userVehicle->delete();

      $query = Vehicles::query();

      $userdetails = $this->userexist($request->user_id);

      if(!empty($userdetails['guest_id'])){
          $userid = $userdetails['guest_id'];
          $query->where(['user_id'=>$userdetails['user_id'],'guest_id'=>$userdetails['guest_id']]);
      }else{
          $userid = $request->user_id;
          $query->where(['user_id'=>$request->user_id]);
      }
      
      $vehicles = $query->with('brands','models')->where(['is_active'=>1,'is_delete'=>0])->get();
      
      return response()->json(Api::apiSuccessResponse('Vehicle deleted successfully',MyVehicleResource::collection($vehicles)));
    
    }else{

      return response()->json(Api::apiErrorResponse(__('message.notRecordFound')),422);

    }
  }

}
