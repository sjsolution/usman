<?php
namespace App\Http\Controllers\ServiceProvider;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use User;
use Auth;
use App\Models\Service;
use App\Models\Servicedescription;
use App\Models\Category;
use App\Models\Vehicletype;
use App\Models\Serviceaddons;
use App\Models\Serviceprovidercategory;
use App\Models\Serviceprovidersubcategory;
use Yajra\Datatables\Datatables;



class ServiceController extends Controller
{

  public function __construct(Service $service)
  {
    $this->service = $service;
  }

  public function index()
  {
    
    return view('services.getservicelist');
  }

  public function servicelisting(Request $request)
  {

    $service = $this->service->with(['category','subcategory'])->where(['user_id'=>Auth::user()->id])->orderBy('id','desc')->get();
      
    return Datatables::of($service)
        ->addColumn('category',function($service){
            return $service->category->name_en;
         })
         ->addColumn('sub_category',function($service){
           if(!empty($service->subcategory)) 
            return $service->subcategory->name_en;
           else 
            return '--';
         })
         ->addColumn('amount',function($service){
          $amount = ($service->type == 2) ? $service->insurance_percentage.'%' :  $service->amount.' KWD';
          return $amount;
          if(!empty($service->subcategory)) 
           return $service->subcategory->name_en;
          else 
           return '--';
        })
         ->addColumn('status',function($service){
          if($service->is_active == 1)
              return '<button type="button" class="btn btn-gradient-primary btn-sm" onclick="changeStatus(event,'.$service->id.',0)">Active</button>'; 
          else
              return '<button type="button" class="btn btn-gradient-danger btn-sm" onclick="changeStatus(event,'.$service->id.',1)">In-active</button>'; 
        })
        ->addColumn('action',function($service){
          return "<a href='servicedetails/".$service->id."' class='btn btn-gradient-danger btn-sm' title='view'><i class='fa fa-eye'></i></a>
                <a href='updateservice/".$service->id."' class='btn btn-sm btn-gradient-info' title='Edit'><i class='fa fa-pencil'></i></a>"; 
        })
        ->make(true);
       
  }

  public function createservice(Request $request)
  {  

    if($request->isMethod('post'))
    {
      $category = Category::where('parent_id',0)->where('id',$request->category)->first();
      $user = Auth::user();
      $service = new Service;
      $service->name_en        = $request->title_en ;
      $service->name_ar        = $request->title_ar ;
      $service->type           = $category->type;
      $service->category_id    = $request->category;
      $service->sub_category_id= $request->sub_category;
      $service->amount         = isset($request->amount)?$request->amount:0;
      $service->service_type   = $request->serviceType;
      $service->special_note_en= isset($request->special_note_en)?$request->special_note_en:'';
      $service->special_note_ar= isset($request->special_note_ar)?$request->special_note_ar:'';
      $service->vehicle_type_id= null;
      $service->insurance_percentage = !empty($request->premium_percentage) ? $request->premium_percentage : 0.0;
      $service->fixed_price    = !empty($request->fixed_price) ? $request->fixed_price : 0.0;
      $service->user_id        = $user->id;
      $time=$request->time_duration;
      if($request->time_type =='hours'){
        //$time=$time*60;
        $service->time_duration = $time;
        $service->time_type=$request->time_type;
      }else{
        $service->time_duration = $time;
        $service->time_type=$request->time_type;
      }
      $service->save();
      if(!empty($request->vehicle_type)){
        $service->vehicleType()->update(['status' => 0]);
        for($i=0; $i<count($request->vehicle_type); $i++){
          $service->vehicleType()->create([
            'vehicle_type_id' => $request->vehicle_type[$i],
            'status'          => 1
          ]);

        }

      }
      if($request->description_en !=null){
        foreach($request->description_en as $key=>$endescription){
          $servicedescription =new Servicedescription;
          $servicedescription->description_en=$endescription;
          $servicedescription->description_ar=$request->description_ar[$key];
          $servicedescription->service_id=$service->id;
          $servicedescription->save();
        }
      }
      if($request->addons_name_en !=null){
        foreach($request->addons_name_en as $key1=>$addons){
          $addonsdata =new Serviceaddons;
          $addonsdata->name_en=$addons;
          $addonsdata->name_ar=$request->addons_name_ar[$key1];
          $addonsdata->amount=$request->addons_amount[$key1];
          $addonsdata->service_id=$service->id;
          $addonsdata->save();
        }

      }

     

      if($user->setup_complete != '1'){

        $user->setup_complete = '3';
        $user->save();
        
        toast('Please add your first technician.','question')->timerProgressBar();

        return redirect('serviceprovider/technician/create');
          
      }

      return redirect('serviceprovider/servicelist')->with('message', 'Service has been created succesfully!');
    
    }

    if(Auth::user()->setup_complete != '1' &&  Auth::user()->setup_complete == '2'){
    
      toast('Please create your first service!','question')->timerProgressBar();

    } 
 
    $vehilce_type = Vehicletype::where('is_active','1')->get();
    $categories   = Category::whereHas('serviecategory',function($q){
        $q->where('user_id',Auth::user()->id);
    })->where('parent_id',0)->get();
   
    return view('services.createservices',compact('vehilce_type','categories'));

  }

  public function getcategory($status)
  {
    $category = Serviceprovidercategory::with(['categoryname'=>function($query)use ($status){
        $query->where('parent_id','0');
        $query->where('type',$status);
    }])
    ->whereUserId(Auth::user()->id)
    ->get();

      $option='<option value=""> Select category </option>';

      if(!empty($category)){
        foreach($category as $cate){
          if(!empty($cate['categoryname']['id'])){
            $option.='<option value="'.$cate['categoryname']['id'].'"> '.$cate['categoryname']['name_en'].'</option>';
          }

        }
      }

      return response()->json(array('status'=>'success','option' => $option));
  }

  public function getsubcategory($service)
  {

    if(!empty($service)){

      $subcategory = Serviceprovidersubcategory::with(['categoryname'=>function($query)use ($service){
              $query->where('parent_id',$service);
      }])
      ->whereUserId(Auth::user()->id)
      ->get();

      $option='<option value=""> Select category </option>';
      $count = 0;

      if(!empty($subcategory)){
        $i = 0;
        foreach($subcategory as $cate){
          if(!empty($cate['categoryname']['id'])){
            $i++;
            $option.='<option value="'.$cate['categoryname']['id'].'"> '.$cate['categoryname']['name_en'].'</option>';
          }

        }

        $count = $i;

      }

      return response()->json(array('status'=>'success','option'=>$option,'count' => $count));

    }

  }
 
  public function getServiceDetails($id)
  {
    $details = Service::with('servicedescription','serviceaddonsdata','category','subcategory','vehicle')->find($id);
    $vehicleNames = [];
    foreach($details->vehicleType as $item){
      $vehicleNames[] = $item->vehicle->name_en;
    }
    return view('services.servicedetails',compact('details','vehicleNames'));
  }

  public function Updateservice(Request $request,$id)
  {

    $details = Service::with('servicedescription','serviceaddonsdata')->find($id);
    
    if($request->isMethod('post'))
    {
      
      // $validator= Validator::make($request->all(),[
      //     'title_en'      =>'required|max:50',
      //     'title_ar'      =>'required|max:50',
      //     'type'          =>'required',
      //     'vehicle_type'  =>'required',
      //     'category'      =>'required',
      //     'amount'        =>'required|numeric',
      //     'time_duration' =>'required|numeric',
      //     'TimeFormat'    =>'required',
      //     'serviceType'   =>'required',
      // ]);
      //
      // if($validator->fails()){
      //   return back()->with('error',$validator->errors()->first());
      // }

      $category = Category::where('parent_id',0)->where('id',$request->category)->first();

      $userid = Auth::user()->id;
      $details->name_en        = $request->title_en ;
      $details->name_ar        = $request->title_ar ;
      $details->type           = $category->type;
      // $details->category_id    = $request->category;
      // $details->sub_category_id= $request->sub_category;
      //
      $details->category_id    = $request->category;
      $details->sub_category_id= $request->sub_category;
      $details->amount         = isset($request->amount)?$request->amount:0;
      $details->service_type   = $request->serviceType;
      $details->special_note_en= isset($request->special_note_en)?$request->special_note_en:'';
      $details->special_note_ar= isset($request->special_note_ar)?$request->special_note_ar:'';
      $details->vehicle_type_id = null;
      $details->insurance_percentage = !empty($request->premium_percentage) ? $request->premium_percentage : 0.0;
      $details->fixed_price          = !empty($request->fixed_price) ? $request->fixed_price : 0.0;

      $details->user_id        = $userid;
      if($request->TimeFormat =='hours'){
        //$time=$time*60;
        $details->time_duration = $request->time_duration;
        $details->time_type=$request->TimeFormat;
      }else{
        $details->time_duration = $request->time_duration;
        $details->time_type=$request->TimeFormat;
      }
      $details->save();
      if(!empty($request->vehicle_type)){
        $details->vehicleType()->update(['status' => 0]);
        for($i=0; $i<count($request->vehicle_type); $i++){
          $details->vehicleType()->create([
            'vehicle_type_id' => $request->vehicle_type[$i],
            'status'          => 1
          ]);
        }
      }

      if($request->description_en !=null){
        Servicedescription::where(['service_id'=>$details->id])->delete();
      }

      if($request->description_en !=null){
        foreach($request->description_en as $key=>$endescription){
          $ardesc = isset($request->description_ar[$key])?$request->description_ar[$key]:'';
            if(!empty($endescription)){
              $servicedescription= new Servicedescription;
              $servicedescription->description_en=$endescription;
              $servicedescription->description_ar=$ardesc;
              $servicedescription->service_id=$details->id;
              $servicedescription->save();
            }
        }
      }

      if($request->addons_name_en !=null){
        Serviceaddons::where('service_id',$details->id)->delete();
      }

      if($request->addons_name_en !=null){
        foreach($request->addons_name_en as $key1=>$addons){
          if(!empty($addons)){
            $addonsdata =new Serviceaddons;
            $addonsdata->name_en=$addons;
            $addonsdata->name_ar=$request->addons_name_ar[$key1];
            $addonsdata->amount=$request->addons_amount[$key1];
            // $addonsdata->description_en=$request->addons_description_en[$key1];
            // $addonsdata->description_ar=$request->addons_description_ar[$key1];
            // if($request->addons_time_type =='hours'){
            //   $addonsdata->time_duration = $request->addons_time_duration[$key1];
            //   $addonsdata->time_type=$request->addons_time_type[$key1];
            // }else{
            //   $addonsdata->time_duration = $request->addons_time_duration[$key1];
            //   $addonsdata->time_type=$request->addons_time_type[$key1];
            // }
            $addonsdata->service_id=$details->id;
            $addonsdata->save();
          }
        }
      }
      return redirect('serviceprovider/servicelist')->with('message', 'Service has been updated succesfully!');
    }
    $ucb = [];
    $vehicle_type    = Vehicletype::where('is_active','1')->get();
    // $query = new Category
    // $query->where('parent_id','0');
    // if($request->type == 1){
    //     $query->where()
    //     $query->where('type',$request->type);
    // }if($request->type == 2){
    //     $query->where('parent_id','0');
    // }
    // if($request->type == 3){
    //     $query->where('parent_id','0');
    // }
    // $categories      =  $query->get();
    $categories      = Category::where('parent_id','0')/*->where('type',$details->type)*/->whereExists(function ($query) {

        $query->select("service_provider_with_category.id")

              ->from('service_provider_with_category')

              ->whereRaw('service_provider_with_category.category_id = categories.id')->where("service_provider_with_category.user_id",Auth::id());

    })->get();
    
    $serviceVehicle  = $details->vehicleType()->get();

    foreach($serviceVehicle as $uc)
    {
      $ucb[] = $uc->vehicle_type_id;
    }

// echo"<pre>";print_r($categories->toArray());die();
    return view('services.editservice',compact('vehicle_type','details','categories','ucb'));
  }


  public function deleteaddons(Request $request)
  {
    if($request->isMethod('post')){
      $update=['is_delete'=>'1'];
      $success = Serviceaddons::where('id',$request->addonsid)->update($update);
      if($success){
        return response()->json(array('status'=>1,'message' =>'Addons has been deleted succesfully.'));
      }else{
        return response()->json(array('status'=>1,'message' =>'Internal error.'));
      }
    }
  }
  public function deletedescription(Request $request)
  {
    if($request->isMethod('post'))
    {
      $exist = Servicedescription::where('service_id',$request->service_id)->count();
      if($exist >1){
        $spdesc = Servicedescription::find($request->descid)->delete();
        if($spdesc){
          return response()->json(array('status'=>1,'message' =>'Description has been deleted successfully.'));
        }else{
          return response()->json(array('status'=>1,'message' =>'Internal error.'));
        }
      }else{
          return response()->json(array('status'=>0,'message' =>'Atleast one description is required to fill.'));
      }
    }
  }

  public function changeStatus(Request $request)
  {
    $serviceprovider=Service::find($request->service_id);
    $serviceprovider->is_active =$request->status;
    $serviceprovider->save();
    return response()->json(['msg' => 'status successfully changed','status' => true]);
  }

}
