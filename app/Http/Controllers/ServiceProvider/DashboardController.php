<?php

namespace App\Http\Controllers\ServiceProvider;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\User;
use App\Models\Spcoverimages;
use Auth;
use App\Models\Country;
use Hash;
use Validator;
use Storage;
use AuthenticatesUsers;
use Carbon\Carbon;
use App\Models\Orders;
use App\Revenue;
use DB;
use App\Models\Service;
Use Alert;
use Illuminate\Validation\Rule;



class DashboardController extends Controller
{
  public function __construct(Orders $orders,Revenue $revenue,Service $service)
  {
    $this->middleware('auth');
    $this->orders   = $orders;
    $this->revenue  = $revenue;
    $this->service  = $service;
  } 

  public function index()
  {
    if(Auth::user()->setup_complete == '0'){
    
      toast('Please update your profile first!','question')->timerProgressBar();

      return redirect('serviceprovider/updateprofile');

    }elseif(Auth::user()->setup_complete == '2'){

      toast('Please create your first service!','question')->timerProgressBar();

      return redirect('serviceprovider/createservice');

    }elseif(Auth::user()->setup_complete == '3'){

      toast('Please update your timeslots first.','question')->timerProgressBar();

      return redirect('serviceprovider/technician/create');

    }elseif(Auth::user()->setup_complete == '4'){

      toast('Please add your first technician.','question')->timerProgressBar();

      return redirect('serviceprovider/create');

    }elseif(Auth::user()->setup_complete == '5'){

      toast('Please update the timeslots for technician.','question')->timerProgressBar();

      return redirect('serviceprovider/technician');
    }

    $startDate      = Carbon::now()->format('Y-m-d')." 00:00:00";
    $endDate        = Carbon::now()->format('Y-m-d'). " 23:59:59";

    $todayOrder     = $this->orders->where('payment_status','2')->where('service_provider_id',Auth::user()->id)->whereBetween('created_at',[ $startDate,$endDate ])->get()->count();
    $totalOrder     = $this->orders->where('payment_status','2')->where('service_provider_id',Auth::user()->id)->get()->count();

    $totalRevenue   = $this->revenue->whereHas('orders', function($query){
       $query->where('service_provider_id',Auth::user()->id)
          ->where('payment_status','2');
    })->sum('sp_amount');

    $todayRevenue   = $this->revenue->whereHas('orders', function($query) use ($startDate,$endDate){
      $query->where('service_provider_id',Auth::user()->id)
        ->where('payment_status','2')
        ->whereBetween('created_at',[ $startDate,$endDate ]);
    })->sum('sp_amount');

    $totalCustomer = $this->orders->where('payment_status','2')->where('service_provider_id',Auth::user()->id)->select('user_id', DB::raw('count(user_id) total_booking'))->groupBy('user_id')->get()->count();

    $todayCustomer = $this->orders->where('payment_status','2')->where('service_provider_id',Auth::user()->id)->select('user_id', DB::raw('count(user_id) total_booking'))->groupBy('user_id')->whereBetween('created_at',[ $startDate,$endDate ])->get()->count();

    $activeService = $this->service->where('is_active','1')->where('user_id',Auth::user()->id)->get()->count();

    return view('service-providers.home',compact('todayOrder','totalOrder','totalRevenue','todayRevenue','totalCustomer','todayCustomer','activeService'));

  }

  public function myprofile(Request $request)
  {
    if(Auth::user()->setup_complete != '1' &&  Auth::user()->setup_complete == '0'){
    
      toast('Please update your profile first!','question')->timerProgressBar();

    }

    $userid = Auth::user()->id;
    $user   = User::with('providertimeslots','coverimages')->find($userid);

    if ($request->isMethod('post')) {

      $validator = Validator::make($request->all(), [
        'full_name_en' => 'required|max:50',
        'email'=>'required|unique:users,email,'.$userid,
        'mobile_number'    => ['numeric',Rule::unique('users')
        ->ignore($user->id)->where(function ($query)use($request){
        return $query->where('user_type', 1);
      })],
        // 'mobile_number'=>'required|unique:users,mobile_number,'.$userid,
        //'profile_pic'=>'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
      ]);
     
      if ($validator->fails())
        return redirect()->back()->withErrors($validator)->withInput();

      $user->full_name_en    = $request->full_name_en;
      $user->full_name_ar    = $request->full_name_ar;
      $user->email           = $request->email;
      $user->mobile_number   = isset($request->mobile_number)?$request->mobile_number:'';
      $user->country_code    = '+965';
      $user->about           = $request->about;
      $user->about_ar        = $request->about_ar;
      $user->is_technician   = isset($request->is_technician)?$request->is_technician:0;
     
      if ($request->hasFile('profile_pic')) {
        $img = $user->profile_pic;
        // this returns the path of the file stored in the db
        if(Storage::disk('s3')->exists($img)) {
          Storage::disk('s3')->delete($img);
        }
        $file = $request->file('profile_pic');
        $extension = $request->file('profile_pic')->getClientOriginalExtension();
        $name = time() .'-serviceprovider-carcare.'.$extension;
        $filePath = 'serviceprovider/'.$name;
        Storage::disk('s3')->put($filePath, file_get_contents($file));
        $user->profile_pic = $filePath;
      }

      if($user->setup_complete == 0 ){
        $user->setup_complete = '2';
      }

      
      $user->save();

      if($request->hasFile('cover_profile_pic')){
        $coverImageUrl = [];

        foreach($request->file('cover_profile_pic') as $fileimg){
          $coverimages = new Spcoverimages;
          $extension = $fileimg->getClientOriginalExtension();
          $name = time().'-'.mt_rand().'-serviceprovider-carcare.'.$extension;
          $filePath = 'serviceprovider/'.$name;
          Storage::disk('s3')->put($filePath, file_get_contents($fileimg));
          //$coverImageUrl[]= $filePath;
          $coverimages->user_id = $userid;
          $coverimages->cover_image = $filePath;
          $coverimages->save();
        }

      }

      if($user->setup_complete == '2'){

        toast('Please create your first service!','question')->timerProgressBar();

        return redirect('serviceprovider/createservice');
      }

      return redirect()->back()->with('success', 'Profile has been updated succesfully!');
    }

    $cntry = Country::all();
    $name = ["KUWAIT", "KAZAKHSTAN","BAHRAIN","UNITED ARAB EMIRATES","OMAN","QATAR","JORDAN"];
    $kuwait = Country::whereIn('name',$name)->get();
    return view('admin.sp_profile.sp_updateprofile',compact('user','kuwait'));

  }

 
  public function changepassword(Request $request)
  {
    $user   = Auth::user();
     
    if ($request->isMethod('post')) {

      $validator= Validator::make($request->all(),[
        'current_password' => 'required|max:20',
        'new_password' => 'min:6|required_with:confirm_password|same:confirm_password',
      ]);

      if($validator->fails()){
        return redirect()->back()->with('error',$validator->errors()->first());
      }

      if (Hash::check($request->current_password, $user->password)) {
        $user->password  = Hash::make($request->new_password);
        $user->password_txt = $request->new_password;
      }else{
        return redirect()->back()->with('error', 'Current password does not match!');
      }

      $user->save();
      return redirect()->back()->with('success', 'Password has been changed successfully');
    }

    return view('admin.sp_profile.sp_changepassword');

  }


  public function coverimagedelete(Request $request)
  {
    if($request->isMethod('post')){
      $spcovred = Spcoverimages::where('id',$request->coverid)->delete();
      if($spcovred){
        return response()->json(array('status'=>1,'message' =>'Cover image has been deleted successfully.'));
      }else{
        return response()->json(array('status'=>1,'message' =>'Internal error.'));
      }
    }

  }

  public function revenueGraph(Request $request)
  {
    $revenue = $this->revenue->whereHas('orders', function($query){
      $query->where('service_provider_id',Auth::user()->id)
        ->where('payment_status','2');
      })->select(
      DB::raw('SUM(sp_amount) as `revenue`'), 
      DB::raw("DATE_FORMAT(created_at, '%m-%Y') new_date"),  
      DB::raw('YEAR(created_at) year, MONTHNAME(created_at) month'))
      ->groupby('year','month')
      ->orderBy('created_at','asc')
      ->get();

    return response()->json(['message' => 'Revenue data fetch successfully','data'=>$revenue]);
  }

  public function orderGraph(Request $request)
  {
    $revenue = $this->orders->where('service_provider_id',Auth::user()->id)
      ->where('payment_status','2')
      ->select(
      DB::raw('COUNT(id) as `order_count`'), 
      DB::raw("DATE_FORMAT(created_at, '%m-%Y') new_date"),  
      DB::raw('YEAR(created_at) year, MONTHNAME(created_at) month'))
      ->groupby('year','month')
      ->orderBy('created_at','asc')
      ->get();

    return response()->json(['message' => 'booking data fetch successfully','data'=>$revenue]);
  }



  public function dashbaordCardDetails()
  {
    $startDate      = Carbon::now()->format('Y-m-d')." 00:00:00";
    $endDate        = Carbon::now()->format('Y-m-d'). " 23:59:59";

    $todayOrder     = $this->orders->where('payment_status','2')->where('service_provider_id',Auth::user()->id)->whereBetween('created_at',[ $startDate,$endDate ])->get()->count();
    $totalOrder     = $this->orders->where('payment_status','2')->where('service_provider_id',Auth::user()->id)->get()->count();

    $totalRevenue   = $this->revenue->whereHas('orders', function($query){
       $query->where('service_provider_id',Auth::user()->id)
          ->where('payment_status','2');
    })->sum('sp_amount');

    $todayRevenue   = $this->revenue->whereHas('orders', function($query) use ($startDate,$endDate){
      $query->where('service_provider_id',Auth::user()->id)
        ->where('payment_status','2')
        ->whereBetween('created_at',[ $startDate,$endDate ]);
    })->sum('sp_amount');

    $totalCustomer = $this->orders->where('payment_status','2')->where('service_provider_id',Auth::user()->id)->select('user_id', DB::raw('count(user_id) total_booking'))->groupBy('user_id')->get()->count();

    $todayCustomer = $this->orders->where('payment_status','2')->where('service_provider_id',Auth::user()->id)->select('user_id', DB::raw('count(user_id) total_booking'))->groupBy('user_id')->whereBetween('created_at',[ $startDate,$endDate ])->get()->count();

    $activeService = $this->service->where('is_active','1')->where('user_id',Auth::user()->id)->get()->count();

    return response()->json([
      'todayOrder'     => $todayOrder,
      'totalOrder'     => $totalOrder,
      'totalRevenue'   => $totalRevenue,
      'todayRevenue'   => $todayRevenue,
      'totalCustomer'  => $totalCustomer,
      'todayCustomer'  => $todayCustomer,
      'activeService'  => $activeService
    ]);

  }



}
