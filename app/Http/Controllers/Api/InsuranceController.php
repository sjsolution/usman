<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\Models\Service;
use App\Models\Serviceaddons;
use App\Models\Vehicles;
use App\Models\Vehicletype;
use App\Models\Insurancetype;
use App\Models\Vehiclebrand;
use App\Models\Insurancecardetails;
use App\Models\Cms;
use App\Models\Orders;
use App\Models\Suborders;
use App\Models\Wallet;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\User as UserResource;
use App\Traits\CommonTrait;
use DB;
use Api;
use App\Traits\PushNotifications;
use App\Http\Services\HesabeOrderPaymentServices;
use App\Jobs\SendInvoiceEmailJob;
use App\Models\PaymentGatewaySetting;
use App\Http\Services\MyFatoorah\MyFatoorahPaymentServices;





class InsuranceController extends Controller
{
  use CommonTrait,PushNotifications;
  
  /**
   * Get all service provider list based on service type
   */
  public function insuranceserviceprovider(Request $request)
  {
      /****************service_type 1 for full party and 2 for third party ********/
      /****************category_type =2 for insurance type category ********/
   
      \Log::info($request->all());

      $validator= Validator::make($request->all(),[
        'user_id'             => 'required',
        'vehicle_type'        => 'required|exists:vehicle_type,id',
        'category_id'         => 'required|exists:categories,id',
        'model_id'            => 'required',
        'brand_id'            => 'required',
        // 'registration_number' => 'required',
        'manufacture'         => 'required',
        'category_type'       => 'required',
        'service_type'        => 'required',
      ]);

      if($validator->fails()){
        return response()->json(['status'=>0,'message' => $validator->errors()->first()]);
      }

  
      if(!empty($request->user_id))
      {
        $result = Vehicles::where('guest_id',$request->user_id)->update([
          'is_primary' => 0
        ]);

        if(!$result){
          $result = Vehicles::where('user_id',$request->user_id)->update([
            'is_primary' => 0
          ]);
        }
      }
      $brand  = Vehiclebrand::where('id',$request->brand_id)->first();
      $vehicles = new Vehicles();

      if(!empty($request->my_vehicle_id)){
        $existvehicles = Vehicles::find($request->my_vehicle_id);
        $existvehicles->vehicle_type_id = $request->vehicle_type;
        $existvehicles->model_id = isset($request->model_id)?$request->model_id:'';
        $existvehicles->vehicle_value = !empty($request->vehicle_value)?$request->vehicle_value:0;
        $existvehicles->brand_id = isset($request->brand_id)?$request->brand_id:'';
        $existvehicles->registration_number = isset($request->registration_number)?$request->registration_number:'';
        //$vehicles->plat_number = isset($request->plat_number)?$request->plat_number:'';
        $existvehicles->year_of_manufacture = isset($request->manufacture)?$request->manufacture:'';
        $existvehicles->is_active = 1;
        $existvehicles->is_primary = 1;
        $existvehicles->save();
      }else{
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
        $vehicles->vehicle_value = !empty($request->vehicle_value)?$request->vehicle_value:0;
        $vehicles->registration_number = isset($request->registration_number)?$request->registration_number:'';
        //$vehicles->plat_number = isset($request->plat_number)?$request->plat_number:'';
        $vehicles->year_of_manufacture = isset($request->manufacture)?$request->manufacture:'';
        $vehicles->is_primary = 1;
        $vehicles->is_active = 1;
        $vehicles->save();
      }

      $full_name = "full_name_".app()->getLocale();
      $name = "name_".app()->getLocale();
      $offset = isset($request->offset)?$request->offset:0;
      $limit = isset($request->limit)?$request->limit:100;
      $service_type = isset($request->service_type)?$request->service_type:1;
      $queries   = new Service;
      $query = $queries->newQuery();
      $query->with(['serviceprovider'=>function($q) use($brand){
        $q->with(['providerGroups'=>function($q) use($brand){
          $q->where('group_id',$brand->group_id)->where('percentage','>',0);
        }]);
      }])->whereHas('serviceprovider',function($q) use($brand){
        $q->where('is_active',1)
        ->whereHas('providerGroups',function($q) use($brand){
            $q->where('group_id',$brand->group_id)->where('percentage','>',0);
        });
      });
      $query->where(['is_active'=>'1']);
      $query->whereIn('service_type' , [(int)$request->service_type, 3 ]);
      $query->where(['type'=>$request->category_type]);
      if($request->category_id !=''){
          $query->where('category_id',$request->category_id);
      }
      //   if($request->vehicle_type !=''){
      //       $query->where('vehicle_type_id',$request->vehicle_type);
      // }
      $query->whereHas('vehicleType',function($j) use ($request) {
        $j->where('vehicle_type_id',$request->vehicle_type);
      })->orderBy('amount','asc');

      $serviceProviders = $query->offset($offset)->limit($limit)->get()->unique('user_id');

      // echo"<pre>";print_r($serviceProviders->toArray());die;
      $providers=[];

      foreach ($serviceProviders as $key=>$serviceProvider) 
      {
        $avgratings = !empty($serviceProvider['serviceprovider']) ?  $serviceProvider['serviceprovider']['ratingreviews']->avg('rating') : 0;
        $reviews = !empty($serviceProvider['serviceprovider']) ?  $serviceProvider['serviceprovider']['ratingreviews']->count() : 0;

        $isBookmark = \App\BookmarkServiceProvider::where('user_id',request()->user_id)
            ->where('service_provider_id',$serviceProvider['serviceprovider']['id'])
            ->where('is_marked',1)
            ->exists();

        $minServicePrice = 0;

        if($request->service_type == 1){ //Full Party

          // $serviceData = \App\Models\Service::where('user_id',$serviceProvider['serviceprovider']['id'])
          //               ->where('service_type',1)
          //               ->where('category_id',1)
          //               ->min('insurance_percentage');

          $serviceData =  \DB::table('service')->where('category_id',$request->category_id) 
              ->where('user_id',$serviceProvider['serviceprovider']['id'])
              ->where('service_type',1)
              ->select( 
                \DB::raw("MIN(service.insurance_percentage) AS insurance_percentage"), 
                \DB::raw("service.fixed_price AS fixed_price")
              )
              ->first();
                     

          if(!empty($serviceData)){
              
            $minServicePrice = ((request()->vehicle_value * $serviceData->insurance_percentage) / 100) + $serviceData->fixed_price;

            if($minServicePrice >= config('app.insurance_service_fixed_price')){
              $minServicePrice = $minServicePrice;
            }else{
              $minServicePrice = config('app.insurance_service_fixed_price');
            }
          
          }else{

            $minServicePrice = config('app.insurance_service_fixed_price');
            
          }
         
        }else{ //Third Party
          
          $minServicePrice = isset($serviceProvider['serviceprovider']['startingprice']['amount']) ? $serviceProvider['serviceprovider']['startingprice']['amount'] : 0;
          
          if($minServicePrice <= 0 ){
             $minServicePrice = 19;
          }

        }

        $providers[] =[
          'id'         =>  $serviceProvider['serviceprovider']['id'],
          'full_name'  =>  isset($serviceProvider['serviceprovider'][$full_name])?$serviceProvider['serviceprovider'][$full_name]:'',
          'about'      =>  isset($serviceProvider['serviceprovider']['about'])?$serviceProvider['serviceprovider']['about']:'',
          'profile_pic'=>  isset($serviceProvider['serviceprovider']['profile_pic'])?$serviceProvider['serviceprovider']['profile_pic']:'',
          'rating'     =>  isset($avgratings)?$avgratings:0.0,
          'reviews'    =>  isset($reviews)?$reviews:0,
          'starting_price' => $minServicePrice,
          'group_basis_percentage' => $serviceProvider['serviceprovider']['providerGroups'][0]->percentage ?? 0,
          'percentage_price' => ((float)($request->vehicle_value)*(float) $serviceProvider['serviceprovider']['providerGroups'][0]->percentage ?? 0)/100 ,
          // 'starting_price' => isset($serviceProvider['serviceprovider']['startingprice']['amount'])?$serviceProvider['serviceprovider']['startingprice']['amount']:'',
          'minimum_service_time' => isset($serviceProvider['serviceprovider']['servicetime']['time_duration'])?$serviceProvider['serviceprovider']['servicetime']['time_duration'] : 30,
          'is_marked'  => $isBookmark
        ];

        
        
      }

      $servicePriceRange =  \DB::table('service')->where('category_id',$request->category_id) 
        ->select( 
          \DB::raw("MIN(service.amount) AS min_amount"), 
          \DB::raw("MAX(service.amount) AS max_amount")
        )
        ->first();




      if($providers){
         // $providers = collect($providers)->sortBy('starting_price')->values()->all();
        if($request->sortBy==1){
          $providers = collect($providers)->sortBy('starting_price')->values()->all();
        }
        elseif($request->sortBy==2){
          $providers = collect($providers)->sortByDesc('starting_price')->values()->all();
        }
        elseif($request->sortBy==3){
          $providers = collect($providers)->sortBy('full_name')->values()->all();
        }
        else{
          $providers = collect($providers)->sortBy('starting_price')->values()->all();
        }
        

      
        return response()->json([
          'status'=>1,
          'message' =>'success',
          'service_providers'=>$providers,
          'filter'              => [
            'minPriceRange' => $servicePriceRange->min_amount > 0 ? $servicePriceRange->min_amount : "1",
            'maxPriceRange' => $servicePriceRange->max_amount > 0 ? $servicePriceRange->max_amount : "500"
          ]
        ]);
      }else{
        return response()->json([
          'status'=>0,
          'message' =>__('message.notRecordFound'),
          'filter'              => [
            'minPriceRange' => $servicePriceRange->min_amount > 0 ? $servicePriceRange->min_amount : "1",
            'maxPriceRange' => $servicePriceRange->max_amount > 0 ? $servicePriceRange->max_amount : "500"
          ]
        ]);
      }
    
  }

  public function providerdetails(Request $request)
  {

    if($request->isMethod('post'))
    {
      /*********user id is service provider id for get service details*******/
      $validator= Validator::make($request->all(),[
        'user_id'       => 'required|exists:users,id',
        'category_id'   => 'required|exists:categories,id',
        'category_type' => 'required',
        'service_type'  => 'required',
      ]);
    
      \Log::info('Request user id: '.$request->user_id);
      \Log::info('Insurance provider list: ');
      \Log::info($request->all());

      if($validator->fails()){
        return response()->json(['status'=>0,'message' => $validator->errors()->first()]);
      }

      $vehicle_value = 0;
      $fixedServicePrice = 0;

      if(!empty($request->logged_user_id)){

        $vehicle = Vehicles::where('guest_id',$request->logged_user_id)
                ->where('is_primary',1)
                ->where('is_active',1)
                ->first();

        if(!$vehicle){
          $vehicle = Vehicles::where('user_id',$request->logged_user_id)
              ->where('is_primary',1)
              ->where('is_active',1)
              ->first();
        }

        $vehicle_value = !empty($vehicle) ? $vehicle->vehicle_value : 0;

      }

      $name = "name_".app()->getLocale();
      $full_name = "full_name_".app()->getLocale();
      $description = "description_".app()->getLocale();
    /************1=full party and 2 third party **********/
      //$serviceType =isset($request->service_type)?$request->service_type:'1';
      // $providerDetails = User::where('id',$request->user_id)->with(['ratingreviews','services'])->first();
      $providerDetails = User::where('id',$request->user_id)->with(['coverimages','ratingreviews','services'=>function($query) use ($request) {
        $query->whereIn('service_type',[(int)$request->service_type,3]);
        $query->where('category_id',$request->category_id);
        $query->whereHas('vehicleType',function($join){
          $join->where('vehicle_type_id',request()->vehicle_type);
        });
        $query->where('type',$request->category_type);
      }])->first();

      $avgratings = $providerDetails['ratingreviews']->avg('rating');
      $reviews = $providerDetails['ratingreviews']->count();
      $services=[];
      foreach ($providerDetails->services as $service) {
        $addons=[];
        foreach ($service['serviceaddonsdata'] as $serviceaddons) {
        $addons[]=[
                    'id'          =>$serviceaddons['id'],
                    'name'        =>$serviceaddons[$name],
                    'amount'      =>$serviceaddons['amount'],
                  ];
        }
        $sevicedess=[];

        foreach ($service['servicedescription'] as $keys=>$servicedescription) {
          $sevicedess[$keys]=$servicedescription[$description];
        }
        /**********service providers cover image********/
        $coverimge=[];
        foreach ($providerDetails['coverimages'] as $coverimages) {
          $coverimge[]=$coverimages['cover_image'];
        }
        \Log::info('vehicle_value:'.$vehicle_value);
        \Log::info('percentage : '.$service['insurance_percentage']);

        $servicePrice = $vehicle_value * ($service['insurance_percentage'] / 100);

        //Add 120 KD Logic here
        if($vehicle_value > 0 && $service['type'] == 1 && $service['service_type'] == 1 ){
         
          if($servicePrice >= config('app.insurance_service_fixed_price')){
            $fixedServicePrice = $servicePrice;
          }else{
            $fixedServicePrice = config('app.insurance_service_fixed_price');
          }

        }else{
            $fixedServicePrice = $servicePrice;
        }

        $services[]=[
                    'id'          =>$service['id'],
                    'name'        =>$service[$name],
                    'amount'      =>!empty( $vehicle_value) ?  $fixedServicePrice : 0,
                    'description' =>$sevicedess,
                  //  'addons'      =>$addons,
                  ];
      }
      $providers = [
        'id'=>$providerDetails['id'],
        'full_name'=>$providerDetails[$full_name],
        'profile_pic'=>isset($providerDetails['profile_pic'])?$providerDetails['profile_pic']:'',
        //'cover_pic'=>$coverimge,
        'about'=>isset($providerDetails['about'])?$providerDetails['about']:'',
        'rating'=>isset($avgratings)?$avgratings:0.0,
        'reviews'=>isset($reviews)?$reviews:0,
        'services'=>$services,
        ];
      if($providers){
        return response()->json(['status'=>1,'message' =>'success','service'=>$providers]);
      }else{
        return response()->json(['status'=>0,'message' => __('message.notRecordFound')]);
      }
    }
  }

  public function insurancevehicledetails(Request $request)
  {
    if($request->isMethod('post')){
      $validator= Validator::make($request->all(),[
        'user_id'=>'required',
      ]);
      if($validator->fails()){
        return response()->json(['status'=>0,'message' => $validator->errors()->first()]);
      }
      $vehicleTypes = Vehicletype::where(['is_active'=>1])->get();
      $name = "name_".app()->getLocale();
      $vehicleData=[];
      foreach ($vehicleTypes as $vehicleType) {
        $vehicleData[]=['id'=>$vehicleType->id,'name'=>$vehicleType->$name,'image'=>isset($vehicleType->image)?$vehicleType->image:''];
      }
      $userid = $this->userexist($request->user_id);
      if(!empty($userid['guest_id'])){
          $vehicles = Vehicles::with('vehilcesdata')->where('guest_id',$userid['guest_id'])->get();
      }else{
          $vehicles = Vehicles::with('vehilcesdata')->where('user_id',$request->user_id)->get();
      }
      $allvehicles=[];
      foreach ($vehicles as $vehicle) {
        $allvehicles[]=['id'=>$vehicle->id,'vehicle_type_id'=>$vehicle->vehicle_type_id,'name'=>$vehicle['vehilcesdata'][$name],'model_number'=>$vehicle->model_number,'brand_name'=>$vehicle->brand_name,'registration_number'=>$vehicle->registration_number,'manufacture'=>isset($vehicle->year_of_manufacture)?$vehicle->year_of_manufacture:''];
      }
      $returndata=['my_vehicle'=>$allvehicles,'vehicle_type'=>$vehicleData];
      return response()->json(['status'=>1,'message' =>'success','vehicledetails'=>$returndata]);
    }
  }

  /**
   * Save insurance car details
   */
  public function insuranceaddcardetails(Request $request)
  {
    if($request->isMethod('post')){

      $validator= Validator::make($request->all(),[
        'user_id'=>'required',
        'service_id'=>'required|exists:service,id',
        'insurance_start_date'=>'required',
        //'insurance_type'=>'required',
        'mobile_number'=>'required',
        'civil_id_front'=>'required',
        'civil_id_back'=>'required',
        // 'car_registration_number'=>'required',
        //'chassis_number'=>'required',
        //'description'=>'required',
        //  'current_car_estimation_value'=>'required',
        'is_agree'=>'required',
        'image'=>'required',
      ]);
      if($validator->fails()){
        return response()->json(['status'=>0,'message' => $validator->errors()->first()]);
      }

      $name = "name_".app()->getLocale();
      $walletAmount = User::find($request->user_id);
      $serviceDetails = Service::find($request->service_id);
      $insuranceDetails = new Insurancecardetails;
      $userid = $this->userexist($request->user_id);
      if(!empty($userid['guest_id'])){
          $insuranceDetails->guest_id=$userid['guest_id'];
          $insuranceDetails->user_id=$userid['user_id'];
          $vehicles = Vehicles::where(['user_id'=>$userid['user_id'],'guest_id'=>$userid['guest_id'],'is_active'=>'1','is_primary'=>1])->first();
          $insuranceDetails->user_vehicle_id=$vehicles['id'];
      }else{
          $insuranceDetails->user_id=$request->user_id;
          $vehicles = Vehicles::where(['user_id'=>$request->user_id,'is_active'=>'1','is_primary'=>1])->first();
          $insuranceDetails->user_vehicle_id=$vehicles['id'];
      }

      $insuranceDetails->service_id=isset($request->service_id)?$request->service_id:'';
      $insuranceDetails->insurance_start_date=isset($request->insurance_start_date)?$request->insurance_start_date:'';
      $insuranceDetails->insurance_type=isset($request->insurance_type)?$request->insurance_type:'';
      $insuranceDetails->mobile_number=isset($request->mobile_number)?$request->mobile_number:'';
      $insuranceDetails->civil_id_front=isset($request->civil_id_front)?$request->civil_id_front:'';
      $insuranceDetails->civil_id_back=isset($request->civil_id_back)?$request->civil_id_back:'';
      $insuranceDetails->car_registration_number=isset($request->car_registration_number)?$request->car_registration_number:'';
      if($request->current_car_estimation_value !=''){
        $validator1= Validator::make($request->all(),[
          'current_car_estimation_value'=>'numeric',
        ]);
        if($validator1->fails()){
          return response()->json(['status'=>0,'message' => $validator1->errors()->first()]);
        }
      }
      $insuranceDetails->current_car_estimation_value=$request->current_car_estimation_value;
      $insuranceDetails->chassis_number=isset($request->chassis_number)?$request->chassis_number:'';
      $insuranceDetails->description=isset($request->description)?$request->description:'';
      $insuranceDetails->images=isset($request->image)?$request->image:'';
      $insuranceDetails->is_agree=isset($request->is_agree)?$request->is_agree:'1';
      $insuranceDetails->save();
     
      $serviceData = [
        'id'=>$serviceDetails['id'],
        'name'=>$serviceDetails[$name],
        'amount'=> !empty($vehicles->vehicle_value) ? (($vehicles->vehicle_value * $serviceDetails->insurance_percentage)/100) : 0,
        'wallet_amount'=>$walletAmount['amount'],
        //'amount'=>number_format($serviceDetails['amount'],3),
        //'wallet_amount'=>number_format($walletAmount['amount'],3),
        'insurance_car_id'=>$insuranceDetails['id'],
        'user_vehicle_id'=>$vehicles['id'],
      ];
      \Log::info($serviceData);
      if($insuranceDetails){
        return response()->json(['status'=>1,'message' =>'Success.','service_payment'=>$serviceData]);
      }else{
        return response()->json(['status'=>0,'message' =>'Internal error']);
      }
    }
  }

  public function getDayId($name)
  {
      switch($name) {

          case "Sunday" :
              $dayId = 1;
              break;

          case "Monday" :
              $dayId = 2;
              break;

          case "Tuesday" :
              $dayId = 3;
              break;

          case "Wednesday" :
              $dayId = 4;
              break;

          case "Thursday" :
              $dayId = 5;
              break;

          case "Friday" :
              $dayId = 6;
              break;

          case "Saturday" :
              $dayId = 7;
              break;

          default :
              $dayId = 0 ;
      }

      return $dayId;
  }

  public function makepayment(Request $request)
  {
    try{ 

        $validator= Validator::make($request->all(),[
          'type'          => 'required|in:1,2', // 1 = Insurance,2 = Normal Or Emergency
          'payment_type'  => 'required|in:0,1,2,3,4,5', //0: None ,1 : Wallet Payment , 2 : Knet 3 : knet & wallet, 4: Credit Card, 5: Credit Card & Wallet
          'user_id'       => 'required|exists:users,id',
          'service_id'    => 'required|exists:service,id',
          'sub_amount'    => 'required',
          'wallet_amount' => 'required',
        ]);

        if($validator->fails()){
          return response()->json(['status'=>0,'message' => $validator->errors()->first()]);
        }

        $totalTimeConsumption = 0;

        $serviceProviderIds = 0;

        if($request->type ==1){

          $validator= Validator::make($request->all(),[
            'insurance_car_id' => 'required',
            'user_vehicle_id'  => 'required',
          ]);
        }

        if($validator->fails()){
          return response()->json(['status'=>0,'message' => $validator->errors()->first()]);
        }

        $user =  User::where('id',$request->user_id)->first();

        if($user->is_active == 0){

          return response()->json(Api::apiErrorResponse(__('message.userLogout')),401);

        }

        //Phone is verified or not.
        if($user->is_verified_mobile == 0){
          return response()->json(['status'=>0,'message' =>__('message.phoneIsNotVerified'),'isPhoneVerified' => 0]);
        }

        if($request->payment_type == 1){
// dd(($request->coupon_amount));
          if($user->amount > 0){
            // if($request->total_amount != $request->wallet_amount || ($request->total_amount - $request->coupon_amount) != $request->wallet_amount)
            //   return response()->json(['status'=>0,'message' =>"You wallet have insufficient balance"]);
          }else{
            return response()->json(['status'=>0,'message' =>"You wallet have insufficient balance"]);
          }
        }

        $isBooked = 0;

        if($request->payment_type == 1){
          $isBooked = 1;
        }else{
          $isBooked = 0;
        }
    
        $orderNumber = time().'-'.$request->user_id;

        $bookServiceData = Service::where('id',$request->service_id)->first();
        $vendor          = User::find($bookServiceData->user_id);
        
        DB::beginTransaction();

        $order = Orders::create([
          'user_id'             => $request->user_id,
          'category_id'         => !empty($bookServiceData) ? $bookServiceData->category->id : null,
          'category_type'       => (string)$request->type,
          'order_number'        => $orderNumber,
          'service_charge'      => isset($request->service_charge) ?  $request->service_charge : 0.000,
          'coupon_amount'       => isset($request->coupon_amount)  ?  $request->coupon_amount:0.00,
          'coupon_code'         => isset($request->coupon_code)    ?  $request->coupon_code:'',
          'coupon_id'           => isset($request->coupon_id)      ?  $request->coupon_id:null,
          'payment_status'      => '0',
          'payment_type'        => $request->payment_type,
          'final_amount'        => isset($request->final_amount)   ?  $request->final_amount:0.00,
          'status'              => '0',
          'total_amount'        => $request->totalServiceAmount,
          'net_payable_amount'  => !empty($request->total_amount) ? $request->total_amount : $request->totalServiceAmount,
          'sub_amount'          => $request->sub_amount,
          'wallet_amount'       => isset($request->wallet_amount) ? $request->wallet_amount:0.00,
          'user_applicable_fee' => !empty($request->user_application_fee) ? $request->user_application_fee : 0,
          'is_apply_user_applicable_fee' => !empty($request->user_application_fee) ? 1 : 0,
          'user_address_id'     => isset($request->user_address_id) ? $request->user_address_id : null
        ]);

     
        $order->orderStatus()->create([
          'status' => '0'
        ]);

     
        $services         = explode(',',$request->service_id);
        $sub_amount       = explode(',',$request->sub_amount);

        if($request->type == 1){
          $user_vehicle_id  = explode(',',$request->user_vehicle_id);
          $insurance_car_id = explode(',',$request->insurance_car_id);
        }else{
          $user_vehicle_id = Vehicles::where(['user_id'=>$request->user_id,'is_primary'=>'1'])->first();
        }


        if(count($services) > 0){
         
          foreach ($services as $key => $value) {

            $serviceDuration = 0;
            $addonsDuration  = 0;

            $serviceData = Service::where('id',$value)->first();

            if($serviceData->time_type == 'mins'){
              $serviceDuration = $serviceDuration  + (int)$serviceData->time_duration;
            }elseif($serviceData->time_type == 'hours'){
              $serviceDuration =  $serviceDuration  + (int)$serviceData->time_duration * 60;
            }else{
              $serviceDuration =  $serviceDuration  + (int)$serviceData->time_duration;
            }

          

            $totalTimeConsumption = $totalTimeConsumption + $serviceDuration;

            \Log::info('totalTimeConsumption : '.$totalTimeConsumption);

            if($request->type ==1){
              $subOrder  = $order->subOrder()->create([
                'order_number_id'  => $orderNumber,
                'user_vehicle_id'  => $user_vehicle_id[$key],
                'service_id'       => $value,
                'insurance_car_id' => $insurance_car_id[$key],
                'sub_amount'       => $sub_amount[$key]
              ]);

              if(!empty($request->addons_id)){

                $addons       = explode(',',$request->addons_id);
                $addonsamount = explode(',',$request->addons_amount);

                foreach ($addons as $key1=>$addonsvalue) {
                  
                  $subOrder->addons()->create([
                    'service_addon_id' => $addonsvalue,
                    'amount'           => $addonsamount[$key1]
                  ]);

                }

              }


              
            }else{

             

              $subOrder = $order->subOrder()->create([
                'order_number_id'  => $orderNumber,
                'user_vehicle_id'  => $user_vehicle_id['id'],
                'service_id'       => $value,
                'sub_amount'       => $sub_amount[$key],
                'service_type'     => '1',
              ]);

             
              if(!empty($request->addons_id)){

                $addons       = explode(',',$request->addons_id);
                $addonsamount = explode(',',$request->addons_amount);
                foreach ($addons as $key1=>$addonsvalue) {
                  
                  $subOrder->addons()->create([
                    'service_addon_id' => $addonsvalue,
                    'amount'           => $addonsamount[$key1]
                  ]);

                 

                  $serviceAddonsData = Serviceaddons::where('id',$addonsvalue)->first();
                  \Log::info('addons : '.$serviceAddonsData->time_duration);
                  if($serviceAddonsData->time_type == 'mins'){
                    $addonsDuration =  $addonsDuration + (int)$serviceAddonsData->time_duration;
                  }elseif($serviceAddonsData->time_type == 'hours'){
                    $addonsDuration =  $addonsDuration + (int)$serviceAddonsData->time_duration * 60;
                  }else{
                    $addonsDuration =  $addonsDuration + (int)$serviceAddonsData->time_duration;
                  }
                }

                $totalTimeConsumption =$totalTimeConsumption + $addonsDuration;
                \Log::info('totalTimeConsumption2 : '.$totalTimeConsumption);

              }

            }

          }
         

          //Emergency or Normal flow
          if($request->type == 2){

            //Assignment of Technician
            $bookingDate = !empty($request->date) ? $request->date : \Carbon\Carbon::now()->format('Y-m-d');
            $bookingTime = !empty($request->time) ? $request->time : \Carbon\Carbon::now()->format('H:i');

            $d    = new \DateTime($bookingDate);

            $dayId = $this->getDayId($d->format('l'));

            $sid = $request->service_id;

            $serviceProvider = \App\Models\Service::where('id',$sid)->first()->user_id;

            $spUserDetails = \App\User::find($serviceProvider);

            //Buffer Length

            $totalTimeConsumption = $totalTimeConsumption + (!empty($spUserDetails->userTimeSlot[0]->buffer_length) ? $spUserDetails->userTimeSlot[0]->buffer_length : 0) ;

            $bookingEndTime = strtotime("+$totalTimeConsumption minutes", strtotime($bookingTime));

            $bookingEndTime = date("H:i",$bookingEndTime);

            \Log::info('Booking Start Time : '.$bookingTime);
            \Log::info('Booking End Time   : '.$bookingEndTime);
          
            $technician = \App\User::where('is_active',1)->where('is_technician','1')->where('is_service_active',1);
            
            if($technician->exists()){
          
              $techicians = $technician->where('created_by', $serviceProvider )
                  ->whereHas('technicaintimeslots',function($query) use ($dayId, $bookingTime,$bookingEndTime){
                    $query->where('day_id','=',$dayId)
                      ->where('is_active',1)
                      ->where('status',1)
                      ->whereRaw('technician_time_slot.start_time <= "'.$bookingTime.'" AND technician_time_slot.end_time > "'.$bookingTime.'"')
                      ->whereRaw('technician_time_slot.start_time <= "'.$bookingEndTime.'" AND technician_time_slot.end_time >= "'.$bookingEndTime.'"')
                      ->whereNotExists(function($q) use ($bookingEndTime,$dayId,$bookingTime){
                        $q->whereRaw('technician_time_slot.break_from <= "'.$bookingTime.'" AND technician_time_slot.break_to > "'.$bookingTime.'"')
                          ->orwhereRaw('technician_time_slot.break_from < "'.$bookingEndTime.'" AND technician_time_slot.break_to > "'.$bookingEndTime.'"');
                      })
                      ->whereNotExists(function($q) use ($bookingEndTime,$dayId,$bookingTime){
                        $q->whereRaw('technician_time_slot.break_from > "'.$bookingTime.'" AND technician_time_slot.break_to <= "'.$bookingEndTime.'"');
                      });
                  })
                  ->whereDoesntHave('bookedTechnican',function($query) use ($bookingDate,$bookingTime,$bookingEndTime){
                    $query->where('booking_date','=',$bookingDate)
                        ->whereIn('status',['1'])
                        ->whereExists(function($q) use ($bookingEndTime,$bookingTime){
                          $q->whereRaw('booked_technicians.booking_time <= "'.$bookingTime.'" AND booked_technicians.booking_end_time > "'.$bookingTime.'"')
                            ->orwhereRaw('booked_technicians.booking_time < "'.$bookingEndTime.'" AND booked_technicians.booking_end_time > "'.$bookingEndTime.'"');
                        });
                        // ->whereExists(function($q) use ($bookingEndTime,$bookingTime){
                        //   $q->whereRaw('booked_technicians.booking_time >= "'.$bookingTime.'" AND booked_technicians.booking_end_time <= "'.$bookingEndTime.'"');
                        // });;
                  });

                  $serviceReq = \App\Models\Service::where('id',$sid)->first();

                  if(!empty($serviceReq) && $serviceReq->category->type == 3){

                    $techicians = $techicians->whereHas('serviceProviderForTechnician.userEmergencyTimeSlot',function($query) use ($bookingTime,$dayId) {
                      $query->where('day_id','=',$dayId)
                        ->whereTime('start_time','<=',$bookingTime.':59')
                        ->whereTime('end_time','>',$bookingTime.' 00'); 
                    });


                  }

                  $techicians = $techicians->with('bookedTechnican')->first();

                
              
              if(!empty($techicians)){
               
                $serviceProviderIds = \App\User::find($techicians->id)->created_by;

                $user->bookingTechnican()->create([
                  'order_id'               => $order->id,
                  'service_provider_id'    => $serviceProviderIds,
                  'technician_id'          => $techicians->id,
                  'booking_date'           => $bookingDate,
                  'booking_time'           => $bookingTime,
                  'booking_end_time'       => $bookingEndTime,
                  'status'                 => (string)$isBooked
                ]);

                $order->service_provider_id = $serviceProviderIds;
                $order->save();

              }else{ 
             
                $servieProviderAsTech = \App\User::where('is_active',1)->where('is_technician','1')->where('id',$serviceProvider);
                
                if($servieProviderAsTech->exists()){
                
                  $servieProviderAsTechcian = $servieProviderAsTech
                  ->whereHas('userTimeSlot',function($query) use ($dayId, $bookingTime,$bookingEndTime){
                    $query->where('day_id','=',$dayId)
                      ->where('is_active',1)
                      ->where('status',1)
                      ->whereRaw('sp_time_slot.start_time <= "'.$bookingTime.'" AND sp_time_slot.end_time > "'.$bookingTime.'"')
                      ->whereRaw('sp_time_slot.start_time <= "'.$bookingEndTime.'" AND sp_time_slot.end_time >= "'.$bookingEndTime.'"')
                      ->whereNotExists(function($q) use ($bookingEndTime,$dayId,$bookingTime){
                        $q->whereRaw('sp_time_slot.break_from < "'.$bookingTime.'" AND sp_time_slot.break_to > "'.$bookingTime.'"')
                          ->orwhereRaw('sp_time_slot.break_from < "'.$bookingEndTime.'" AND sp_time_slot.break_to > "'.$bookingEndTime.'"');
                      })
                      ->whereNotExists(function($q) use ($bookingEndTime,$dayId,$bookingTime){
                        $q->whereRaw('sp_time_slot.break_from > "'.$bookingTime.'" AND sp_time_slot.break_to <= "'.$bookingEndTime.'"');
                      });
                  })
                  ->whereDoesntHave('bookedTechnicanAsServiceProviders',function($query) use ($bookingDate,$bookingTime,$bookingEndTime){
                    $query->where('booking_date','=',$bookingDate)
                      ->whereIn('status',['1'])
                      ->whereNull('technician_id')
                      ->whereRaw('booked_technicians.booking_date="'.$bookingDate.'"')
                      ->whereRaw('booked_technicians.booking_time <= "'.$bookingTime.'" AND booked_technicians.booking_end_time > "'.$bookingTime.'"')
                      ->orwhereRaw('booked_technicians.booking_time <= "'.$bookingEndTime.'" AND booked_technicians.booking_end_time > "'.$bookingEndTime.'"');
                  })
                  ->first();

               
                  if(!empty($servieProviderAsTechcian)){
                   
                    $user->bookingTechnican()->create([
                      'order_id'               => $order->id,
                      'service_provider_id'    => $serviceProvider,
                      'booking_date'           => $bookingDate,
                      'booking_time'           => $bookingTime,
                      'booking_end_time'       => $bookingEndTime,
                      'status'                 => (string)$isBooked
                    ]);

                    $order->service_provider_id = $serviceProvider;
                    $order->save();

                    $serviceProviderIds = $serviceProvider;
                      
                  }else{

                    DB::rollBack();
                    return response()->json(['status'=>0,'message' =>__('message.spTimeSlotBusy')]);

                  }

                }else{

                  DB::rollBack();
                  return response()->json(['status'=>0,'message' =>__('message.spTimeSlotBusy')]);

                }

              }


            }else{
              DB::rollBack();
              return response()->json(['status'=>0,'message' =>__('message.spTimeSlotBusy')]);
            }

          }

          //Insurance Flow
          if($request->type == 1){

            $insuranceServiceProvider = \App\Models\Service::where('id',$request->service_id)->first()->user_id;

            $user->bookingTechnican()->create([
              'order_id'               => $order->id,
              'service_provider_id'    => $insuranceServiceProvider,
              'technician_id'          => null,
              'booking_date'           => null,
              'booking_time'           => null,
              'booking_end_time'       => null,
              'status'                 => (string)$isBooked
            ]);

            $order->service_provider_id =  $insuranceServiceProvider;
            $order->save();

          }

        } 

        if($user->amount > 0 && ($request->payment_type == 1)){

          $remaningTotalAmount = (double)$user->amount - (double)$request->wallet_amount;
          
          if( $remaningTotalAmount >= 0) {

            $user->amount            = $remaningTotalAmount;
            $user->save();

            $user->wallet()->create([
                'transaction_amount' => $request->wallet_amount,
                'closing_amount'     => $remaningTotalAmount,
                'transaction_type'   => '2', //debit amount from wallet
                'description'        => "Debited from wallet",
                'description_ar'     => "مدين من المحفظة"
            ]);

            $order->payment_status = '2';
            $order->save();


          }else{
// dd($user);

            return response()->json(['status'=>0,'message' =>__('message.insufficientBalance')]);
          
          }
          
        }

        if($request->payment_type ==1){        //Wallet Payment
          //Admin commission calculation
          $serviceProviderDetails = \App\User::find($serviceProviderIds);

          $maakPercent      = !empty($serviceProviderDetails->maak_percentage) ? $serviceProviderDetails->maak_percentage : 0;
          $maakFixedPrice   = !empty($serviceProviderDetails->fixed_price) ? $serviceProviderDetails->fixed_price : 0;
          $commissionAmount = $maakFixedPrice + ((double)$request->total_amount *  $maakPercent) / 100;
           
          //Transaction 
          $order->transaction()->create([
            'service_amount'      => $request->total_amount,
            //'service_provider_id' => !empty($serviceProviderIds) ? $serviceProviderIds : null,
            'service_provider_id' => $order->service_provider_id,
            'maak_percentage'     => $maakPercent,
            'fixed_amount'        => $maakFixedPrice,
            'commission'          => $commissionAmount + $order->user_applicable_fee,
            'knet_fees'           => 0,
            'others_fees'         => 0,
            'net_payable'         => $request->total_amount -  $order->coupon_amount,
            'status'              => '1',
            'user_applicable_fee' => $order->user_applicable_fee,
            'cash_payable'        => $request->total_amount - ( $commissionAmount + $order->user_applicable_fee ), 
            'actual_sp_share'     => $request->total_amount - ( $commissionAmount + $order->user_applicable_fee ),
            'net_sp_share'        => $request->total_amount - ( $commissionAmount + $order->user_applicable_fee ),
            'net_commission'      => ($commissionAmount + $order->user_applicable_fee) - $order->coupon_amount
          ]);  

          $order->revenue()->create([
            'status'    => '1',
            'amount'    => ($maakFixedPrice + $commissionAmount ) - $order->coupon_amount,
            'sp_amount' => $request->total_amount - $commissionAmount
          ]);
          
          //send notification to technician
          if(!empty($order->serviceProvider[0]->technician)){
            
            foreach($order->serviceProvider[0]->technician->deviceInfo as $key){
            
              if(!empty($key->device_token)){
  
                  if($order->user->is_language=='ar'){
                      $title     = 'تم استلام الحجز الجديد';
                      $subject   = 'لقد استلمت الحجز الجديد. يرجى التحقق من تفاصيل الحجز.';
                  }else{
                      $title   = 'New Booking Received';
                      $subject = 'You have received a new booking. Please check booking details.';
                  }

                  \Log::info('notification is working');

                  $this->sendNotification($title,$subject,$key->device_token,'booking_list',$order->id,'2');
          
              }

            }

          }

          //Mail Invoice
          $userServiceProvider = User::where('id',$order->service_provider_id)->first();

          //Mail send to service provider on secondary mail
          $spUserEmail = new User;
        
          $spUserEmail->email = $userServiceProvider->email ?? $userServiceProvider->secondary_email;
          $spEmail =  $spUserEmail->email;

          dispatch(new SendInvoiceEmailJob($order,$spEmail));

          //Mail to maak admin
          // dispatch(new SendInvoiceEmailJob($order,'orders@maak.live'));

          //END
          
          DB::commit();

        }elseif($request->payment_type == 2){  //Knet Payment
          
          if($request->total_amount == 0)
          {
            $order->payment_type = 0;
            $order->payment_status = '2';
            $order->total_amount = $request->total_amount;
            $order->save();
            
            //Admin commission calculation
            $serviceProviderDetails = \App\User::find($serviceProviderIds);

            $maakPercent      = !empty($serviceProviderDetails->maak_percentage) ? $serviceProviderDetails->maak_percentage : 0;
            $maakFixedPrice   = !empty($serviceProviderDetails->fixed_price) ? $serviceProviderDetails->fixed_price : 0;
            $commissionAmount = $maakFixedPrice + ((double)$request->total_amount *  $maakPercent) / 100;
           
            //Transaction 
            $order->transaction()->create([
              'service_amount'      => $request->total_amount,
              //'service_provider_id' => !empty($serviceProviderIds) ? $serviceProviderIds : null,
              'service_provider_id' => $order->service_provider_id,
              'maak_percentage'     => $maakPercent,
              'fixed_amount'        => $maakFixedPrice,
              'commission'          => $commissionAmount + $order->user_applicable_fee,
              'knet_fees'           => 0,
              'others_fees'         => 0,
              'net_payable'         => $request->total_amount -  $order->coupon_amount,
              'status'              => '1',
              'user_applicable_fee' => $order->user_applicable_fee,
              'cash_payable'        => $request->total_amount - ( $commissionAmount + $order->user_applicable_fee ), 
              'actual_sp_share'     => $request->total_amount - ( $commissionAmount + $order->user_applicable_fee ),
              'net_sp_share'        => $request->total_amount - ( $commissionAmount + $order->user_applicable_fee ),
              'net_commission'      => ($commissionAmount + $order->user_applicable_fee) - $order->coupon_amount
            ]);  

            $order->revenue()->create([
              'status'    => '1',
              'amount'    => ($maakFixedPrice + $commissionAmount ) - $order->coupon_amount,
              'sp_amount' => $request->total_amount - $commissionAmount
            ]);
          
            //send notification to technician
            if(!empty($order->serviceProvider[0]->technician)){
              
              foreach($order->serviceProvider[0]->technician->deviceInfo as $key){
              
                if(!empty($key->device_token)){
    
                    if($order->user->is_language=='ar'){
                        $title     = 'تم استلام الحجز الجديد';
                        $subject   = 'لقد استلمت الحجز الجديد. يرجى التحقق من تفاصيل الحجز.';
                    }else{
                        $title   = 'New Booking Received';
                        $subject = 'You have received a new booking. Please check booking details.';
                    }

                    \Log::info('notification is working');

                    $this->sendNotification($title,$subject,$key->device_token,'booking_list',$order->id,'2');
            
                }

              }

            } 
            //Mail Invoice
          $userServiceProvider = User::where('id',$order->service_provider_id)->first();

          //Mail send to service provider on secondary mail
          $spUserEmail = new User;
        
          $spUserEmail->email = $userServiceProvider->email ?? $userServiceProvider->secondary_email;
          $spEmail =  $spUserEmail->email;

          dispatch(new SendInvoiceEmailJob($order,$spEmail));

          //Mail to maak admin
          // dispatch(new SendInvoiceEmailJob($order,'orders@maak.live'));
            
            DB::commit();

            return response()->json(['paymentUrl' => '','wallet_amount'=>$user->amount,'status'=>1,'message' =>__('message.paymentSuccess')]);

          }

          $userRemWallBal = $user->amount - $request->wallet_amount;
 
          /**
           * Payment Geteway stwich
           */
          $defaultPayment = PaymentGatewaySetting::where('is_default',1)->first();

          if($defaultPayment->id == 1){ //Hesabe Payment
            //Hesabe Payment
            $hesabe         = new HesabeOrderPaymentServices;
            $response       = $hesabe->setDataForPayment($order,1,$userRemWallBal,$request->total_amount);
            //
          }else if($defaultPayment->id == 2){ //Fatoorah Payment
            $supplierCode   = !empty($vendor->supplier_code)?$vendor->supplier_code : '';
            $hesabe         = new MyFatoorahPaymentServices;
            $response       = $hesabe->paymentUrl($order->id,1,$order->total_amount,$order->service_charge,$order->net_payable_amount,$supplierCode,$order->user_id);
          }
          /**
           * End of Payment Gateway
           */
        

          // $response = $this->paymentUrl((double)$request->total_amount,$order->id,$serviceProviderIds,1);
         
          // $resArr = [
          //   'status'        => 1,
          //   'message'       => $response['message'],
          //   'order_id'      => $order->id,
          //   'wallet_amount' => $userRemWallBal,
          //   'paymentUrl'    => (config('app.env') == 'staging') ? $response['data']['paymenturl'].$response['data']['token'] : $response['data']['paymenturl'].$response['data']['token'].$response['data']['ptype']
          // ];
            
          
          DB::commit();

          return $response;

        }elseif($request->payment_type == 3){  //Knet & Wallet Payment

          if($request->total_amount == 0){
            $order->payment_status = '2';
            $order->payment_type = 0;
            $order->total_amount = $request->total_amount;
            $order->save();
            DB::commit();
            return response()->json([
              'paymentUrl' => '',
              'wallet_amount'=>$user->amount,
              'status'=>1,
              'message' =>__('message.paymentSuccess')
            ]);
          }

          // $response =  $this->paymentUrl((double)$request->final_amount,$order->id,$serviceProviderIds,1);

          // $resArr = [
          //   'status'        => 1,
          //   'message'       => $response['message'],
          //   'order_id'      => $order->id,
          //   'wallet_amount' => $user->amount - $request->wallet_amount,
          //   'paymentUrl'    => (config('app.env') == 'staging') ? $response['data']['paymenturl'].$response['data']['token'] : $response['data']['paymenturl'].$response['data']['token'].$response['data']['ptype']
          // ];

          //Hesabe Payment
          $userRemWallBal = $user->amount - $request->wallet_amount;

          // $hesabe         = new HesabeOrderPaymentServices;
          // $response       = $hesabe->setDataForPayment($order,1,$userRemWallBal,$request->final_amount);
          //

             /**
           * Payment Geteway stwich
           */
          $defaultPayment = PaymentGatewaySetting::where('is_default',1)->first();

          if($defaultPayment->id == 1){ //Hesabe Payment
            //Hesabe Payment
            $hesabe         = new HesabeOrderPaymentServices;
            $response       = $hesabe->setDataForPayment($order,1,$userRemWallBal,$request->final_amount);
            //
          }else if($defaultPayment->id == 2){ //Fatoorah Payment
            
            $supplierCode   = !empty($vendor->supplier_code)?$vendor->supplier_code : '';
            $hesabe         = new MyFatoorahPaymentServices;
            $response       = $hesabe->paymentUrl($order->id,1,$order->total_amount,$order->service_charge,$order->final_amount,$supplierCode,$order->user_id);
            // $response       = $hesabe->paymentUrl($order->id,$order->payment_type,$order->total_amount,$order->service_charge,$order->final_amount,$supplierCode,$order->user_id);
          }
          /**
           * End of Payment Gateway
           */


          DB::commit();

          return $response;

        }elseif($request->payment_type == 4){  //Credit Card

          if($request->total_amount == 0){
            $order->payment_status = '2';
            $order->payment_type = 0;
            $order->total_amount = $request->total_amount;
            $order->save();
            DB::commit();
            return response()->json(['paymentUrl' => '','wallet_amount'=>$user->amount,'status'=>1,'message' =>__('message.paymentSuccess')]);
          }

          // $response = $this->paymentUrl((double)$request->total_amount,$order->id,$serviceProviderIds,2);

          //Hesabe Payment
          $userRemWallBal = $user->amount - $request->wallet_amount;

          //$hesabe         = new HesabeOrderPaymentServices;
          //$response       = $hesabe->setDataForPayment($order,2,$userRemWallBal,$request->total_amount);
          //
          // $resArr = [
          //   'status'        => 1,
          //   'message'       => $response['message'],
          //   'order_id'      => $order->id,
          //   'wallet_amount' => $user->amount - $request->wallet_amount,
          //   'paymentUrl'    => (config('app.env') == 'staging') ? $response['data']['paymenturl'].$response['data']['token'] : $response['data']['paymenturl'].$response['data']['token'].$response['data']['ptype']
          // ];

          /**
           * Payment Geteway stwich
           */
          $defaultPayment = PaymentGatewaySetting::where('is_default',1)->first();

          if($defaultPayment->id == 1){ //Hesabe Payment
            //Hesabe Payment
            $hesabe         = new HesabeOrderPaymentServices;
            $response       = $hesabe->setDataForPayment($order,2,$userRemWallBal,$request->total_amount);
            //
          }else if($defaultPayment->id == 2){ //Fatoorah Payment
            $supplierCode   = !empty($vendor->supplier_code)?$vendor->supplier_code : '';
            $hesabe         = new MyFatoorahPaymentServices;
            $response       = $hesabe->paymentUrl($order->id,2,$order->total_amount,$order->service_charge,$order->total_amount,$supplierCode,$order->user_id);
            // $response       = $hesabe->paymentUrl($order->id,$order->payment_type,$order->total_amount,$order->service_charge,$order->total_amount,$supplierCode,$order->user_id);
          }
          /**
           * End of Payment Gateway
           */


          DB::commit();

          return $response;

        }elseif($request->payment_type == 5){  //Credit Card & Wallet

          if($request->total_amount == 0){
            $order->payment_status = '2';
            $order->payment_type = 0;
            $order->total_amount = $request->total_amount;
            $order->save();
            DB::commit();
            return response()->json(['paymentUrl' => '','wallet_amount'=>$user->amount,'status'=>1,'message' =>__('message.paymentSuccess')]);
          }

          // $response = $this->paymentUrl((double)$request->total_amount,$order->id,$serviceProviderIds,2);

          // $resArr = [
          //   'status'        => 1,
          //   'message'       => $response['message'],
          //   'order_id'      => $order->id,
          //   'wallet_amount' => $user->amount - $request->wallet_amount,
          //   'paymentUrl'    => (config('app.env') == 'staging') ? $response['data']['paymenturl'].$response['data']['token'] : $response['data']['paymenturl'].$response['data']['token'].$response['data']['ptype']
          // ];

           //Hesabe Payment
           $userRemWallBal = $user->amount - $request->wallet_amount;

          //  $hesabe         = new HesabeOrderPaymentServices;
          //  $response       = $hesabe->setDataForPayment($order,2,$userRemWallBal,$request->total_amount);
           //

          /**
           * Payment Geteway stwich
           */
          $defaultPayment = PaymentGatewaySetting::where('is_default',1)->first();

          if($defaultPayment->id == 1){ //Hesabe Payment
            //Hesabe Payment
            $hesabe         = new HesabeOrderPaymentServices;
            $response       = $hesabe->setDataForPayment($order,2,$userRemWallBal,$request->total_amount);
            //
          }else if($defaultPayment->id == 2){ //Fatoorah Payment
            $supplierCode   = !empty($vendor->supplier_code)?$vendor->supplier_code : '';
            $hesabe         = new MyFatoorahPaymentServices;
            $response       = $hesabe->paymentUrl($order->id,2,$order->total_amount,$order->service_charge,$order->total_amount,$supplierCode,$order->user_id);
             // $response       = $hesabe->paymentUrl($order->id,$order->payment_type,$order->total_amount,$order->service_charge,$order->total_amount,$supplierCode,$order->user_id);
          }
          /**
           * End of Payment Gateway
           */

          DB::commit();

          return $response;

        }elseif($request->payment_type == 0){  //No any payment method selected

          if($request->total_amount == 0){
              //Admin commission calculation
              $serviceProviderDetails = \App\User::find($serviceProviderIds);

              $maakPercent      = !empty($serviceProviderDetails->maak_percentage) ? $serviceProviderDetails->maak_percentage : 0;
              $maakFixedPrice   = !empty($serviceProviderDetails->fixed_price) ? $serviceProviderDetails->fixed_price : 0;
              $commissionAmount = $maakFixedPrice + ((double)$request->total_amount *  $maakPercent) / 100;
              
              //Transaction 
              $order->transaction()->create([
                'service_amount'      => $request->total_amount,
                //'service_provider_id' => !empty($serviceProviderIds) ? $serviceProviderIds : null,
                'service_provider_id' => $order->service_provider_id,
                'maak_percentage'     => $maakPercent,
                'fixed_amount'        => $maakFixedPrice,
                'commission'          => $commissionAmount + $order->user_applicable_fee,
                'knet_fees'           => 0,
                'others_fees'         => 0,
                'net_payable'         => $request->total_amount -  $order->coupon_amount,
                'status'              => '1',
                'user_applicable_fee' => $order->user_applicable_fee,
                'cash_payable'        => $request->total_amount - ( $commissionAmount + $order->user_applicable_fee ), 
                'actual_sp_share'     => $request->total_amount - ( $commissionAmount + $order->user_applicable_fee ),
                'net_sp_share'        => $request->total_amount - ( $commissionAmount + $order->user_applicable_fee ),
                'net_commission'      => ($commissionAmount + $order->user_applicable_fee) - $order->coupon_amount
              ]);  

              $order->revenue()->create([
                'status'    => '1',
                'amount'    => ($maakFixedPrice + $commissionAmount ) - $order->coupon_amount,
                'sp_amount' => $request->total_amount - $commissionAmount
              ]);

              $order->total_amount = $request->total_amount;
              $order->payment_status = '2';
              $order->save();
              
              //send notification to technician
              if(!empty($order->serviceProvider[0]->technician)){
                
                foreach($order->serviceProvider[0]->technician->deviceInfo as $key){
                
                  if(!empty($key->device_token)){
      
                      if($order->user->is_language=='ar'){
                          $title     = 'تم استلام الحجز الجديد';
                          $subject   = 'لقد استلمت الحجز الجديد. يرجى التحقق من تفاصيل الحجز.';
                      }else{
                          $title   = 'New Booking Received';
                          $subject = 'You have received a new booking. Please check booking details.';
                      }

                      \Log::info('notification is working');

                      $this->sendNotification($title,$subject,$key->device_token,'booking_list',$order->id,'2');
              
                  }

                }

              }
               //Mail Invoice
          $userServiceProvider = User::where('id',$order->service_provider_id)->first();

          //Mail send to service provider on secondary mail
          $spUserEmail = new User;
        
          $spUserEmail->email = $userServiceProvider->email ?? $userServiceProvider->secondary_email;
          $spEmail =  $spUserEmail->email;

          dispatch(new SendInvoiceEmailJob($order,$spEmail));

          //Mail to maak admin
          dispatch(new SendInvoiceEmailJob($order,'orders@maak.live'));
          
            DB::commit();
            return response()->json(['paymentUrl' => '','wallet_amount'=>$user->amount,'status'=>1,'message' =>__('message.paymentSuccess')]);
          }

        }

        $message = __('message.paymentSuccess');

        //Specific message for insurance
        if($request->type == 1){
            if($bookServiceData->service_type == 1){
              $message = __('message.comprehensiveInsurance');
            }else{
              $message = __('message.thirdPartyInsurance');
            }
        }

        if($order){
          return response()->json(['wallet_amount'=>$user->amount,'status'=>1,'message' => $message]);
        }else{
          return response()->json(['status'=>0,'message' =>'Internal error']);
        }


    }catch (\Exception $e) {
      throw $e;
      \Log::info($e);
      DB::rollBack();
      return response()->json(['status' => 0 ,'message' => 'something went wrong'],200);
   
    }

  }

  /**
   * Payment url generation when successfully checkout
   */
  public function paymentUrl($amount=1,$orderId=null,$serviceProviderId=null,$pType=1)
  {

    try{

      $data = [
        'MerchantCode' => config('app.payment_merchant_code'), 
        'Amount'       =>  str_replace(',','',number_format($amount,3)),
        'Ptype'        =>  $pType,  //1 : Knet 2: Card
        'SuccessUrl'   => url('/payment/success'),
        'FailureUrl'   => url('/payment/failure'),
        'Variable1'    => $orderId,
        'Variable2'    => $amount,
        'Variable3'    => !empty($serviceProviderId) ? $serviceProviderId : null,
        'Variable4'    => 0,
        'Variable5'    => 0

      ];

      $client   = new \GuzzleHttp\Client();
    
      $url      = config('app.payment_url'). "?". http_build_query($data);
    
      $res      = $client->post($url);

      $response = json_decode((string)$res->getBody(), true);
     
      if(!empty($response) && $response['status'] == 'success'){
          return $response;
      }else{
          return false;
      }

    }catch (\Exception $e) {
      return response()->json(['Payment gateway not responding'],200);
    } 

  }

  public function orderDelete(Request $request)
  {
    $validator= Validator::make($request->all(),[
      'order_id'   => 'required|exists:orders,id'
    ]);

    if($validator->fails()){
      return response()->json(Api::apiValidationResponse($validator->errors()->first()),422);
    }

    $order = \App\Models\Orders::where('id',$request->order_id)->delete();

    return response()->json(Api::apiSuccessResponse('Order deleted successfully'));

  }

  public function orderAacknowledge(Request $request)
  {
    $validator= Validator::make($request->all(),[
      'order_id'       => 'required|exists:orders,id',
      'is_acknowledge' => 'required|in:0,1'
    ]);

    if($validator->fails()){
      return response()->json(Api::apiValidationResponse($validator->errors()->first()),422);
    }

    $order = \App\Models\Orders::where('id',$request->order_id)->first();
    $order->is_acknowledge = $request->is_acknowledge;
    $order->save();

    return response()->json(Api::apiSuccessResponse('Order Acknowledged successfully'));

  }




  /**
   * Insurance type api details
   */
  public function insurancetypewithcms(Request $request)
  {
    if($request->isMethod('post')){
      $validator= Validator::make($request->all(),[
        'user_id'=>'required',
        'service_provider_id'=>'required|exists:users,id',
      ]);
      if($validator->fails()){
        return response()->json(['status'=>0,'message' => $validator->errors()->first()]);
      }
      $name = "name_".app()->getLocale();
      $description = "description_".app()->getLocale();
      $insuranceTypes = Insurancetype::get();
      $csmdata = Cms::where(['user_id'=>$request->service_provider_id,'slug'=>'privacy_policy'])->first();
      $typesInsurance=[];
      if($insuranceTypes){
        foreach ($insuranceTypes as $insuranceType) {
          $typesInsurance[] =['id'=>$insuranceType['id'],'name'=>$insuranceType[$name]];
        }
      }
      return response()->json(['status'=>1,'message' =>'success.','privacy_policy'=>isset($csmdata['privacy_policy'])?$csmdata['privacy_policy']:env('PRIVACY_URL'),'insurance_type'=>$typesInsurance]);
    }
  }

}
