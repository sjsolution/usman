<?php
namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\Models\Service;
use App\Models\Vehicletype;
use App\Models\Vehicles;
use App\Models\TimeSlot;
use App\Models\SpTimeSlot;
use App\Models\TechnicianTimeSlot;
use App\Models\Toprated;
use Illuminate\Support\Facades\Validator;
use App\Traits\CommonTrait;
use App\Http\Resources\User as UserResource;
use App\Http\Resources\ServiceProviderDetailResource;
use App\Http\Resources\ServiceProviderListResource;
use DB;
use App\ListType;
use App\Http\Resources\ListTypeResource;
use App\Models\Spcountrycity;
use App\Models\Ratings;


class ServiceprovidersController extends Controller
{

  use CommonTrait;

  public function __construct(
    ListType               $listType,
    Spcountrycity          $spCountryCity,
    Service                $service,
    Ratings                $rating
  )
  {
    $this->listType      = $listType;
    $this->spCountryCity = $spCountryCity;
    $this->service       = $service;
    $this->rating        = $rating;
  }

  public function timeslotwithvehicleType(Request $request)
  {
    $name = "name_".app()->getLocale();
    $vehicleTypes = Vehicletype::where(['is_active'=>1])->get();
    $timeslotData = $this->slotperiods();
    $vehicleData=[];
    foreach ($vehicleTypes as $vehicleType) {
        $vehicleData[]=['id'=>$vehicleType->id,'name'=>$vehicleType->$name,'image'=>isset($vehicleType->image)?$vehicleType->image:''];
    }
    $resultedData=['vehicle_type'=>$vehicleData,'total_time_slot'=>$timeslotData];
    return response()->json(['status'=>1,'message' =>'success','data'=>$resultedData]);
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

   /**
   * Service provide listing expcept insurance type
   */
  public function getServiceProviders(Request $request)
  {

    
    $validator= Validator::make($request->all(),[
      'user_id'            => 'required',
      'vehicle_type'       => 'required|exists:vehicle_type,id',
      'category_id'        => 'required|exists:categories,id',
      'model_id'           => 'required',
      'brand_id'           => 'required'
    ]);

    if($validator->fails()){
      return response()->json(['status'=>0,'message' => $validator->errors()->first()],422);
    } 


    $latitude      = !empty($request->latitude)  ? $request->latitude  : 0;
    $longitude     = !empty($request->longitude) ? $request->longitude : 0;
    $offset        = isset($request->offset)     ? $request->offset:0;
    $limit         = isset($request->limit)      ? $request->limit:100;
    $date          = !empty($request->date)      ? $request->date : \Carbon\Carbon::now()->format('Y-m-d');
    $time          = !empty($request->time)      ? $request->time : \Carbon\Carbon::now()->format('H:i');
    // $rating        = !empty($request->rating)    ? $request->rating : 6;
    $bookingDate   = $date;
    $bookingTime   = $time;

    $d             = new \DateTime($date);
    $dayId         = $this->getDayId($d->format('l'));
  // dd($dayId);
    $qry = "SELECT *, (3959 * acos(cos(radians(".$latitude.")) * cos(radians(latitude)) * cos( radians(longitude) - radians(".$longitude.")) + sin(radians(".$latitude.")) * sin(radians(latitude)))) 
            AS distance 
            FROM city_areas 
            HAVING distance < 5
            ORDER BY distance LIMIT 0 , 10";

    $lat_long_subquery = $this->spCountryCity->join('sp_country_city_area','sp_country_city_area.country_city_id', '=', 'sp_country_city.id')
          ->join('city_areas','city_areas.id', '=', 'sp_country_city_area.area_id')
          ->join(DB::Raw("({$qry}) AS lat_long_sp"),'lat_long_sp.id', '=', 'city_areas.id')
          ->groupBy('user_id')
          ->selectRaw('user_id as usr_id');
    $service_subquery = $this->service
        ->where('category_id',$request->category_id)
        ->whereExists(function($query) use ($request){
          $query->select("service_vehicles.id")
              ->from('service_vehicles')
              ->whereRaw('service_vehicles.service_id = service.id')
              ->where('vehicle_type_id',$request->vehicle_type)
              ->where('status','1');
        })
        ->groupBy('user_id')

          ->whereRaw('service.type != 2')
          ->selectRaw('user_id as user_ids,MIN(service.amount) as min_service_price,MAX(service.amount) as max_service_price');

    $rating_subquery = $this->rating->groupBy('service_provider')
          ->selectRaw('service_provider as service_provider_id,CAST(AVG(rating) AS DECIMAL(10,1)) as sp_rating,sum(id) as rating_count');

    $subQuery  = \App\SPTechnician::query();

    $subQuery  =  $subQuery->join('technician_time_slot',
    'technician_time_slot.technician_id', '=', 's_p_technicians.technician_id')
      ->whereRaw('technician_time_slot.day_id="'.$dayId.'"')
      ->whereRaw('technician_time_slot.is_active = 1')
      ->whereRaw('technician_time_slot.start_time <= "'.$request->time.':59"')
      ->whereRaw('technician_time_slot.end_time > "'.$request->time.':00"')
      ->whereNotExists(function($q) use ($bookingDate,$bookingTime){
        $q->selectRaw(1)
          ->from('booked_technicians')
          ->whereRaw('booked_technicians.technician_id = s_p_technicians.technician_id')
          // ->whereRaw('booked_technicians.service_provider_id = s_p_technicians.id')
          ->whereRaw('booked_technicians.status = "1"')
          ->whereRaw('booked_technicians.booking_date = "'.$bookingDate.'" ')
          ->whereRaw('booked_technicians.booking_time <= "'.$bookingTime.':59" ')
          ->whereRaw('booked_technicians.booking_end_time > "'.$bookingTime.':00" ');
      });

    $subQuery  =  $subQuery->groupBy('s_p_technicians.user_id')
      ->selectRaw('COUNT(*) AS technician, s_p_technicians.user_id as tech_userid');
    
    $providers = User::where('user_type','1')->where('is_active',1)->where('is_service_active',1)
      ->whereNull('created_by')
      ->join(DB::Raw("({$service_subquery->toSql()}) AS freelancer_services"),'freelancer_services.user_ids', '=', 'users.id')
      ->addBinding($service_subquery->getBindings(),'join')
      ->leftjoin(DB::Raw("({$rating_subquery->toSql()}) AS service_provider_ratings"),'service_provider_ratings.service_provider_id', '=', 'users.id')
      ->join(DB::Raw("({$lat_long_subquery->toSql()}) AS lat_long_services"),'lat_long_services.usr_id', '=', 'users.id')
      ->leftjoin('s_p_list_rankings as splr',function($q) use ($request){
        $q->on('splr.user_id','=','users.id')
        ->where('category_id',$request->category_id)
        ->where('list_type_id',$request->list_type_id);
      })  
      ->leftjoin(DB::Raw("({$subQuery->toSql()}) AS technician_availibility"),'technician_availibility.tech_userid', '=', 'users.id')
      ->groupBy('users.id')
      ->with(['userlocation','ratingreviews','startingprice','servicetime','spRatings'])
      ->whereHas('userServices',function($query) use ($request) {
        $query->where('is_active','1')
            ->where('category_id',$request->category_id)
            #change by sanjiv
            ->when(!empty($request->subcategory_id),function($query) use ($request){
              $query->where('sub_category_id',$request->subcategory_id);
            })
            ->whereHas('vehicleType',function($j) use ($request) {
                $j->where('vehicle_type_id',$request->vehicle_type);
            })
            //->where('vehicle_type_id',$request->vehicle_type)
            ->orderBy('amount','desc');
      }) 
      // ->whereHas('userTimeSlot',function($query) use ($request,$dayId) { //Check sp timeslot range
      //   $query->where('day_id','=',$dayId)
      //     ->whereTime('start_time','<=',$request->time.':59')
      //     ->whereTime('end_time','>',$request->time.' 00'); 
      // })
      ->addSelect('technician_availibility.technician','users.*','freelancer_services.min_service_price as min_price','service_provider_ratings.sp_rating','service_provider_ratings.rating_count',
      \DB::raw('IF ( technician_availibility.technician IS NULL, 0,1 ) AS is_available')
      );


      if($request->has('sortBy') && $request->sortBy == 1){            //Sort by price low to high
        $providers = $providers->orderBy('freelancer_services.min_service_price','asc');
      }else if($request->has('sortBy') && $request->sortBy == 2){      //Sort by price high to low
        $providers = $providers->orderBy('freelancer_services.min_service_price','desc');
      }else if($request->has('sortBy') && $request->sortBy == 3){      //Sort by alphabetic order
        $providers = $providers->orderBy('users.full_name_en','asc');
      }else if($request->has('sortBy') && $request->sortBy == 4){      //Sort by relevance
        $providers = $providers->orderBy('users.full_name_en','asc');
      }else{
        // $providers = $providers->orderBy('splr.rank','asc');
        // $providers = $providers->orderBy('freelancer_services.min_service_price','asc');
      }
       

       //when filter apply Filter
      if($request->has('filter') && $request->filter == 1){

        if($request->has('price_range') && !empty($request->price_range) ){

          $priceRange       = explode('-',$request->price_range);
          $price_range_from = isset($priceRange[0])    ? $priceRange[0] : 0;
          $price_range_to   = isset($priceRange[1])    ? $priceRange[1] : 1000;
          $providers = $providers
               ->whereBetween('freelancer_services.min_service_price',[(double)$price_range_from,(double)$price_range_to])
                ->orderBy('splr.rank','asc');

             
        }
        if($request->has('rating') && !empty($request->rating) ){
            $providers = $providers
                  ->where('service_provider_ratings.sp_rating','<', (double)($request->rating))
                  ->orderBy('splr.rank','asc');
          }
        }
      

      if($request->category_type == 3){
        $providers = $providers->whereHas('userEmergencyTimeSlot',function($query) use ($bookingTime,$dayId) {
          $query->where('day_id','=',$dayId)
            ->whereTime('start_time','<=',$bookingTime.':59')
            ->whereTime('end_time','>',$bookingTime.' 00'); 
        });
      } 
        if((!empty($request->list_type_id) && $request->list_type_id=='1')){
             $providers = $providers->orderBy('freelancer_services.min_service_price','asc');
         }
         
      $providers = $providers->get();
      // echo"<pre>";print_r($providers->toArray());die;
      if((!empty($request->list_type_id) && ($request->list_type_id=='2') || $request->list_type_id=='3')){
       
        $providers =$providers->sortByDesc('sp_rating');
                  
          }
         if((!empty($request->list_type_id) && $request->list_type_id=='3')){
        
            $providers =$providers->sortByDesc('rating_count');
  
          }

      if($providers->count()){
        
        $providers  = $providers;
         
      
      }else{
        
        $providers = User::where('user_type','1')->where('is_active',1)
          ->where('is_technician','1')
          ->whereNull('created_by')
          ->join(DB::Raw("({$service_subquery->toSql()}) AS freelancer_services"),'freelancer_services.user_ids', '=', 'users.id')
          ->addBinding($service_subquery->getBindings(),'join')
          ->leftjoin(DB::Raw("({$rating_subquery->toSql()}) AS service_provider_ratings"),'service_provider_ratings.service_provider_id', '=', 'users.id')
          ->join(DB::Raw("({$lat_long_subquery->toSql()}) AS lat_long_services"),'lat_long_services.usr_id', '=', 'users.id')
          ->leftjoin('s_p_list_rankings as splr',function($q) use ($request){
            $q->on('splr.user_id','=','users.id')
              ->where('category_id', $request->category_id)
              ->where('list_type_id',$request->list_type_id);
          })
          ->groupBy('users.id')
          ->with(['userlocation','ratingreviews','startingprice','servicetime','spRatings'])
          ->whereHas('userServices',function($query) use ($request) {
            $query->where('is_active','1')
                ->where('category_id',$request->category_id)
                ->whereHas('vehicleType',function($j) use ($request) {
                  $j->where('vehicle_type_id',$request->vehicle_type);
                })
                // ->where('vehicle_type_id',$request->vehicle_type)
                ->orderBy('amount','desc');
          })
          // ->whereHas('userTimeSlot',function($query) use ($request,$dayId) {
          //   $query->where('day_id','=',$dayId)
          //     ->whereTime('start_time','<=',$request->time.':59')
          //     ->whereTime('end_time','>',$request->time.' 00')
          //     ->whereNotExists(function($qu) use ($request,$dayId){
          //       $qu->selectRaw(1)
          //       ->from('sp_time_slot')
          //       ->where('sp_time_slot.day_id','=',$dayId)
          //       ->whereRaw('sp_time_slot.is_active = 1')
          //       ->whereRaw('sp_time_slot.break_from <= "'.$request->time.':59"')
          //       ->whereRaw('sp_time_slot.break_to > "'.$request->time.':00"');
          //     });
          // })
          // ->whereDoesntHave('bookedTechnicanAsServiceProviders',function($query) use ($bookingDate,$bookingTime){
          //   $query->where('booking_date','=',$bookingDate)
          //         ->whereTime('booking_time','<=',$bookingTime.':59')
          //         ->whereTime('booking_end_time','>',$bookingTime.':00')
          //         ->where('status','1');
          // })
          ->addSelect('users.*','freelancer_services.min_service_price as min_price','service_provider_ratings.sp_rating','service_provider_ratings.rating_count');
          // dd($providers->get());
      

          if($request->has('sortBy') && $request->sortBy == 1){            //Sort by price low to high
            $providers = $providers->orderBy('freelancer_services.min_service_price','asc');
          }else if($request->has('sortBy') && $request->sortBy == 2){      //Sort by price high to low
            $providers = $providers->orderBy('freelancer_services.min_service_price','desc');
          }else if($request->has('sortBy') && $request->sortBy == 3){      //Sort by alphabetic order
            $providers = $providers->orderBy('users.full_name_en','asc');
          }else if($request->has('sortBy') && $request->sortBy == 4){      //Sort by relevance
            $providers = $providers->orderBy('users.full_name_en','asc');
          }else{
            // $providers = $providers->orderBy('splr.rank','asc');
            // $providers = $providers->orderBy('freelancer_services.min_service_price','asc');
          }


           //when filter apply Filter
           if($request->has('filter') && $request->filter == 1){

        if($request->has('price_range') && !empty($request->price_range) ){

          $priceRange       = explode('-',$request->price_range);
          $price_range_from = isset($priceRange[0])    ? $priceRange[0] : 0;
          $price_range_to   = isset($priceRange[1])    ? $priceRange[1] : 1000;
          $providers = $providers
               ->whereBetween('freelancer_services.min_service_price',[(double)$price_range_from,(double)$price_range_to])
                ->orderBy('splr.rank','asc');
        }
        if($request->has('rating') && !empty($request->rating) ){
            $providers = $providers
                  ->where('service_provider_ratings.sp_rating','<', (double)($request->rating))
                  ->orderBy('splr.rank','asc');
          }
        }
      

      if($request->category_type == 3){
        $providers = $providers->whereHas('userEmergencyTimeSlot',function($query) use ($bookingTime,$dayId) {
          $query->where('day_id','=',$dayId)
            ->whereTime('start_time','<=',$bookingTime.':59')
            ->whereTime('end_time','>',$bookingTime.' 00'); 
        });
      } 
          // if($request->category_type == 3){
          //   $providers = $providers->whereHas('userEmergencyTimeSlot');
          // }
          
          $providers = $providers->get();    
        if((!empty($request->list_type_id) && ($request->list_type_id=='2') || $request->list_type_id=='3')){
           
          $providers =$providers->sortByDesc('sp_rating');
           if($request->list_type_id=='3'){
            $providers =$providers->sortByDesc('rating_count');
            }
                  
          }
              
      }

    $servicePriceRange =  \DB::table('service')->where('category_id',$request->category_id) 
        ->select( 
          \DB::raw("MIN(service.amount) AS min_amount"), 
          \DB::raw("MAX(service.amount) AS max_amount")
        )
        ->first();

    if($providers->count()){

      return response()->json([
        'status'              => 1,
        'message'             => 'success',
        'top_rated_category'  => '',
        'listType'            => ListTypeResource::collection($this->listType->where('status',1)->orderBy('rank')->get()),
        'service_providers'   => ServiceProviderListResource::collection($providers),
        'filter'              => [
          'minPriceRange' => $servicePriceRange->min_amount > 0 ? $servicePriceRange->min_amount : "1",
          'maxPriceRange' => $servicePriceRange->max_amount > 0 ? $servicePriceRange->max_amount : "500"
        ]
      ]);  

    }else{

      return response()->json([
        'status'              => 0,
        'message'             => __('message.notRecordFound'),
        'top_rated_category'  => '',
        'listType'            => ListTypeResource::collection($this->listType->where('status',1)->orderBy('rank')->get()),
        'service_providers'   => [],
        'filter'              => [
          'minPriceRange' => $servicePriceRange->min_amount > 0 ? $servicePriceRange->min_amount : "1",
          'maxPriceRange' => $servicePriceRange->max_amount > 0 ? $servicePriceRange->max_amount : "500"
        ]
      ]); 

    }

  } 

  public function serviceDetails(Request $request)
  {
    if($request->isMethod('post')){

      $validator= Validator::make($request->all(),[
        'user_id'=>'required|exists:users,id',
        'service_id'=>'required|exists:service,id',
      ]);
      if($validator->fails()){
        return response()->json(['status'=>0,'message' => $validator->errors()->first()]);
      }
      $full_name = "full_name_".app()->getLocale();
      $name = "name_".app()->getLocale();
      $description = "description_".app()->getLocale();
      $serviceDetails=Service::with('category','subcategory','servicedescription','serviceaddonsdata')->find($request->service_id);
      $serviceData['id'] = $serviceDetails['id'];
      $serviceData['name'] = isset($serviceDetails[$name])?$serviceDetails[$name]:'';
      $serviceData['image'] = isset($serviceDetails['image'])?$serviceDetails['image']:'';
      $serviceData['amount'] = $serviceDetails['amount'];
      $serviceData['time_duration'] = ($serviceDetails['time_type'] =='hours')?$serviceDetails['time_duration'].'Hours':$serviceDetails['time_duration'].'Minutes';
      $serviceData['category'] = !empty($serviceDetails['category'][$name])?$serviceDetails['category'][$name]:'';
      $serviceData['sub_category'] = !empty($serviceDetails['subcategory'][$name])?$serviceDetails['subcategory'][$name]:'';
      $descption = [];

      if(count($serviceDetails['servicedescription'])>0){
        foreach ($serviceDetails['servicedescription'] as $key => $value) {
          $descption[]= ['description'=>$value[$description]];
        }
      }
      $addons =[];

      if(count($serviceDetails['serviceaddonsdata'])>0){
        foreach ($serviceDetails['serviceaddonsdata'] as $key1 => $value1) {
          $addons[]= [
            'id'=>$value1['id'],
            'name'=>$value1[$name],
            'description'=>$value1[$description],
            'amount'=>$value1['amount'],
          ];
        }
      }

      $serviceData['addons'] = $addons;
      $serviceData['description'] = $descption;
      return response()->json(['status'=>1,'message' =>$serviceData]);

    }

  }
 
  public function serviceproviderdetails(Request $request)
  {
    \Log::info('Service Details');
    \Log::info($request->all());

    $validator= Validator::make($request->all(),[
      'user_id'              => 'required',
      'service_provider_id'  => 'required|exists:users,id',
      'category_id'          => 'required|exists:categories,id',
      'vehicle_type'         => 'required'
      // 'category_type'        => 'required|in:1,2,3', //1=Normal, 2=Insurance , 3=Emergency
    ]);

    if($validator->fails()){
      return response()->json(['status'=>0,'message' => $validator->errors()->first()]);
    } 

    $category = \App\Models\Category::where('id',$request->category_id)->first();

    //For handling insurance category
    if($category->type == 2){

      $validator= Validator::make($request->all(),[
        'service_type'         => 'required|in:1,2', //1:full ,2 third party ,3 both
      ]);

      if($validator->fails()){
        return response()->json(['status'=>0,'message' => $validator->errors()->first()]);
      } 

    } 
 
  
    $user = User::with('service')->where('id',$request->service_provider_id)->get();
  
    return response()->json([
      'status'   => 1,
      'message'  => 'success',
      'provider' => ServiceProviderDetailResource::collection($user)
    ],200);
  
  } 

  public function getsubcategoryservices(Request $request)
  {
    if($request->isMethod('post')){
        $validator= Validator::make($request->all(),[
          'user_id'=>'required',
          'service_provider_id'=>'required|exists:users,id',
          'sub_category_id'=>'required|exists:categories,id',
        ]);
        if($validator->fails()){
          return response()->json(['status'=>0,'message' => $validator->errors()->first()]);
        }
        $name = "name_".app()->getLocale();
        $full_name = "full_name_".app()->getLocale();
        $description = "description_".app()->getLocale();
        $special_note = "special_note_".app()->getLocale();
        $providerDetails = Service::with('addons','servicedescription')->where(['user_id'=>$request->service_provider_id,'sub_category_id'=>$request->sub_category_id])->get();
        $services =[];
        foreach ($providerDetails as $key => $service) {
          $sevicedess=[];
            foreach ($service['servicedescription'] as $keys=>$servicedescription) {
            $sevicedess[$keys]=$servicedescription[$description];
            }
            $addons=[];
            foreach ($service['addons'] as $serviceaddons) {
            $addons[]=[
                        'id'          =>$serviceaddons['id'],
                        'name'        =>$serviceaddons[$name],
                        'description' =>$serviceaddons[$description],
                        'amount'      =>$serviceaddons['amount'],
                      ];
            }
            /***************Services********************/
          $services[]=[
                      'id'          =>$service['id'],
                      'name'        =>$service[$name],
                      'special_note'=>$service[$special_note],
                      'amount'      =>$service['amount'],
                      'duration'    =>$service['time_duration']." ".$service['time_type'],
                      'sub_category_id'=>$service['sub_category_id'],
                      'description' =>$sevicedess,
                      'addons'      =>$addons,
                    ];
        }
        if($services){
          return response()->json(['status'=>1,'message' =>'success','services'=>$services]);
        }else{
          return response()->json(['status'=>0,'message' =>__('message.notRecordFound')]);
        }

    }
  }

}
