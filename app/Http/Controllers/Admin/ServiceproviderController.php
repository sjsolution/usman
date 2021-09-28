<?php
namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\User;
use App\Models\City;
use App\Models\CityArea;
use Session;
use App\Models\Country;
use App\Models\Category;
use App\Models\Providercategory;
use App\Models\Serviceprovidercategory;
use App\Models\Serviceprovidersubcategory;
use Illuminate\Support\Facades\Mail;
use Redirect;
use Illuminate\Validation\Rule;
use App\Models\Spcountrycity;
use App\Models\Spcountrycityarea;
use Yajra\Datatables\Datatables;
use Auth;
use App\Notifications\ServiceProviderInvitation;


class ServiceproviderController extends Controller
{ 

  public function __construct(User $user)
  {
    $this->middleware('auth');
    $this->user = $user;
  }

  /**
   * Servicr provider creation
   */
  public function createServiceProvider(Request $request)
  {
    $user  = Auth::user();

    $roleUserPermission = \App\Models\RolePermission::query();

    if($user->type == '2'){
     
      $roleUserPermission =  $roleUserPermission->where([
        'menu_id' => 3,
        'role_id' => $user->role[0]->role_id
      ])->first();

      if($roleUserPermission->is_write == 0){

        toast('Permission denied','error')->timerProgressBar();

        return redirect('admin/serviceproviderlist');
      }
    
    }

    if($request->isMethod('post'))
    {


      $validator= Validator::make($request->all(),[
        'full_name_en'     =>'required|max:50',
        'full_name_ar'     =>'required|max:50',
        'email'            =>'required|max:50|email|unique:users,email',
        'secondary_email'  =>'required|max:50|email|unique:users,secondary_email',
        // 'mobile_number'    =>'unique:users|max:15|unique:users,mobile_number',
        'mobile_number'    => ['numeric',Rule::unique('users')->where(function ($query)                      use($user){
                                return $query->where('user_type', '1');
                              })],
        'phone_number'     => ['numeric',Rule::unique('users')->where(function ($query)                      use($user){
                              return $query->where('user_type', '1');
                            })],
        'incharge_name'    =>'required',
        'address'          =>'required',
        'supplier_code'    =>'required',
        //'monthly_fee'      =>'required|numeric'

      ]);
      if($validator->fails()){
        
        return back()->with('error',$validator->errors()->first())->withInput();
        
      }

      $password = $this->generateRandomString();

      $user = $this->user->create([
        'full_name_en'       => $request->full_name_en,
        'full_name_ar'       => $request->full_name_ar,
        'email'              => $request->email,
        'secondary_email'    => $request->secondary_email,
        'country_code'       => '+965',
        'mobile_number'      => $request->mobile_no,
        'password'           => Hash::make($password),
        'remember_token'     => rand(0000,9999),
        'phone_number'       => $request->phone_number,
        'person_incharge'    => $request->incharge_name,
        'address'            => $request->address,
        'bank_name'          => $request->bank_name,
        'iban'               => $request->iban,
        'supplier_code'      => $request->supplier_code,
        'monthly_fees'       => !empty($request->monthly_fee) ? $request->monthly_fee : 0,
        'setup_fee'          => !empty($request->setup_fee) ? $request->setup_fee : 0,
        'fixed_price'        => !empty($request->fixed_price) ? $request->fixed_price : 0,
        'maak_percentage'    => !empty($request->maak_percentage) ? $request->maak_percentage : 0,
        'is_verified_email'  => 1,
        'is_verified_phone'  => 1,
        'is_verified_mobile' => 1,
        'password_txt'       => $password,
        'is_active'          => 1,
        'is_service_active'  => 1
      ]);

      
      for ($i=0; $i < count($request->all()); $i++) {

        if($request->has('city_'.$i)){
        
          $spCity = Spcountrycity::create([
            'user_id'     => $user->id,
            'country_id'  => 114,
            'city_id'     => $request->input('city_'.$i)[0]
          ]);

          for($j=0; $j < count($request->input('area_'.$i)) ; $j++){
            $spCityArea = Spcountrycityarea::create([
              'country_city_id' => $spCity->id,
              'area_id'         => $request->input('area_'.$i)[$j]
            ]);
          }

        }
      }

     
      foreach ($request->categories as $key => $category) {
        $providercategory = new Providercategory;
        $providercategory->category_id =$category;
        $providercategory->user_id = $user->id;
        $providercategory->save();
      }

      if($request->subcategories !=null){
        foreach ($request->subcategories as $subcate) {
          $providersubcategory = new Serviceprovidersubcategory;
          $providersubcategory->sub_category_id =$subcate;
          $providersubcategory->user_id = $user->id;
          $providersubcategory->save();
        }
      }

      $user->notify(new ServiceProviderInvitation($user));

      return redirect('admin/serviceproviderlist');

    } 

    $cntry = Country::all();
    $kuwait = Country::get();
    $cities = City::where('country_id',114)->get();
    $category = Category::where('parent_id',0)->get();

    return view('admin.serviceprovider.createserviceprovider', compact('cntry','kuwait','cities','category'));
  }

  /**
   * this method is to get areas for corresponding cities in sp creation blade
   */
  public function generateRandomString($length = 6) 
  {
      $characters = '0123456789abcdefghijklmnopqrstuvwxyz';
      $charactersLength = strlen($characters);
      $randomString = '';
      for ($i = 0; $i < $length; $i++) {
          $randomString .= $characters[rand(0, $charactersLength - 1)];
      }
      return $randomString;
  }

  public function getcities($countryid)
  {
        $countries=City::where('country_id',$countryid)->get();
        $option='<option value=""> Select  city </option>';
        if(!empty($countries)){
          foreach($countries as $city){
            $option.='<option value="'.$city->id.'"> '.$city->name_en.'</option>';
          }
        }
        return response()->json(array('status'=>'success','option' => $option));
  }

  public function getareas($cityId)
  {

    $area=CityArea::where('city_id',$cityId)->get();
   
    $option='';
    if(!empty($area)){
          // $option.='<option value="All" selected="selected">All Ratings</option>';
        foreach($area as $areas){
          //$option.='<input type="checkbox" class="individual" />"'.$areas->id.'"<br>';
          $option.='<option id="'.$areas->id.'" value="'.$areas->id.'"> '.$areas->name_en.'</option>';
        }
    } 
    return response()->json(array('status'=>'success','option' => $option));
  }

  public function index()
  {
    return view('admin.serviceprovider.serviceproviderView');
  }

  public function getserviceproviderlist(Request $request)
  {
    $users = $this->user->where('is_delete',0)->where('user_type',1)->orderBy('id','desc')->get();
      
    return Datatables::of($users)
        ->addColumn('checkbox', function ($users) {
            return '<input type="checkbox" id="'.$users['id'].'" name="someCheckbox" class="userId" />';
        })
        ->addColumn('is_active',function($users){
            if($users->is_active == 1)
                return '<button type="button" class="btn btn-gradient-primary btn-sm" onclick="changeStatus(event,'.$users->id.',0)">Active</button>'; 
            else
                return '<button type="button" class="btn btn-gradient-danger btn-sm" onclick="changeStatus(event,'.$users->id.',1)">In-active</button>'; 
         })
        ->addColumn('password_txt',function($users){
         
          return '<strong id="user-'.$users->id.'" data-text ="'.$users->password_txt.'"  onclick="changePasswordVisibility('.$users->id.')">View</strong>'; 
          
        })
        ->addColumn('action',function($users){
          return  "<a href='serviceprovider/details/".$users->id."' class='btn btn-sm btn-gradient-primary' title='view'><i class='fa fa-eye'></i></a>
          <a href='serviceprovider/update/".$users->id."' class='btn btn-sm btn-gradient-info' title='Edit'><i class='fa fa-pencil'></i></a>
          <a href='serviceprovider/service/list/".$users->id."' class='btn btn-sm btn-gradient-info' title='Services'><i class='fa fa-briefcase'></i></a>
          <a href='serviceprovider/technician/list/".$users->id."' class='btn btn-sm btn-gradient-info' title='Technician'><i class='fa fa-user-o'></i></a>
          <a href='serviceprovider/change/password/".$users->id."' class='btn btn-sm btn-gradient-danger' title='Change Password'><i class='fa fa-key'></i></a>";
        })
        ->make(true);

  }

  public function serviceproviderDetails(Request $request,$id)
  {

    if($id){
      $serviceprovider = User::with('cityareas')->find($id);
      
      $category = Serviceprovidercategory::with(['categoryname'])->whereUserId($id)->get();
      $subcategory = Serviceprovidersubcategory::with(['categoryname'])->whereUserId($id)->get();
      
      $area = CityArea::get();
      $city = City::get();
      $country = Country::get();
      return view('admin.serviceprovider.serviceproviderDetails',compact('serviceprovider','country','city','area','category','subcategory'));
    }else{
      return Redirect::to('admin/serviceproviderlist');
    }
  }

  /**
   * Service provide updatation
   */
  public function serviceproviderUpdate(Request $request,$id)
  {
    
    $user  = Auth::user();

    $roleUserPermission = \App\Models\RolePermission::query();

    if($user->type == 2){

      $roleUserPermission =  $roleUserPermission->where([
        'menu_id' => 3,
        'role_id' => $user->role[0]->role_id
      ])->first();

      if($roleUserPermission->is_write == 0){

        toast('Permission denied','error')->timerProgressBar();

        return redirect('admin/serviceproviderlist');
      }
    
    }

     $serviceprovider   = User::with('category','subcategory','cityareas')->find($id);

    if($request->isMethod('post')){
      $validator= Validator::make($request->all(),[
        'full_name_en'     =>'required|max:50',
        'full_name_ar'     =>'required|max:50',
        'email'            =>'required|max:50|unique:users,email,'.$serviceprovider->id,
        'secondary_email'  =>'required|max:50|unique:users,secondary_email,'.$serviceprovider->id,
        // 'mobile_number'    =>'unique:users|min:8|max:15,'.$serviceprovider->id,
        'mobile_number'    => ['numeric','min:8','max:15',Rule::unique('users')
                                ->ignore($serviceprovider->id)->where(function ($query)use($request){
                                return $query->where('user_type', $serviceprovider->user_type);
                              })],
        'phone_number'    =>'required|numeric|unique:users,phone_number,'.$serviceprovider->id,
        'incharge_name'    =>'required',
        // 'country'          =>'required',
        // 'city'             =>'required',
        // 'area'             =>'required',
        'supplier_code'      =>'required',
        'address'          =>'required',
        //'monthly_fee'      =>'required|numeric',
        //'commission_fees'  =>'required|numeric',
      ]);

      if($validator->fails()){
          return back()->with('error',$validator->errors()->first());
      }

      $serviceprovider->full_name_en      =$request->full_name_en;
      $serviceprovider->full_name_ar      =$request->full_name_ar;
      $serviceprovider->mobile_number     =$request->mobile_no;
      $serviceprovider->email             =$request->email;
      $serviceprovider->secondary_email   =$request->secondary_email;
      $serviceprovider->country_code      =$request->countrycode;
      $serviceprovider->phone_number      =$request->phone_number;
      $serviceprovider->person_incharge   =$request->incharge_name;
      $serviceprovider->address           =$request->address;
      $serviceprovider->iban              =$request->iban;
      $serviceprovider->bank_name         =$request->bank_name;
      $serviceprovider->setup_fee         =$request->setup_fee;
      $serviceprovider->monthly_fees      =$request->monthly_fee;
      $serviceprovider->fixed_price       =$request->fixed_price;
      $serviceprovider->supplier_code       =$request->supplier_code;
      $serviceprovider->maak_percentage   =$request->maak_percentage;
      $serviceprovider->save();
      if($request->categories !=''){
        Providercategory::where(['user_id'=>$serviceprovider->id])->delete();
        foreach ($request->categories as $key => $category) {
          $providercategory = new Providercategory;
          $providercategory->category_id =$category;
          $providercategory->user_id = $serviceprovider->id;
          $providercategory->save();
        }
      }

      if($request->subcategories !=''){
        Serviceprovidersubcategory::where(['user_id'=>$serviceprovider->id])->delete();
        foreach ($request->subcategories as $subcate) {
          $providersubcategory = new Serviceprovidersubcategory;
          $providersubcategory->sub_category_id =$subcate;
          $providersubcategory->user_id = $serviceprovider->id;
          $providersubcategory->save();
        }
      }

      /*************Add code for country city area****/
      $existUSerCountry = Spcountrycity::where('user_id',$id)->get();

      if(!empty($existUSerCountry))
      {
        Spcountrycity::where('user_id',$id)->delete();
      }

      for ($i=0; $i < count($request->all()); $i++) {

        if($request->has('city_'.$i)){
        
          $spCity = Spcountrycity::create([
            'user_id'     => $id,
            'country_id'  => 114,
            'city_id'     => $request->input('city_'.$i)[0]
          ]);

          for($j=0; $j < count($request->input('area_'.$i)) ; $j++){
            $spCityArea = Spcountrycityarea::create([
              'country_city_id' => $spCity->id,
              'area_id'         => $request->input('area_'.$i)[$j]
            ]);
          }

        }
      }

      $message = 'Service provider details has been updated successfully';
      return redirect('admin/serviceproviderlist')->with('success',$message);
    
    }
    
    $cityIds    = [];
    $countryIds = [];

    foreach ($serviceprovider['cityareas'] as $countrycity) {
      $countryIds[] = $countrycity['country_id'];
      $areas[]      = CityArea::where('city_id',$countrycity['city_id'])->get();
    }

    $cities = City::whereIn('country_id',$countryIds)->get();
    $cntry = Country::all();
    $name = ["KUWAIT", "KAZAKHSTAN","BAHRAIN","UNITED ARAB EMIRATES","OMAN","QATAR","JORDAN"];
    //$kuwait = Country::whereIn('name',$name)->get();
    $kuwait = Country::get();
    $category = Category::where('parent_id',0)->get();
    $spCategories = $serviceprovider['category']->pluck('category_id')->toArray();
    $selectedSubcategory = $serviceprovider['subcategory']->map(function($subcategory){
      return $subcategory->sub_category_id;
    })->toArray() ?? [];

    $subcategory = Category::where('parent_id','!=',0)->get();
    return view('admin.serviceprovider.serviceproviderEdit',compact('serviceprovider','spCategories','cntry','kuwait','cities','areas','category','subcategory','selectedSubcategory'));
  
  }

  public function statusUpdate(Request $request)
  {
    $user  = Auth::user();

    $roleUserPermission = \App\Models\RolePermission::query();

    if($user->type == '2'){
     
      $roleUserPermission =  $roleUserPermission->where([
        'menu_id' => 3,
        'role_id' => $user->role[0]->role_id
      ])->first();

      if($roleUserPermission->is_write == 0){

        return response()->json(['msg' => 'Permission Denied','status' => 0],422);
      }
    
    }

    $serviceprovider=User::find($request->user_id);
    $serviceprovider->is_active =  $request->status;
    $serviceprovider->save();
    return response()->json(['msg' => 'status successfully changed','status' => true]);
  }

  public function selectedstatusupdate(Request $request)
  {
    $user  = Auth::user();

    if($user->type == '2'){

      $roleUserPermission = \App\Models\RolePermission::query();

      $roleUserPermission =  $roleUserPermission->where([
        'menu_id' => 3,
        'role_id' => $user->role[0]->role_id
      ])->first();

      if($roleUserPermission->is_write == 0){

        return response()->json(['msg' => 'Permission Denied','status' => 0]);
      }
    
    }

    if($request->status==0){
      $serviceprovider = User::whereIn('id',$request->id)->update(['is_active'=>0]);
    }
    elseif($request->status==1){
      $serviceprovider = User::whereIn('id',$request->id)->update(['is_active'=>1]);
    }
   
    return response()->json(['status'=>1, 'message'=>'success']);
  }

  public function getSubcategorydata(Request $request)
  {
    $subcategories = Category::whereIn('parent_id',$request->categoryID)->get();
    if(!empty($subcategories)){
      $option=[];
        foreach($subcategories as $subcategory){
          $option[]='<option value="'.$subcategory['id'].'"> '.$subcategory['name_en'].'</option>';
        }
    }
    return response()->json(array('status'=>1,'option' => $option));
  }

  /*****This function is used to delete country city and all city related areas***********/
  public function deletecountry(Request $request)
  {
    if($request->isMethod('post'))
    {
      $exist = Spcountrycity::where('user_id',$request->user_id)->count();
      if($exist >1){
        $spcountry = Spcountrycity::find($request->countryid)->delete();
        if($spcountry){
          return response()->json(array('status'=>1,'message' =>'Country and city with area has been deleted successfully.'));
        }else{
          return response()->json(array('status'=>1,'message' =>'Internal error.'));
        }
      }else{
          return response()->json(array('status'=>0,'message' =>'Atleast one country, city & area is required to fill.'));
      }
    }
  }

  /*****This function is used to check email ,mobile and password exist or not ***********/
  public function checkexists(Request $request)
  {
    if($request->isMethod('post')){
      $query = new User;

      if($request->type ==1){
        if(!empty($request->userid)){
          $validator1= Validator::make($request->all(),[
            'email'            =>'unique:users,email,'.$request->userid,
          ]);
          if($validator1->fails()){
            return response()->json(array('status'=>1,'message' =>'Email already exist, Please choose another email'));
          }
        }else{
          $validator2= Validator::make($request->all(),[
            'email'            =>'unique:users,email',
          ]);
          if($validator2->fails()){
            return response()->json(array('status'=>1,'message' =>'Email already exist, Please choose another email'));
          }
        }
        return response()->json(array('status'=>0,'message' =>'success.'));
      }else if($request->type ==2){
        
        if(!empty($request->userid)){
          $validator3= Validator::make($request->all(),[
            // 'mobile_number'            =>'unique:users,mobile_number,'.$request->userid,
            'mobile_number'    => ['numeric',Rule::unique('users')->ignore($request->userid)
                                    ->where(function ($query)use($request){
                                return $query->where('user_type', $request->type);
                              })],
          ]);
          if($validator3->fails()){
            return response()->json(array('status'=>1,'message' =>'Mobile number already exist, Please choose another mobile.'));
          }
        }else{
          $validator4= Validator::make($request->all(),[
            // 'mobile_number'            =>'unique:users,mobile_number',
            'mobile_number'    => ['numeric',Rule::unique('users')->where(function ($query)                        use($request){
                                return $query->where('user_type','1');
                              })],
          ]);
          if($validator4->fails()){
            return response()->json(array('status'=>1,'message' =>'Mobile number already exist, Please choose another mobile.'));
          }
        }
        return response()->json(array('status'=>0,'message' =>'success.'));
      }else if($request->type ==3){

        if(!empty($request->userid)){
          $validator5= Validator::make($request->all(),[
            'phone_number'            =>'unique:users,phone_number,'.$request->userid,
          ]);
          if($validator5->fails()){
            return response()->json(array('status'=>1,'message' =>'Phone number already exist, Please choose another phone.'));
          }
        }else{
          $validator6= Validator::make($request->all(),[
            'phone_number'            =>'unique:users,phone_number',
          ]);
          if($validator6->fails()){
            return response()->json(array('status'=>1,'message' =>'Phone number already exist, Please choose another phone.'));
          }
        }
        return response()->json(array('status'=>0,'message' =>'success.'));
      }
    }
  }

  public function serviceList(User $user)
  {
    return view('admin.serviceprovider.service_list',compact('user'));
  }

  public function servicelisting(Request $request,User $user)
  {
    $orderby = "id DESC";
    $limit = "10";
    $offset = "0";
    $service   = new \App\Models\Service();
    $service = $service->newQuery();
    $orderby = isset($request->jtSorting)?$request->jtSorting:$orderby;
    $limit = isset($request->jtPageSize)?$request->jtPageSize:$limit;
    $offset= isset($request->jtStartIndex)?$request->jtStartIndex:$offset;
    $service->with(['category','subcategory']);
    $service->where(['user_id'=>$user->id]);
    if ($request->isMethod('post')) {
      if ($request->has('keyword')) {
        if($request->input('keyword') !=''){
          $service->where('name_en','like', '%'.$request->input('keyword').'%');
          $service->orWhere('name_ar','like', '%'.$request->input('keyword').'%');
        }
      }
      $count = $service->count();
    }else{
      $count = $service->count();
    }
    $service->orderByRaw($orderby);
    $service->offset($offset);
    $service->limit($limit);
    $services = $service->get();
    return  response()->json([
    'Result'           => 'OK',
    'TotalRecordCount' => $count,
    'Records'          => $services,
    ],200);
  }

  public function technicianList(User $user)
  {
    return view('admin.serviceprovider.technician_list',compact('user'));
  }

  public function technicianlisting(Request $request,User $user)
  {
    
    $orderby = "id DESC";
    $limit = "10";
    $offset = "0";
    $users   = new User();
    $users = $users->newQuery();
    $orderby = isset($request->jtSorting)?$request->jtSorting:$orderby;
    $limit = isset($request->jtPageSize)?$request->jtPageSize:$limit;
    $offset= isset($request->jtStartIndex)?$request->jtStartIndex:$offset;
    if ($request->isMethod('post')) {
      if($request->has('status')){
        if($request->input('status') !=''){
          $users->where('is_active',$request->input('status'));
        }
      }
        if ($request->has('keyword')) {
          if($request->input('keyword') !=''){
            $users->where('user_type','4')->where('full_name_en','like', '%'.$request->input('keyword').'%')->where('created_by',Auth::user()->id);
            $users->orWhere('email','like', '%'.$request->input('keyword').'%')->where('user_type','4')->where('created_by',Auth::user()->id);
            $users->orWhere('mobile_number','like', '%'.$request->input('keyword').'%')->where('user_type','4')->where('created_by',Auth::user()->id);
          }
      }
      $count = $users->count();
    }else{
      $count = $users->count();
    }

    $users->where(['user_type'=>'4','is_delete'=>'0', 'created_by'=>$user->id]);
    $users->orderByRaw($orderby);
    $users->offset($offset);
    $users->limit($limit);
    $user = $users->get();
    return  response()->json([
        'Result'           => 'OK',
        'TotalRecordCount' => $count,
        'Records'          => $user,
      ],200);
  }

  public function changePassword(User $user)
  {
     return view('admin.serviceprovider.change_password',compact('user'));
  }

  public function changePasswordUpdate(Request $request)
  {
    $validator= Validator::make($request->all(),[
      'password' => 'required|confirmed|min:6',
    ]);
  
    if($validator->fails()){
      return back()->with('error',$validator->errors()->first());
    }

    $user = $this->user->where('id',$request->user_id)->first();
    $user->password = Hash::make($request->password);
    $user->password_txt = $request->password;
    $user->save();

 
    return back()->with('success','Password successfully changed');
    
  }

}
