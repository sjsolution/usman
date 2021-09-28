<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Labels;
use App\Models\Banner;
use App\Models\Area;
use Illuminate\Support\Facades\Validator;
use Api;
use App\Models\Useraddress;
use App\User;

class LabelsController extends Controller
{
  public function getLanguage(Request $request)
  {
    $name = "name_".app()->getLocale();
  
    $token = auth('api')->user();
    if($token){
      $user = User::where('id',$token->id)->first();
      $user->is_language = $request->header('X-localization');
      $user->save();
    }
   

    $awsdetails=[
      'AWS_ACCESS_KEY_ID'=>env('AWS_ACCESS_KEY_ID'),
      'AWS_SECRET_ACCESS_KEY'=>env('AWS_SECRET_ACCESS_KEY'),
      'AWS_DEFAULT_REGION'=>env('AWS_DEFAULT_REGION'),
      'AWS_BUCKET'=>env('AWS_BUCKET'),
      'AWS_URL'=>env('AWS_URL'),
    ];
     
      $labelname = $name.' as name';
      $labels   = Labels::select('id','label_key',$labelname)->where('type','0')->get();
      return response()->json(['status'=>1,'message'=>'success','s3bucket_details'=>$awsdetails,'label'=>$labels]);
  }

  public function getLanguagefortechnician(Request $request)
  {

    $token = auth('api')->user();
    if($token){
      $user = User::where('id',$token->id)->first();
      $user->is_language = $request->header('X-localization');
      $user->save();
    }
    $awsdetails=[
      'AWS_ACCESS_KEY_ID'=>env('AWS_ACCESS_KEY_ID'),
      'AWS_SECRET_ACCESS_KEY'=>env('AWS_SECRET_ACCESS_KEY'),
      'AWS_DEFAULT_REGION'=>env('AWS_DEFAULT_REGION'),
      'AWS_BUCKET'=>env('AWS_BUCKET'),
      'AWS_URL'=>env('AWS_URL'),
    ];
      $name = "name_".app()->getLocale();
      $labelname = $name.' as name';
      $labels   = Labels::select('id','label_key',$labelname)->where('type','1')->get();
      $lbs = [];
      foreach($labels as $key=>$values){
        $lbs[$values['label_key']] = $values['name'];
      }
      return response()->json(['status'=>1,'message'=>'success','s3bucket_details'=>$awsdetails,'label'=>$lbs]);
  }

  public function getBanners(Request $request)
  {
      $title = "title_".app()->getLocale();
      $description = "description_".app()->getLocale();
      $banners   = Banner::select('id',$title,$description,'banner_image')->where(['type'=>'1','is_active'=>'0'])->get();
      $bannnerdata=[];
      foreach ($banners as $banner) {
        $bannnerdata[]=[
                    'id'=>$banner['id'],
                    'title'=>$banner[$title],
                    'description'=>$banner[$description],
                    'image'=>$banner['banner_image'],
                    ];
      }
      return response()->json(['status'=>1,'message'=>'success','banner'=>$bannnerdata]);
  }

  public function getareas(Request $request)
  {
    if($request->isMethod('get')){

    $name = "name_".app()->getLocale();
    $areas   = Area::get();
    $areadata=[];
    foreach ($areas as $area) {
      $areadata[]=[
                'id'=>$area['id'],
                'name'=>$area[$name],
                ];
    }

      return response()->json(['status'=>1,'message'=>'success','area_list'=>$areadata]);
    }
  }

  public function addressType(Request $request)
  {
     
    $validator= Validator::make($request->all(),[
      'action'     => 'required|in:1,2'
    ]);

    if($validator->fails()){
      return response()->json(Api::apiValidationResponse($validator->errors()->first()),422);
    }

    
    //for add new address
    if($request->action == 1){
      $array = [
        'block'                => ($request->header('X-localization')=='en') ? 'block'  : 'منع' ,
        'street'               => ($request->header('X-localization')=='en') ? 'street' : 'شارع' ,
        'avenue'               => ($request->header('X-localization')=='en') ? 'avenue' : 'السبيل',
        'building'             => ($request->header('X-localization')=='en') ? 'building' : 'بناء',
        'floor'                => ($request->header('X-localization')=='en') ? 'floor'    : 'أرضية',
        'office'               => ($request->header('X-localization')=='en') ? 'office' : 'مكاتب',
        'house'                => ($request->header('X-localization')=='en') ? 'house' : 'منزل',
        'appartment_number'    => ($request->header('X-localization')=='en') ? 'appartment_number' : 'شقة_لا',
        'country_code'         => ($request->header('X-localization')=='en') ? 'country_code' : 'الرقم الدولي',
        // 'mobile_number'        => 'mobile_number',
        'landline_number'      => ($request->header('X-localization')=='en') ? 'landline_number' : 'رقم الهاتف الثابت',
        'additional_direction' => ($request->header('X-localization')=='en') ? 'additional_direction' : 'اتجاه إضافي'
      ];

      $data[] = [
        'is_required' => 1,
        'is_type'     => 0,
        'type'        => $array['house'],
        'value'       => ''
      ];

      $address['home'] = $data;

    
      $data1[] = [
        'is_required' => 1,
        'is_type'     => 0,
        'type'        => $array['building'],
        'value'       => ''
      ];

      $data1[] = [
        'is_required'   =>  1,
        'is_type'       =>  0,
        'type'          => $array['floor'],
        'value'         => ''
      ];

      $data1[] = [
        'is_required'  => 1,
        'is_type'      => 0,
        'type'         => $array['office'],
        'value'        => ''
      ];
     
      $address['office']=$data1;

      
   
      $data2[] = [
        'is_required'  =>  1,
        'is_type'      => 0,
        'type'         => $array['building'],
        'value'        => ''
      ];

      $data2[] = [
        'is_required'  => 1,
        'is_type'      => 0,
        'type'         => $array['floor'],
        'value'        => ''
      ];

      // $data2[] = [
      //   'is_required'  => 1,
      //   'is_type'      => 0,
      //   'type'         => $array['house'],
      //   'value'        => ''
      // ];

      $data2[] = [
        'is_required'  => 1,
        'is_type'      => 0,
        'type'         => $array['appartment_number'],
        'value'        => ''
      ];
    
      $address['appartment']=$data2;

      if($request->header('X-localization')=='en'){

        $address['address_type']=['home','office','appartment'];

      }else{

        $address['address_type']=['الصفحة الرئيسية','مكاتب','شقة'];

      }
     

      // $address['name']                    = '';
      // $address['email']                   = '';
      $address['block']                   = '';
      $address['street']                  = '';
      $address['building']                = '';
      $address['floor']                   = '';
      $address['house']                   = '';
      // $address['office']                  = '';
      $address['appartment_number']       = '';
      $address['direction']               = '';
      $address['landline']                = '';
      $address['avenue']                  = '';
      $address['country_code']            = '';
      // $address['mobile_number']           = '';
      $address['address']                 = '';
      $address['location_latitude']       = '';
      $address['location_longitude']      = '';
     
      return response()->json(['status'=>1,'message'=>'success','address_data'=>$address]);
    
    }elseif($request->action == 2){
      
      $validator= Validator::make($request->all(),[
        'user_address_id'     => 'required|exists:user_address,id'
      ]);
  
      if($validator->fails()){
        return response()->json(Api::apiValidationResponse($validator->errors()->first()),422);
      }

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
        // 'mobile_number'        => 'mobile_number',
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
    
      $address['appartment']  =$data2;
      $address['address_type']=['home','office','appartment'];

      $user = \App\User::find( $userAddr->user_id);

      // $address['name']                    =  $user->full_name_en;
      // $address['email']                   =  $user->email;
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
      // $address['mobile_number']           =  $userAddr->mobile_number;
      $address['address']                 =  $userAddr->address;
      $address['location_latitude']       =  $userAddr->location_latitude;
      $address['location_longitude']      =  $userAddr->location_longitude;
      $address['area']                    =  $userAddr->area;
      

      return response()->json(['status'=>1,'message'=>'success','address_data'=>$address]);

    }

  }

  public function updateLanguage(Request $request)
  {
    $validator= Validator::make($request->all(),[
      'seleted_language'    => 'required|in:en,ar'
    ]);

    if($validator->fails()){
      return response()->json(Api::apiValidationResponse($validator->errors()->first()),422);
    }

    $token = auth('api')->user();

    if($token){
      $user = User::where('id',$token->id)->first();
      $user->is_language = $request->seleted_language;
      $user->save();
      return response()->json(['status'=>1,'message'=>'success','seleted_language'=>$user->is_language]);
    }else{
      return response()->json(['status'=>0,'message'=>__('message.tokenExpired'),'seleted_language'=>'']);
    }
   
  }

}
