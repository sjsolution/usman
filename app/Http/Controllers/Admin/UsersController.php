<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;
use Auth;
use Session;
use App\User;
use App\Models\Admin;
use Hash;
use Validator;
use Illuminate\Support\Facades\Storage;
use DB;
use Yajra\Datatables\Datatables;
use App\Models\RolePermission;
use App\Models\Wallet;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\UserReport;



class UsersController extends Controller
{

  public function __construct(User $user,Wallet $wallet)
  {
    $this->middleware('auth');
    $this->user = $user;
    $this->wallet = $wallet;
  }

  /**
   * user listing view
   */
  public function index(Request $request)
  { 
    return view('admin.users.usersView');
  }

  /**
   * user wallet history
   */
  public function userWallet(User $user)
  { 
    return view('admin.users.user_wallet_histories',compact('user'));
  }


  /**
   * User liting data
   */
  public function walletHistoriesList(Request $request,User $user)
  {
    $users = $this->wallet->where('user_id',$user->id)->orderBy('id','desc')->get();
        return Datatables::of($users)
        
        ->addColumn('status',function($users){
            if($users->transaction_type == 0)
              return '<button type="button" class="btn btn-gradient-primary btn-sm">Add Money</button>'; 
            else if($users->transaction_type == 1)
              return '<button type="button" class="btn btn-gradient-info btn-sm">Credit</button>'; 
            else if($users->transaction_type == 2)
              return '<button type="button" class="btn btn-gradient-danger btn-sm">Debit</button>'; 
            else if($users->transaction_type == 3)
              return '<button type="button" class="btn btn-gradient-primary btn-sm">Refunded</button>'; 
            else
                return '--'; 
         }) 
     
        ->make(true);
  }

  /**
   * User liting data
   */
  public function getuserlist(Request $request)
  {
    $users = $this->user->where('is_delete',0)->where('user_type',2)->orderBy('id','desc')->get();
         return Datatables::of($users)
          ->addColumn('full_name_en', function ($users) {
            if($users->full_name_en){
              return $users->full_name_en;
            }else{
              return $users->full_name_ar;
            }
        })
        
        ->addColumn('checkbox', function ($users) {
            return '<input type="checkbox" id="'.$users['id'].'" name="someCheckbox" class="userId" />';
        })
        ->addColumn('is_active',function($users){
            if($users->is_active == 1)
                return '<button type="button" class="btn btn-gradient-primary btn-sm" onclick="changeStatus(event,'.$users->id.',0)">Active</button>'; 
            else
                return '<button type="button" class="btn btn-gradient-danger btn-sm" onclick="changeStatus(event,'.$users->id.',1)">In-active</button>'; 
         }) 
        ->addColumn('action',function($users){
          return "<a href='user/usersDetails/".$users->id."' class='btn btn-gradient-info btn-sm' title='view'><i class='fa fa-eye'></i></a>
              <span class='btn btn-gradient-danger  btn-sm'  data-toggle='tooltip' data-placement='top' title='Delete' onclick='deleteUser(event,".$users->id.")'><i class='fa fa-trash-o'></i></span>

              <span class='btn btn-gradient-info  btn-sm' data-toggle='tooltip' data-placement='top' title='Add Cashback' onclick='addCashback(event,".$users->id.")'><i class='fa fa-money'></i></span>
              <a href='".url('admin/user/wallet/histories')."/".$users->id."'><span class='btn btn-gradient-info  btn-sm' data-toggle='tooltip' data-placement='top' title='Wallet Histories'><i class='fa fa-money'></i></span></a>";
              
        })
        ->make(true);
  }

  /**
   * User status change
   */
  public function userUpdate(Request $request)
  {
    $user  = Auth::user();

    if($user->type == 2){

      $rolePermission = RolePermission::where([
        'menu_id' => 2,
        'role_id' => $user->role[0]->role_id
      ])->first();

      if($rolePermission->is_write == 1){
        $user   = User::find($request->user_id);
        $user->is_active = $request->status;
        $user->save();
        return response()->json(['status'=>'success','message'=>'User status changed successfully']);
      }else{
        return response()->json(['status'=>'error','message'=>'you dont have a permission'],422);
      }

    }
    
   
    $user   = User::find($request->user_id);
    $user->is_active = $request->status;
    $user->save();
    return response()->json(['status'=>'success','message'=>'User status changed successfully']);
    
  } 
   
  /**
   * User proifle details
   */
  public function usersDetails(Request $request,$id)
  {
    if($id){
        $user = User::find($id);
        if($user){
          return view('admin.users.userDetail',compact('user'));
        }else{
          return redirect('admin/user');
        }
    }else{
      return redirect('admin/user');
    }
  }

  /**
   * User block-unblock based on multiple checkbox selected
   */
  public function selectedstatusupdate(Request $request)
  {
    $user   = Auth::user();

    if($user->type == 2){

      $rolePermission = RolePermission::where([
        'menu_id' => 2,
        'role_id' => $user->role[0]->role_id
      ])->first();

      if($rolePermission->is_write == 1){

        if($request->status==0){
          $user = User::whereIn('id',$request->id)->update(['is_active'=>0]);
        }else{
          $user = User::whereIn('id',$request->id)->update(['is_active'=>1]);
        }
  
        return response()->json(['status'=>1, 'message'=>'success']);
  
      }else{
  
        return response()->json(['status'=>0, 'message'=>'Permission denied']);
        
      }

    }

    if($request->status==0){
      $user = User::whereIn('id',$request->id)->update(['is_active'=>0]);
    }else{
      $user = User::whereIn('id',$request->id)->update(['is_active'=>1]);
    }

    return response()->json(['status'=>1, 'message'=>'success']);

  }

  public function myprofile(Request $request)
  {
    //dd('hi');
    $userid = Auth::user()->id;
    $user   = Admin::find($userid);
    if ($request->isMethod('post')) {
         $validator = Validator::make($request->all(), [
            'name' => 'required|max:15',
        ]);
        if($request->email !=''){
           $validator = Validator::make($request->all(), [
            'email'=>'required|unique:admins,email,'.$userid,
        ]);
         }

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
      
        $user->name=$request->name;
        $user->email=$request->email;
        $user->mobile_number=isset($request->mobile_number)?$request->mobile_number:'';
        
        if ($request->hasFile('profile_pic')) {
         
          $validator = Validator::make($request->all(), [
           'profile_pic'=>'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
         ]);
         if ($validator->fails()) {
             return redirect()->back()->withErrors($validator)->withInput();
         }
          $rand1 = mt_rand(000,999);
          $file = $request->file('profile_pic');
          $extension = $request->file('profile_pic')->getClientOriginalExtension();
          $name = time().$rand1.'-admin-user.'.$extension;
          $coverPath = 'profile/'.$name;
          Storage::disk('s3')->put($coverPath, file_get_contents($file));
          $user->profile_pic=$coverPath;
        }
        $user->save();
        return redirect()->back()->with('success', 'Profile has been changed succesfully!');
    }
    return view('admin.profile.updateprofile',compact('user'));
  }

  public function changepassword(Request $request)
  {
    //dd(bcrypt(123456));
    $userid = Auth::user()->id;
    $user   = Admin::find($userid);
    if ($request->isMethod('post')) {
      $validator= Validator::make($request->all(),[
        'current_password' => 'required|max:20',
        'new_password' => 'min:6|required_with:confirm_password|same:confirm_password',
      ]);
      if($validator->fails()){
        return Redirect::back()->with('error',$validator->errors()->first());
      }
      if (Hash::check($request->current_password, $user->password)) {
        $user->password  = Hash::make($request->new_password);
      }else{
        return redirect()->back()->with('error', 'Current password does not match!');
      }
      $user->save();
      return Redirect::back()->with('success', 'Password has been changed successfully');
    }
    return view('admin.profile.changepassword');
  }

  public function resetpassworddata(Request $request, $email)
  {
    $user = User::where('email',$request->email)->first();

    if($request->isMethod('post')){

      $validator= Validator::make($request->all(),[
        //'email'=>'required',
        'password'=>'required|min:6|max:20',
        //'password_confirmation' => 'min:6|required_with:confirm_password|same:confirm_password',
      ]);

      if($validator->fails()){
        return response()->json(['message' => $validator->errors()->first(),'status'=>0],config('constants.OK'));
      }
      // $user = User::find($user->id);
      $user->password = bcrypt($request->password);;
      $user->otp='';
      $user->password_reset_token='';
      $user->is_password_reset=1;
      $user->save();
      Session::flash('message', "Congratulation Your Password changed successfully!!");
      return Redirect::back();
      
    }else{
      return view('auth.changepassword', compact('user'));
    }

  }

  public function delete(User $user)
  {
      $user->forceDelete();
      return response()->json(['data' => 'User Successfully Deleteds']);
  } 

  public function addCashback(Request $request,User $user)
  {
      $user = $user->where('id',$request->user_id)->first();

      $user->update([
          'amount' => $user->amount +  $request->cashback_amount,
      ]);

      $user->wallet()->create([
        'description'        => 'Maak admin cashback',
        'transaction_amount' => $request->cashback_amount,
        'closing_amount'     => $user->amount,
        'transaction_type'   => '1',
        'description_ar'     => 'معاك المشرف كاش باك'
      ]);

      return response()->json(['message' => 'Cashback amount successfully added','status' => 'Success']);

  }

  public function exportUser(Request $request)
  {
      Excel::store(new UserReport($request->all()), "public/user_reports.xlsx");
      return '1';
  }

}
