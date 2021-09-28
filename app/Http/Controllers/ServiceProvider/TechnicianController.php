<?php

namespace App\Http\Controllers\ServiceProvider;
use Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Country;
use App\User;
use Session;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use App\Mail\TechnicianInvitation;
use Yajra\Datatables\Datatables;

use Illuminate\Validation\Rule;

class TechnicianController extends Controller
{
  public function __construct(User $user)
  {
    $this->user = $user;
  }

  public function index(){

    if(Auth::user()->setup_complete != '1' &&  Auth::user()->setup_complete == '5'){
    
      toast('Please update the timeslots for technician','question')->timerProgressBar();

    }
    
    return view('technician.Viewtechnicians');

  }
      
  public function  createTechnician(Request $request)
  { 
    
    if($request->isMethod('post'))
    {
        $validator= Validator::make($request->all(),[
          'full_name_en'     =>'required|max:20',
          'full_name_ar'     =>'required|max:20',
          'email'            =>'required|max:40|email|unique:users',
          'mobile_number'    =>['numeric',Rule::unique('users')
                    ->where(function ($query)use($request){
            return $query->where('user_type', 4);
          })],
          // 'countrycode'      =>'required|max:5',
        ]);

        if($validator->fails()){
         
          return back()->withInput($request->input())->with('error',$validator->errors());
        }

       
       
        $password = rand(1111,9999);
        $token = rand(0000,9999);
        $id = Auth::user()->id;
        $user                = new User;
        $user->full_name_en  = $request->full_name_en;
        $user->full_name_ar  = $request->full_name_ar;
        $user->email         = $request->email;
        $user->mobile_number = $request->mobile_number;
        $user->country_code  = '+965';
        $user->password      = Hash::make($password);
        $user->is_verified_email= '1';
        $user->is_verified_mobile='1';
        $user->is_active     = '1';
        $user->is_technician = '1';
        $user->user_type     = '4';
        $user->password_txt  = $password;
        $user->remember_token= $token;
        $user->created_by    = $id;
        $user->is_service_active = 1;
        $user->save();
        
        \App\SPTechnician::create([
          'user_id'       => $id,
          'technician_id' => $user->id
        ]);
        
        Mail::to($user->email)->send(new TechnicianInvitation($user,$password));

        if(Auth::user()->setup_complete != '1' ){

          $spUser = Auth::user();
          $spUser->setup_complete = '4';
          $spUser->save();
        
          toast('Please update your timeslots first.','question')->timerProgressBar();
  
          return redirect('serviceprovider/create');
          
        }
      
      return redirect('serviceprovider/technician')->with('message', 'Technician has been created succesfully !');
    }

    if(Auth::user()->setup_complete != '1' &&  Auth::user()->setup_complete == '3'){
    
      toast('Please add your first technician.','question')->timerProgressBar();

    }

    $cntry = Country::all();
    $name = ["KUWAIT", "KAZAKHSTAN","BAHRAIN","UNITED ARAB EMIRATES","OMAN","QATAR","JORDAN"];
    $kuwait = Country::whereIn('name',$name)->get();
    return view('technician.createTechnicians', compact(['cntry','kuwait']));
  }

  public function getTechnicianList(Request $request)
  {

    $user = $this->user->where(['user_type'=>'4','is_delete'=>'0', 'created_by'=>Auth::user()->id])->orderBy('id','desc')->get();
      
    return Datatables::of($user)
          ->addColumn('checkbox', function ($users) {
              return '<input type="checkbox" id="'.$users['id'].'" name="someCheckbox" class="userId" />';
          })
         ->addColumn('mobile_number',function($user){
           if(!empty($user->mobile_number)) 
            return $user->country_code.'-'.$user->mobile_number;
           else 
            return '--';
         })
         ->addColumn('password_txt',function($users){
         
          return '<strong id="user-'.$users->id.'" data-text ="'.$users->password_txt.'"  onclick="changePasswordVisibility('.$users->id.')">View</strong>'; 
          
         })
         ->addColumn('status',function($user){
          if($user->is_active == 1)
              return '<button type="button" class="btn btn-gradient-primary btn-sm" onclick="changeStatus(event,'.$user->id.',0)">Active</button>'; 
          else
              return '<button type="button" class="btn btn-gradient-danger btn-sm" onclick="changeStatus(event,'.$user->id.',1)">In-active</button>'; 
        })
        ->addColumn('action',function($user){
          return 
                "<a href='".route('sp.technicianDetails')."/".$user->id."' class='btn btn-sm btn-gradient-primary' title='view'><i class='fa fa-eye'></i></a>
                <a href='".route('sp.technicianUpadte')."/".$user->id."' class='btn btn-sm btn-gradient-info' title='Edit'><i class='fa fa-pencil'></i></a>
                <a href='".route('tech.techtimeslot')."/".$user->id."' class='btn btn-sm btn-gradient-info' title='Time Slot'><i class='fa fa-clock-o' aria-hidden='true'></i></a>";
        })
        ->make(true);

  }

  public function technicianDetails(Request $request,$id){
        if($id){
            $user = User::with('technicaintimeslots')->where('user_type',4)->where('id',$id)->first();
            if($user){
              return view('technician.technicianDetails',compact('user'));
            }else{
              return redirect('user/home');
            }
        }else{
          return redirect('user/home');
        }
  }


  public function updateTechnician(Request $request,$id)
  {
    $userdata = User::where('id',$id)->first();
    $cntry = Country::all();
    $name = ["KUWAIT", "KAZAKHSTAN","BAHRAIN","UNITED ARAB EMIRATES","OMAN","QATAR","JORDAN"];
    $kuwait = Country::whereIn('name',$name)->get();
    return view('technician.technicianEdit',compact('userdata','cntry','kuwait'));
  }

  public function update(Request $request ,$id)
  {
 
    $validator= Validator::make($request->all(),[
      'full_name_en'     =>'required|max:20',
      'full_name_ar'     =>'required|max:20',
      'email'            =>'required|max:40|email|unique:users,email,'.$id,
      'mobile_no'        =>'unique:users,mobile_number,'.$id
    ]);

   
    if($validator->fails()){
         
      return back()->withInput($request->input())->with('error',$validator->errors());
    }

    $technician   = User::find($id);
    $technician->full_name_en = $request->full_name_en;
    $technician->full_name_ar = $request->full_name_ar;
    $technician->mobile_number = $request->mobile_no;
    $technician->email =$request->email;
    $technician->country_code =  '+965';
    $technician->save();
    $message = 'Technician details has been updated successfully';
    Session::flash('$message');
    Session::flash('alert-class', 'alert-danger');
    return view('technician.Viewtechnicians');

  }

  public function statusUpdate(Request $request)
  {
    $technician=User::find($request->technician_id);
    $technician->is_active = $request->status;
    $technician->save();
    return response()->json(['msg' => 'status successfully chnaged','status' => true]);
  }

  public function technicianDelete(Request $request){
    $id = $request->id;
    $technician = User::where('id',$id)->where('user_type','4')->first()->delete();
    return response()->json(['status'=>1, 'message'=>'success']);
  }

  public function selectedstatusupdate(Request $request){
     
      if($request->status=='0'){
        $technician = User::whereIn('id',$request->id)->where('user_type','4')->update(['is_active'=>'0']);
      }elseif($request->status=='1'){
        $selectedtech = User::whereIn('id',$request->id)->where('user_type','4')->update(['is_active'=>'1']);
      }elseif($request->status=='2'){
        $selectedDel=User::whereIn('id',$request->id)->where('user_type','4')->delete();
      }

    return response()->json(['status'=>1, 'message'=>'success']);
  }




     public function techtimeslot(Request $request)
     {
       $open_time = strtotime("1:00");
       $close_time = strtotime("23:59");
       $now = time();
       $output = [];
       for( $i=$open_time; $i<$close_time; $i+=300) {
           //if( $i < $now) continue;
           //$output .= "<option>".date("H:i",$i)."</option>";
           $output[] =date("H:i",$i);
       }
       $adminTimeSlots = TimeSlot::get();
       $slotDuration=['10'=>'10 minutes','15'=>'15 minutes','20'=>'20 minutes','30'=>'30 minutes','60'=>'1 hour','120'=>'2 hour','180'=>'3 hour','240'=>'4 hour','300'=>'5 hour','360'=>'6 hour','420'=>'7 hours','480'=>'8 hours','540'=>'9 hours','600'=>'10 hours','660'=>'11 hours','720'=>'12 hours'];
       $bufferDuration=['10'=>'10 minutes','15'=>'15 minutes','20'=>'20 minutes','30'=>'30 minutes','60'=>'1 hour'];

      //  $slotDuration=['10'=>'10 minutes','15'=>'15 minutes','20'=>'20 minutes','30'=>'30 minutes','60'=>'1 hour','120'=>'2 hour','180'=>'3 hour','240'=>'4 hour','300'=>'5 hour','360'=>'6 hour','420'=>'7 hours','480'=>'8 hours','540'=>'9 hours','600'=>'10 hours','660'=>'11 hours','720'=>'12 hours'];
        //$bufferDuration=['10'=>'10 minutes','15'=>'15 minutes','20'=>'20 minutes','30'=>'30 minutes','60'=>'1 hour'];
        return view('technician.tech-time-slot',compact('slotDuration','bufferDuration','adminTimeSlots','output'));
     }

}
