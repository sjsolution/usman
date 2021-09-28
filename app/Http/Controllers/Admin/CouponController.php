<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\Models\Category;
use App\Models\Serviceprovidercategory;
use App\Models\Service;
use App\Coupon;
use Carbon\Carbon;
use DB;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\Storage;
use Auth;

class CouponController extends Controller
{ 
    public function __construct(
        Serviceprovidercategory $serviceprovidercategory,
        Service                 $service,
        Coupon                  $coupon
    )
    {
       $this->serviceProvider = $serviceprovidercategory;
       $this->service         = $service;
       $this->coupon          = $coupon;
    }

    public function create()
    {
        $user  = Auth::user();

        if($user->type == '2'){
    
            $roleUserPermission = \App\Models\RolePermission::query();
    
            $roleUserPermission =  $roleUserPermission->where([
            'menu_id' => 7,
            'role_id' => $user->role[0]->role_id
            ])->first();
    
            if($roleUserPermission->is_write == 0){
    
               toast('Permission Denied','error');
               return redirect('admin/coupon/list');
            }
        
        }

        $users      = User::where('user_type','2')->where('is_active',1)->where('is_guest',0)->get();
        $categories = Category::where('parent_id',0)->get();
        return view('admin.coupon.create',compact('users','categories'));
    }

    public function getServiceProviders($categoryId)
    {
        $ids = explode(',',$categoryId);
        
        $serviceProviders  = $this->serviceProvider->whereIn('category_id',$ids)->with('user')->get();
  
        $option ='';
        $sp_ids = [];
        // $option = '<option value="">All</option>';
        
        foreach($serviceProviders as $key =>$val){
            if( !in_array( $val->user->id ,$sp_ids ) ){
                $sp_ids[] = $val->user->id;
                $option.='<option value="'.$val->user->id.'"> '.$val->user->full_name_en.'</option>';

            }


        }

        return response()->json(['status'=>'success','option' => $option]);
    }

    public function getServices($serviceProviderId)
    {
        $ids = explode(',',$serviceProviderId);
        
        $serviceProviders  = $this->service->whereIn('user_id',$ids)->get();
        $option = '';
        // $option = '<option value="">All</option>';
        
        foreach($serviceProviders as $key =>$val){
           $option.='<option value="'.$val->id.'"> '.$val->name_en.'</option>';
        }

        return response()->json(['status'=>'success','option' => $option]);
    }

    public function store(Request $request)
    { 
         
        try{

            DB::beginTransaction();

            $filePath = '';

            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $extension = $request->file('image')->getClientOriginalExtension();
                $name = time() .'-coupon-carcare.'.$extension;
                $filePath = 'coupon/'.$name;
                Storage::disk('s3')->put($filePath, file_get_contents($file));
                
            }

            $coupon =  $this->coupon->create([
                'name_en'               => $request->name_en,
                'name_ar'               => $request->name_ar,
                'code'                  => $request->coupon_code,
                'image'                 => $filePath,
                'type'                  => (string)$request->coupon_type,
                'coupon_value'          => $request->coupon_value,
                'coupon_min_value'      => $request->min_cost_limit,
                'coupon_max_value'      => $request->max_cost_limit,
                'user_limit'            => $request->user_limit,
                'coupon_per_user_limit' => $request->coupon_use_limit,
                'description_en'        => $request->description_en,
                'description_ar'        => $request->description_ar,
                'valid_till'            => !empty($request->coupon_valid_till) ? Carbon::parse($request->coupon_valid_till)->format('Y-m-d') : null
            ]);
    
            //Assign coupon to user
            if(!empty($request->customer_name)){
                for($i=0 ; $i < count($request->customer_name) ; $i++){
                    $coupon->assignedUser()->create([
                        'user_id' => $request->customer_name[$i]
                    ]);
                }
                
            }
    
            //Assign coupon to category
            if(!empty($request->ser_cat_ids)){
                for($i=0 ; $i < count($request->ser_cat_ids) ; $i++){
                    $coupon->assignedCategory()->create([
                        'category_id' => $request->ser_cat_ids[$i]
                    ]);
                }
                
            }
    
            //Assign coupon to Service provider
            if(!empty($request->service_provider_ids)){
                for($i=0 ; $i < count($request->service_provider_ids) ; $i++){
                    $coupon->assignedServiceProvider()->create([
                        'user_id' => $request->service_provider_ids[$i]
                    ]);
                }
                
            }
    
            //Assign coupon to service
            if(!empty($request->services)){
                for($i=0 ; $i < count($request->services) ; $i++){
                    $coupon->assignedServices()->create([
                        'service_id' => $request->services[$i]
                    ]);
                }
                
            } 
    
            DB::commit();

            return redirect('admin/coupon/list')->with('message', 'Coupon has been succesfully created!');


        }catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 0 ,'message' => 'something went wrong'],200);
        }
  

    }

    public function index()
    {
        return view('admin.coupon.list');
    }

    public function couponList(Request $request)
    {
        $coupons = $this->coupon->orderBy('id','desc')->get();
      
        return Datatables::of($coupons)
            ->addColumn('type',function($coupons){
                if($coupons->type == '1')
                   return 'Percentage';
                else if($coupons->type == '2')
                   return 'Amount';
            })
            ->addColumn('status',function($coupons){
                if($coupons->status == 1)
                    return '<button type="button" class="btn btn-gradient-primary btn-sm" onclick="changeStatus(event,'.$coupons->id.',0)">Active</button>'; 
                else
                    return '<button type="button" class="btn btn-gradient-danger btn-sm" onclick="changeStatus(event,'.$coupons->id.',1)">In-active</button>'; 
             })
            ->addColumn('is_display',function($coupons){
            if($coupons->is_display == 1)
                return '<button type="button" class="btn btn-gradient-primary btn-sm" onclick="changeDisplayStatus(event,'.$coupons->id.',0)">Show</button>'; 
            else
                return '<button type="button" class="btn btn-gradient-danger btn-sm" onclick="changeDisplayStatus(event,'.$coupons->id.',1)">Hide</button>'; 
            })
            ->addColumn('action',function($coupons){
               return '
                  <i class="fa fa-eye ikn"  data-toggle="tooltip" data-placement="top" title="View" onclick="couponDetails(event,'.$coupons->id.')"></i>'; 
            })
            ->make(true);   
    } 

    public function couponCheck(Request $request)
    {
        $flag     = false;
        $coupon   = $this->coupon->where('code', $request->code)->first();
       
        if ($coupon) {
            $flag = 'true';
        }

        echo $flag;
    }

    public function statusChange(Request $request)
    {
        $user  = Auth::user();

        if($user->type == '2'){
    
            $roleUserPermission = \App\Models\RolePermission::query();
    
            $roleUserPermission =  $roleUserPermission->where([
            'menu_id' => 7,
            'role_id' => $user->role[0]->role_id
            ])->first();
    
            if($roleUserPermission->is_write == 0){
    
                return response()->json(['message' => 'Permission Denied','status' => 0],422);

            }
        
        }

        $coupon = $this->coupon->where('id',$request->couponId)->first();
        $coupon->status = $request->status;
        $coupon->save();
        return response()->json(['msg' => 'Status changes successfully']);
    }

    public function displayStatusChange(Request $request)
    {
        $user  = Auth::user();

        if($user->type == '2'){
    
            $roleUserPermission = \App\Models\RolePermission::query();
    
            $roleUserPermission =  $roleUserPermission->where([
            'menu_id' => 7,
            'role_id' => $user->role[0]->role_id
            ])->first();
    
            if($roleUserPermission->is_write == 0){
    
                return response()->json(['message' => 'Permission Denied','status' => 0],422);

            }
        
        }

        $coupon = $this->coupon->where('id',$request->couponId)->first();
        $coupon->is_display = $request->status;
        $coupon->save();
        return response()->json(['msg' => 'Status changes successfully']);
    }

    public function couponDetails(Request $request)
    {
       $coupon['list'] = $this->coupon->with('assignedUser','assignedCategory','assignedServiceProvider','assignedServices')->where('id',$request->coupon_id)->get();
       return $coupon;
    }
}
