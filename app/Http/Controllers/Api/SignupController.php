<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use App\User;
use App\Models\Device;
use App\Models\Useraddress;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Mail;
use App\Mail\Congratulation;
use GuzzleHttp\Client;
use App\Mail\Forgotpassword;
use App\Traits\CommonTrait;
use App\Models\Vehicles;
use App\Models\Insurancecardetails;
use Api;
use App\Events\WalletGift;


class SignupController extends Controller
{
  use CommonTrait;
 
  public function register(Request $request)
  {
    $full_name = "full_name_".app()->getLocale();

    $validator= Validator::make($request->all(),[
      'full_name'        => 'required',
      'email'            => 'required|max:40|email|unique:users,email',
      'password'         => 'required|min:5|max:20',
      'country_code'     => 'required',
      'user_type'        => 'required',
      // 'mobile_number'    => 'required|numeric|unique:users,mobile_number',
      'mobile_number'    => ['required','numeric',Rule::unique('users')->where(function ($query)                        use($request){
                                return $query->where('user_type', $request->user_type);
                              })
                            ],
      'seleted_language' => 'required|in:en,ar'
    ]);

    if($validator->fails()){
      return response()->json(['status'=>0,'message' => $validator->errors()->first()]);
    }

    $user                    = new User();
    $user->full_name_en      = $request->full_name;
    $user->full_name_ar      = $request->full_name;
    $user->email             = $request->email;
    $user->password          = bcrypt($request->password);
    $user->password_txt      = $request->password_txt;
    $user->country_code      = $request->country_code;
    $user->mobile_number     = $request->mobile_number;
    $user->user_type         = $request->user_type;
    $randString              = $this->generateRandomString();
    $user->otp               = $randString;
    $user->is_active         = 1;
    $user->is_verified_email = 0;
    $user->amount            = "0";
    $user->is_language       = $request->seleted_language;
    $user->save();
    
    /*****************Guest updation for new vehocle and another entry **************/
    if(!empty($request->guest_id)){

      $guestuserdetails = $this->userexist($request->guest_id);

      if(!empty($guestuserdetails['guest_id'])){

        $vehicles = Vehicles::where(['user_id'=>$guestuserdetails['user_id'],'guest_id'=>$guestuserdetails['guest_id']])->get();
        
        if(!empty($vehicles)){

          $myvehiclesIDs=[];

          foreach ($vehicles as $key => $value) {
            $adduserforvehicles = Vehicles::find($value['id']);
            $adduserforvehicles->user_id = $user->id;
            $adduserforvehicles->guest_id = '';
            $adduserforvehicles->is_primary = '0';
            $adduserforvehicles->save();
            $myvehiclesIDs[] =$value['id'];
          }

          $update = ['is_primary'=>'1'];
          $lastmyvehicleId = end($myvehiclesIDs);
          Vehicles::where(['id'=>$lastmyvehicleId])->update($update);
        }

        $insurance = Insurancecardetails::where(['user_id'=>$guestuserdetails['user_id'],'guest_id'=>$guestuserdetails['guest_id']])->get();
        
        if(!empty($insurance)){
          foreach ($insurance as $insurancedata) {
            $insuranceadd = Insurancecardetails::find($insurancedata['id']);
            $insuranceadd->user_id = $user->id;
            $insuranceadd->guest_id = '';
            $insuranceadd->save();
          }
        }

      }
      
    }
    /**************************End data***************************/

    if(app()->getLocale()=="ar"){
      $messageContent = "Use ".$randString." to verify your mobile number for MAAK account";
    }else{
      $messageContent = "Use ".$randString." to verify your mobile number for MAAK account";
    }

    $mobileNumber = $request->country_code.$request->mobile_number;
    $messageSuccess = $this->sendSmsonMobile($messageContent,$mobileNumber);
    $device = new Device();
    $device->device_type = isset($request->device_type)?$request->device_type:'';
    $device->device_token = isset($request->device_token)?$request->device_token:'';
    $device->user_id=$user->id;
    $device->save();
    
    $response =  $user->createToken('MyApp')->accessToken; //generate Token
    $user->full_name = $user->$full_name;
    $user->wallet_amount = $user->amount;
    $user->is_address = 0;
    unset($user->amount);
    unset($user->$full_name);

    if($response){
      return response()->json(['status'=>1,'message'=>'success','user'=>$user,'auth'=>$response]);
    }else{
      return response()->json(['status'=>0,'message'=>'failed']);
    }
    
  }
  
  /**
   * OTP Verification 
   */
  public function verifyotp(Request $request)
  {
    $validator= Validator::make($request->all(),[
      'otp'     => 'required',
      'id'      => 'required|exists:users,id',
    ]);

    if($validator->fails()){
      return response()->json(['status'=>0,'message' => $validator->errors()->first()]);
    }

    $user   = User::find($request->id);

    if( $user->is_verified_mobile != 1){
     
      if($user->otp === $request->otp || config('app.MASTER_OTP') === $request->otp){
      
        $user->otp                = '';
        $user->is_verified_mobile = 1;
        $user->save();

        event(new WalletGift($user));
        
        Mail::to($user->email)->send(new Congratulation($user));

        return response()->json(['status'=>1,'message'=>__('message.otpVerified'),'user'=>$user]);

      }else{

        return response()->json(['status'=>0,'message' =>__('message.otpInvalid')]);

      }

    }else{

      if($user->otp === $request->otp || config('app.MASTER_OTP') === $request->otp){

        if($user->temp_mobile_number !=0){
          $user->mobile_number       = $user->temp_mobile_number;
          $user->country_code        = $user->temp_country_code;
          $user->temp_country_code   = 0;
          $user->temp_mobile_number  = 0;
          $user->otp                 = '';
          $user->save();
          return response()->json(['status'=>1,'message'=>__('message.otpVerified'),'user'=>$user]);
        }

      }else{

        return response()->json(['status'=>0,'message' =>__('message.otpInvalid')]);

      }

      return response()->json(['status'=>1,'message' =>__('message.alreadyVerified'),'user'=>$user]);

    }
     
  }

  public function generateRandomString($length = 4) {
      $characters = '0123456789';
      $charactersLength = strlen($characters);
      $randomString = '';
      for ($i = 0; $i < $length; $i++) {
          $randomString .= $characters[rand(0, $charactersLength - 1)];
      }
      return $randomString;
  }


  public function checkEmailExist(Request $request){
    if($request->isMethod('post')){
      $validator= Validator::make($request->all(),[
        'email'=>'required|max:40|email|unique:users,email',
      ]);
      if($validator->fails()){
        return response()->json(['status'=>0,'message' => $validator->errors()->first()]);
      }
      return response()->json(['status'=>1,'message'=>'success']);
    }
  }

  public function resendOtp(Request $request){
    if($request->isMethod('post')){
      $validator= Validator::make($request->all(),[
        'id'=>'required|exists:users,id',
      ]);
      if($validator->fails()){
        return response()->json(['status'=>0,'message' => $validator->errors()->first()]);
      }
      $user =  User::find($request->id);
      if($user->is_verified_mobile ==1){
        if($user->temp_mobile_number !=0 && $user->temp_mobile_number !=0){
            $returndata = 1;
        }else{
            return response()->json(['status'=>0,'message' =>'This Account Already Verified']);
        }

      }
      $randString = $this->generateRandomString();
      $user->otp = $randString;
      $user->save();
      if($user){
        if(app()->getLocale()=="ar"){
          $messageContent = "Use ".$randString." to verify your mobile number for MAAK account";
        }else{
          $messageContent = "Use ".$randString." to verify your mobile number for MAAK account";
        }

        if(!empty($request->mobile_number)){
          $mobileNumber = '+965'.$request->mobile_number;
          $user->mobile_number =  $request->mobile_number;
          $user->save();
        }else{
          $mobileNumber = $user->country_code.$user->mobile_number;
        }
      
       
       $messageSuccess = $this->sendSmsonMobile($messageContent,$mobileNumber);
       return response()->json(['status'=>1,'message'=>'success']);
      }else{
        return response()->json(['status'=>0,'message'=>'failed']);
      }

    }
  }

  public function userlogin(Request $request)
  {
    /*********1=email and password,2=mobile and password ,3=google login,4=facebook login**************/
    $full_name = "full_name_".app()->getLocale();

    if($request->isMethod('post')){
      if($request->type==1){
        $validator= Validator::make($request->all(),[
          'email'=>'required',
          'password'=> 'required',
          'user_type'=> 'required'
        ]);
      }else if($request->type==2){
        $validator= Validator::make($request->all(),[
          'mobile_number'=>'required',
          'password'=> 'required',
          'user_type'=> 'required'
        ]);
      }else if($request->type==3){
        $validator= Validator::make($request->all(),[
          'social_id'=>'required',
          'user_type'=> 'required'
        ]);
      }else if($request->type==4){
        $validator= Validator::make($request->all(),[
          'social_id'=>'required',
          'user_type'=> 'required'
        ]);
      }else if($request->type==5){
        $validator= Validator::make($request->all(),[
          'social_id'=>'required',
          'user_type'=> 'required'
        ]);
      }
      if($validator->fails()){
        return response()->json(['status'=>0,'message'=>$validator->errors()->first()]);
      }
      /********type 1 for email and password via login****/
      if($request->type ==1){

        if(Auth::attempt(['email'=>$request->email,'password'=>$request->password,'user_type'=>$request->user_type])){
          $users = Auth::user();
          $is_address = Useraddress::where('user_id',$users['id'])->count();
          if($users['is_active'] ==1){
            if($users['is_verified_mobile'] ==1){
              $user=['id'=>$users['id'],'email'=>$users['email'],'full_name'=>$users[$full_name],'country_code'=>$users['country_code'],'mobile_number'=>$users['mobile_number'],'is_verified_mobile'=>$users['is_verified_mobile'],'is_active'=>$users['is_active'],'profile_pic'=>isset($users['profile_pic'])?$users['profile_pic']:'','wallet_amount'=>$users['amount'],'user_type'=>$users['user_type'],'is_address'=>$is_address, 'isServiceActive'    => $users['is_service_active']];
              $status=1;
              $message=__('message.success');
            }else{
              $user = User::find($users['id']);
              $randString = $this->generateRandomString();
              $user->otp = $randString;
              $user->save();
              if(app()->getLocale()=="ar"){
                $messageContent = "Use ".$randString." to verify your mobile number for MAAK account";
                }else{
                $messageContent = "Use ".$randString." to verify your mobile number for MAAK account";
                }
                $mobileNumber = $users->country_code.$users->mobile_number;
                $status=2;
                $message=__('message.otpVerification');
                $user=[
                  'id'                 =>  $users['id'],
                  'email'              =>  $users['email'],
                  'full_name'          => $users[$full_name],
                  'otp'                => $randString,
                  'country_code'       => $users['country_code'],
                  'mobile_number'      => $users['mobile_number'],
                  'is_verified_mobile' => $users['is_verified_mobile'],
                  'is_active'          => $users['is_active'],
                  'profile_pic'        => isset($users['profile_pic'])?$users['profile_pic']:'',
                  'wallet_amount'      => $users['amount'],
                  'user_type'          => $users['user_type'],
                  'is_address'         => $is_address,
                  'notificationStatus' => $users['is_notification'],
                  'isServiceActive'    => $users['is_service_active']
                ];
                $messageSuccess = $this->sendSmsonMobile($messageContent,$mobileNumber);
            }
          }else {
            return response(['status'=>0,'message'=>__('message.accountDisabled')]);
          }

        }else{
            return response(['status'=>0,'message'=>__('message.wrongMobilePassword')]);
        }
      }

      /********type 2 for mobile numberand password via login****/
      if($request->type ==2){

        if(Auth::attempt(['mobile_number'=>$request->mobile_number,'password'=>$request->password,'user_type'=>$request->user_type])){
           
            $users = Auth::user();

            $is_address = Useraddress::where('user_id',$users['id'])->count();
            
            if($users['is_active'] ==1){
                  if($users['is_verified_mobile'] ==1){
                    $user=['id'=>$users['id'],'email'=>$users['email'],'full_name'=>$users[$full_name],'country_code'=>$users['country_code'],'mobile_number'=>$users['mobile_number'],'is_verified_mobile'=>$users['is_verified_mobile'],'is_active'=>$users['is_active'],'profile_pic'=>isset($users['profile_pic'])?$users['profile_pic']:'','wallet_amount'=>$users['amount'],'user_type'=>$users['user_type'],'is_address'=>$is_address];
                    $status=1;
                    $message="success";
                  }else{
                      $user = User::find($users['id']);
                      $randString = $this->generateRandomString();
                      $user->otp = $randString;
                      $user->save();
                      if(app()->getLocale()=="ar"){
                        $messageContent = "Use ".$randString." to verify your mobile number for MAAK account";
                        }else{
                        $messageContent = "Use ".$randString." to verify your mobile number for MAAK account";
                        }
                        $mobileNumber = $users->country_code.$users->mobile_number;
                        $messageSuccess = $this->sendSmsonMobile($messageContent,$mobileNumber);
                        $user=['id'=>$users['id'],'notificationStatus' => $users['is_notification'],'email'=>$users['email'],'full_name'=>$users[$full_name],'otp'=>$randString,'country_code'=>$users['country_code'],'mobile_number'=>$users['mobile_number'],'is_verified_mobile'=>$users['is_verified_mobile'],'is_active'=>$users['is_active'],'profile_pic'=>isset($users['profile_pic'])?$users['profile_pic']:'','wallet_amount'=>$users['amount'],'user_type'=>$users['user_type'],'is_address'=>$is_address];
                        $status=2;
                        $message= __('message.otpVerification');
                      }
                }else{
                  return response(['status'=>0,'message'=>__('message.accountDisabled')]);
                } 
              }else{

                    return response(['status'=>0,'message'=>__('message.wrongMobilePassword')]);
              }
      }

      /********type 3 for google id via login****/
      if($request->type ==3){
              $users = User::where(['google_id'=>$request->social_id,'user_type'=>$request->user_type])->first();
              if(!empty($users)){
                  $is_address = Useraddress::where('user_id',$users['id'])->count();
                  if($users['is_active']==1){
                    if($users['is_verified_mobile'] ==1){
                    $user=['id'=>$users['id'],'email'=>$users['email'],'full_name'=>$users[$full_name],'country_code'=>$users['country_code'],'mobile_number'=>$users['mobile_number'],'is_verified_mobile'=>$users['is_verified_mobile'],'is_active'=>$users['is_active'],'profile_pic'=>isset($users['profile_pic'])?$users['profile_pic']:'','wallet_amount'=>$users['amount'],'user_type'=>$users['user_type'],'is_address'=>$is_address];
                    $status=1;
                    $message="Success";
                  }else{
                      $user = User::find($users['id']);
                      $randString = $this->generateRandomString();
                      $user->otp = $randString;
                      $user->save();
                      if(app()->getLocale()=="ar"){
                        $messageContent = "Use ".$randString." to verify your mobile number for MAAK account";
                        }else{
                        $messageContent = "Use ".$randString." to verify your mobile number for MAAK account";
                        }
                        $mobileNumber = $users->country_code.$users->mobile_number;
                        $messageSuccess = $this->sendSmsonMobile($messageContent,$mobileNumber);
                        $user=['id'=>$users['id'],'notificationStatus' => $users['is_notification'],'email'=>$users['email'],'full_name'=>$users[$full_name],'otp'=>$randString,'country_code'=>$users['country_code'],'mobile_number'=>$users['mobile_number'],'is_verified_mobile'=>$users['is_verified_mobile'],'is_active'=>$users['is_active'],'profile_pic'=>isset($users['profile_pic'])?$users['profile_pic']:'','wallet_amount'=>$users['amount'],'user_type'=>$users['user_type'],'is_address'=>$is_address];
                        $status=2;
                        $message=__('message.otpVerification');
                      }
                  }else{
                      return response(['status'=>0,'message'=>'Your account has been disabled, Please contact to administrator.']);
                  }
            }else{
              $validator= Validator::make($request->all(),[
                'email'=>'unique:users|max:50',
              ]);
              if($validator->fails()){
                return response()->json(['status'=>0,'message'=>$validator->errors()->first()]);
              }
              $validator= Validator::make($request->all(),[
                'mobile_number'=>'unique:users|max:50',
              ]);
              if($validator->fails()){
                return response()->json(['status'=>0,'message'=>$validator->errors()->first()]);
              }

            if($request->email == ''){
              if($request->mobile_number ==''){
                  return response()->json(['status'=>3,'is_mobile'=>0,'is_email'=>0,'message'=>'Email and mobile number field is required']);
              }else{
                return response()->json(['status'=>3,'is_mobile'=>1,'is_email'=>0,'message'=>'Email field is required']);
              }
            }
            if($request->mobile_number == ''){
              if($request->email ==''){
                  return response()->json(['status'=>3,'is_mobile'=>0,'is_email'=>0,'message'=>'Mobile number and email field is required']);
              }else{
                return response()->json(['status'=>3,'is_mobile'=>0,'is_email'=>1,'message'=>'Mobile number field is required']);
              }
            }
            if($request->email =='' && $request->mobile_number ==''){
              return response()->json(['status'=>3,'is_mobile'=>0,'is_email'=>0,'message'=>'Email and mobile number field is required']);
            }

            $users =new User;
            $users->$full_name = isset($request->full_name)?$request->full_name:'';
            $users->email = $request->email;
            $users->country_code = isset($request->country_code)?$request->country_code:'';
            $users->mobile_number = $request->mobile_number;
            $users->google_id = isset($request->social_id)?$request->social_id:'';
            $users->user_type = $request->user_type;
            $randString = $this->generateRandomString();
            $users->otp = $randString;
            $users->is_active = 1;
            $users->amount = "0.000";
            $users->save();
            $status=2;
            $is_address = Useraddress::where('user_id',$users['id'])->count();
            $user=['id'=>$users['id'],'notificationStatus' => $users['is_notification'],'email'=>$users['email'],'full_name'=>$users[$full_name],'otp'=>$randString,'country_code'=>$users['country_code'],'mobile_number'=>$users['mobile_number'],'is_verified_mobile'=>$users['is_verified_mobile'],'is_active'=>$users['is_active'],'profile_pic'=>isset($users['profile_pic'])?$users['profile_pic']:'','wallet_amount'=>$users['amount'],'user_type'=>$users['user_type'],'is_address'=>$is_address];
            $message=__('message.otpVerification');
            if(app()->getLocale()=="ar"){
              $messageContent = "Use ".$randString." to verify your mobile number for MAAK account";
              }else{
              $messageContent = "Use ".$randString." to verify your mobile number for MAAK account";
              }
              $mobileNumber = $users->country_code.$users->mobile_number;
              $messageSuccess = $this->sendSmsonMobile($messageContent,$mobileNumber);

        }
      }

      /********type 4 for facebook id via login****/
      if($request->type ==4){

          $users = User::where(['facebook_id'=>$request->social_id,'user_type'=>$request->user_type])->first();
            if(!empty($users)){
              $is_address = Useraddress::where('user_id',$users['id'])->count();
              if($users['is_active'] ==1){
                if($users['is_verified_mobile'] ==1){
                  $user=['id'=>$users['id'],'email'=>$users['email'],'full_name'=>$users[$full_name],'country_code'=>$users['country_code'],'mobile_number'=>$users['mobile_number'],'is_verified_mobile'=>$users['is_verified_mobile'],'is_active'=>$users['is_active'],'profile_pic'=>isset($users['profile_pic'])?$users['profile_pic']:'','wallet_amount'=>$users['amount'],'user_type'=>$users['user_type'],'is_address'=>$is_address];
                  $status=1;
                  $message=__('message.success');
                }else{
                    $user = User::find($users['id']);
                    $randString = $this->generateRandomString();
                    $user->otp = $randString;
                    $user->save();
                    if(app()->getLocale()=="ar"){
                      $messageContent = "Use ".$randString." to verify your mobile number for MAAK account";
                    }else{
                      $messageContent = "Use ".$randString." to verify your mobile number for MAAK account";
                    }
                    $mobileNumber = $users->country_code.$users->mobile_number;
                    $messageSuccess = $this->sendSmsonMobile($messageContent,$mobileNumber);
                    $user=['id'=>$users['id'],'email'=>$users['email'],'full_name'=>$users[$full_name],'otp'=>$randString,'country_code'=>$users['country_code'],'mobile_number'=>$users['mobile_number'],'is_verified_mobile'=>$users['is_verified_mobile'],'is_active'=>$users['is_active'],'profile_pic'=>isset($users['profile_pic'])?$users['profile_pic']:'','wallet_amount'=>$users['amount'],'user_type'=>$users['user_type'],'is_address'=>$is_address];
                    $status=2;
                    $message=__('message.otpVerification');
                }
              }else{
                return response(['status'=>0,'message'=>__('message.accountDisabled')]);
              }
            }else{

              $validatoremail= Validator::make($request->all(),[
                'email'=>'unique:users|max:50',
              ]);
              if($validatoremail->fails()){
                return response()->json(['status'=>0,'message'=>$validatoremail->errors()->first()]);
              }
              $validatormobile= Validator::make($request->all(),[
                'mobile_number'=>'unique:users|max:50',
              ]);
              if($validatormobile->fails()){
                return response()->json(['status'=>0,'message'=>$validatormobile->errors()->first()]);
              }

            if($request->email == ''){
              if($request->mobile_number ==''){
                  return response()->json(['status'=>3,'is_mobile'=>0,'is_email'=>0,'message'=>'Email and mobile number field is required']);
                }else{
                  return response()->json(['status'=>3,'is_mobile'=>1,'is_email'=>0,'message'=>'Email field is required']);
              }
            }
            if($request->mobile_number == ''){
              if($request->email ==''){
                  return response()->json(['status'=>3,'is_mobile'=>0,'is_email'=>0,'message'=>'Mobile number and email field is required']);
                }else{
                  return response()->json(['status'=>3,'is_mobile'=>0,'is_email'=>1,'message'=>'Mobile number field is required']);
                }
            }
            if($request->email =='' && $request->mobile_number ==''){
              return response()->json(['status'=>3,'is_mobile'=>0,'is_email'=>0,'message'=>'Email and mobile number field is required']);
            }

            $users =new User;
            $users->$full_name = isset($request->full_name)?$request->full_name:'';
            $users->email = $request->email;
            $users->country_code = $request->country_code;
            $users->mobile_number = $request->mobile_number;
            $users->facebook_id = $request->social_id;
            $users->user_type =$request->user_type;
            $randString = $this->generateRandomString();
            $users->otp = $randString;
            $users->is_active = 1;
            $users->amount = "0.000";
            $users->save();
            $is_address = Useraddress::where('user_id',$users['id'])->count();
            $user=['id'=>$users['id'],'notificationStatus' => $users['is_notification'],'email'=>$users['email'],'full_name'=>$users[$full_name],'otp'=>$randString,'country_code'=>$users['country_code'],'mobile_number'=>$users['mobile_number'],'is_verified_mobile'=>$users['is_verified_mobile'],'is_active'=>$users['is_active'],'profile_pic'=>isset($users['profile_pic'])?$users['profile_pic']:'','wallet_amount'=>$users['amount'],'user_type'=>$users['user_type'],'is_address'=>$is_address];
            $status=2;
            $message=__('message.otpVerification');
            if(app()->getLocale()=="ar"){
              $messageContent = "Use ".$randString." to verify your mobile number for MAAK account";
              }else{
              $messageContent = "Use ".$randString." to verify your mobile number for MAAK account";
              }
              $mobileNumber = $users->country_code.$users->mobile_number;
              $messageSuccess = $this->sendSmsonMobile($messageContent,$mobileNumber);
        }
      }

       /********type 5 for apple id via login****/
       if($request->type ==5){

        $users = User::where(['apple_id'=>$request->social_id,'user_type'=>$request->user_type])->first();
          if(!empty($users)){
            $is_address = Useraddress::where('user_id',$users['id'])->count();
            if($users['is_active'] ==1){
              // if($users['is_verified_mobile'] ==1){
                $user=['id'=>$users['id'],'email'=>$users['email'],'full_name'=>$users[$full_name],'country_code'=>$users['country_code'],'mobile_number'=>$users['mobile_number'],'is_verified_mobile'=>$users['is_verified_mobile'],'is_active'=>$users['is_active'],'profile_pic'=>isset($users['profile_pic'])?$users['profile_pic']:'','wallet_amount'=>$users['amount'],'user_type'=>$users['user_type'],'is_address'=>$is_address];
                $status=1;
                $message=__('message.success');
              // }else{
              //     $user = User::find($users['id']);
              //     $randString = $this->generateRandomString();
              //     $user->otp = $randString;
              //     $user->save();
              //     if(app()->getLocale()=="ar"){
              //       $messageContent = "Use ".$randString." to verify your mobile number for MAAK account";
              //     }else{
              //       $messageContent = "Use ".$randString." to verify your mobile number for MAAK account";
              //     }
              //     $mobileNumber = $users->country_code.$users->mobile_number;
              //     $messageSuccess = $this->sendSmsonMobile($messageContent,$mobileNumber);
              //     $user=['id'=>$users['id'],'email'=>$users['email'],'full_name'=>$users[$full_name],'otp'=>$randString,'country_code'=>$users['country_code'],'mobile_number'=>$users['mobile_number'],'is_verified_mobile'=>$users['is_verified_mobile'],'is_active'=>$users['is_active'],'profile_pic'=>isset($users['profile_pic'])?$users['profile_pic']:'','wallet_amount'=>$users['amount'],'user_type'=>$users['user_type'],'is_address'=>$is_address];
              //     $status=2;
              //     $message=__('message.otpVerification');
              // }
            }else{
              return response(['status'=>0,'message'=>'Your account has been disabled, Please contact to administrator.']);
            }
          }else{

            $validatoremail= Validator::make($request->all(),[
              'email'=>'unique:users|max:50',
            ]);
            if($validatoremail->fails()){
              return response()->json(['status'=>0,'message'=>$validatoremail->errors()->first()]);
            }

            if(!empty($request->mobile_number)){
              
              $validatormobile= Validator::make($request->all(),[
                'mobile_number'=>'unique:users|max:50',
              ]);

              if($validatormobile->fails()){
                return response()->json(['status'=>0,'message'=>$validatormobile->errors()->first()]);
              }
            }
           

          if($request->email == ''){
            if($request->mobile_number ==''){
                return response()->json(['status'=>3,'is_mobile'=>0,'is_email'=>0,'message'=>'Email and mobile number field is required']);
              }else{
                return response()->json(['status'=>3,'is_mobile'=>1,'is_email'=>0,'message'=>'Email field is required']);
            }
          }
          
          if($request->mobile_number == '' && $request->type != 5 ){
              if($request->email ==''){
                return response()->json(['status'=>3,'is_mobile'=>0,'is_email'=>0,'message'=>'Mobile number and email field is required']);
              }else{
                return response()->json(['status'=>3,'is_mobile'=>0,'is_email'=>1,'message'=>'Mobile number field is required']);
              }
          }

          if($request->email =='' && $request->mobile_number ==''){
            return response()->json(['status'=>3,'is_mobile'=>0,'is_email'=>0,'message'=>'Email and mobile number field is required']);
          }

          $users =new User;
          $users->$full_name = isset($request->full_name)?$request->full_name:'';
          $users->email = $request->email;
          $users->country_code = $request->country_code;
          $users->mobile_number = $request->mobile_number;
          $users->apple_id = $request->social_id;
          $users->user_type =$request->user_type;
          $randString = $this->generateRandomString();
          $users->otp = $randString;
          $users->is_active = 1;
          $users->amount = "0.000";
          $users->save();
          $is_address = Useraddress::where('user_id',$users['id'])->count();
          $user=['id'=>$users['id'],'notificationStatus' => $users['is_notification'],'email'=>$users['email'],'full_name'=>$users[$full_name],'otp'=>$randString,'country_code'=>$users['country_code'],'mobile_number'=>$users['mobile_number'],'is_verified_mobile'=>$users['is_verified_mobile'],'is_active'=>$users['is_active'],'profile_pic'=>isset($users['profile_pic'])?$users['profile_pic']:'','wallet_amount'=>$users['amount'],'user_type'=>$users['user_type'],'is_address'=>$is_address];
          $status=1;
          $message=__('message.otpVerification');

          if(app()->getLocale()=="ar"){
            $messageContent = "Use ".$randString." to verify your mobile number for MAAK account";
            }else{
            $messageContent = "Use ".$randString." to verify your mobile number for MAAK account";
            }
            $mobileNumber = $users->country_code.$users->mobile_number;
            $messageSuccess = $this->sendSmsonMobile($messageContent,$mobileNumber);
      }
    }

      /*****************Guest updation for new vehocle and another entry **************/
      if(!empty($request->guest_id)){

        $guestuserdetails = $this->userexist($request->guest_id);
        if(!empty($guestuserdetails['guest_id'])){
            $vehicles = Vehicles::where(['user_id'=>$guestuserdetails['user_id'],'guest_id'=>$guestuserdetails['guest_id']])->get();
            if(!empty($vehicles)){
              $myvehiclesIDs=[];
              foreach ($vehicles as $key => $value) {
                $adduserforvehicles = Vehicles::find($value['id']);
                $adduserforvehicles->user_id = $users['id'];
                $adduserforvehicles->guest_id = '';
                $adduserforvehicles->is_primary = '0';
                $adduserforvehicles->save();
                $myvehiclesIDs[] =$value['id'];
              }
              $update = ['is_primary'=>'1'];
              $myvehicleActiveId = end($myvehiclesIDs);
              Vehicles::where(['id'=>$myvehicleActiveId])->update($update);
            }
            $insurance = Insurancecardetails::where(['user_id'=>$guestuserdetails['user_id'],'guest_id'=>$guestuserdetails['guest_id']])->get();
            if(!empty($insurance)){
              foreach ($insurance as $insurancedata) {
                $insuranceadd = Insurancecardetails::find($insurancedata['id']);
                $insuranceadd->user_id = $users['id'];
                $insuranceadd->guest_id = '';
                $insuranceadd->save();
              }
            }
        }

      }

      /**************************End data***************************/

      /***insert device token ****/
      
      $device = Device::updateOrCreate([
        'user_id'      => $user['id'],
        'device_token' => $request->device_token,
        'device_type'  => $request->device_type,
        'uuid'         => !empty($request->uuid) ? $request->uuid : null 
      ],[
        'user_id'      => $user['id'],
        'device_token' => $request->device_token,
        'device_type'  => $request->device_type,
        'uuid'         => !empty($request->uuid) ? $request->uuid : null 
      ]);
      
      $response =  $users->createToken('MyApp')->accessToken; //generate token

      if($response){
        return response()->json(['status'=>$status,'message'=>$message,'user'=>$user,'auth'=>$response]);
      }else{

        return response()->json(['status'=>0,'message'=>'failed']);
      }
    }

  }

  public function forgotpassword(Request $request)
  {
    if($request->isMethod('post')){

      $validator= Validator::make($request->all(),[
        'email_or_mobile'=>'required',
      ]);

      if($validator->fails()){
        return response()->json(['status'=>0,'message'=>"Email or mobile number is required"]);
      }

      $user = User::where('email',$request->email_or_mobile)->orwhere('mobile_number',$request->email_or_mobile)->first();

      if(!empty($user))
      {
     
          if($user->is_verified_mobile==1)
          {

            $email= $user->email;
            $user->password_reset_token=base64_encode($user->id.'-'.time());
            $user->save();

            if (filter_var($request->email_or_mobile, FILTER_VALIDATE_EMAIL))
            {
              Mail::to($email)->send(new Forgotpassword($user,$user->password_reset_token));
              $successmessage='Reset password link has been sent your registerd email address.';
            
            }else{
              $url = url('/').'/resetPassword/'.$user->password_reset_token;
              if(app()->getLocale()=="ar"){
              $messageContent = "Use ".$url." to reset password for your MAAK account.";
              }else{
              $messageContent = "Use ".$url." to reset password for your MAAK account.";
              }
              $mobileNumber = $user->country_code.$user->mobile_number;
              $messageSuccess = $this->sendSmsonMobile($messageContent,$mobileNumber);
              $successmessage=__('message.resetLinkPassword');

            }
            
            return response()->json(['status'=>1,'message'=>$successmessage]);

          }else{
            return response()->json(['status'=>0,'message'=>__('message.mobileNotVerified')]);
          }
        }else{
          return response()->json(['status'=>0,'message'=>__('message.chooseAnotherEmail')]);
        }
      }
  }

  public function testsendSmsonMobile(Request $request)
  {
    try{
     
      $message = 'How Are you?';

      $mnumber = $request->mobile_number;
      
      $client = new Client();

      $url =config('app.SMS_URL')."?username=".config('app.SMS_USER_NAME')."&password=".config('app.SMS_PASSWORD')."&customerid=".config('app.SMS_CUSTOMER_ID')."&sendertext=MAAK&messageBody=".$message."&recipientnumbers=".$mnumber."&defdate=&isBlink=false&isFlash=false";
     
      $response = $client->request('GET',$url);
     
      $body = $response->getBody();
  
      $responseResult = new \SimpleXMLElement($response->getBody()->getContents());

      $responseArr = [
        'result'    => (string)$responseResult->Result,
        'message'   => (string)$responseResult->Message,
        'netPoints' => (string)$responseResult->NetPoints
      ];

      return $responseArr;
  
    }catch (\Exception $e){

      $responseArr = [
        'result'    => "false",
        'message'   => "Network not responding",
        'netPoints' => 0
      ];

      return  $responseArr;
    }
    
  }
 
  public function guestuserlogin(Request $request)
  {
    if($request->isMethod('post')){
      $full_name = "full_name_".app()->getLocale();
      $validator= Validator::make($request->all(),[
        'is_guest'=>'required|numeric',
        'device_type'=> 'required',
        'device_token' => 'required'
      ]);
        if($validator->fails()){
              return response()->json(['status'=>0,'message' => $validator->errors()->first()]);
        }
        $user = User::firstOrCreate(
            ['email' => 'guestuser@maak.live','full_name_en' =>'Guest','mobile_number' =>'12345','full_name_ar' =>'Guest','is_guest'=>1,'user_type'=>'5'],
            ['email' => 'guestuser@maak.live']
        );
        $existGuestUser = Device::where(['device_type'=>$request->device_type,'device_token'=>$request->device_token])->first();
        if(!empty($existGuestUser['guest_id'])){
          $users=['guest_id'=>$existGuestUser['guest_id'],'full_name'=>$user[$full_name],'user_type'=>'5'];
        }else{
          $randomNumber = abs(crc32(uniqid()));
          $device = new Device;
          $device->device_type = isset($request->device_type)?$request->device_type:'';
          $device->device_token = isset($request->device_token)?$request->device_token:'';
          $device->user_id=$user['id'];
          $device->guest_id=$randomNumber;
          $device->save();
          $users=['guest_id'=>$randomNumber,'full_name'=>$user[$full_name],'user_type'=>'5'];
        }
        $response =  $user->createToken('MyApp')->accessToken; //generate token

        if($response){
          return response()->json(['status'=>1,'message'=>'success','user'=>$users,'auth'=>$response]);
        }else{
          return response()->json(['status'=>0,'message'=>'failed']);
        }
    }
  }

  public function getlastvalues(Request $request)
  {
    $guestuserdetails = $this->userexist($request->guest_id);
    $vehicles = Vehicles::where(['user_id'=>$guestuserdetails['user_id'],'guest_id'=>$guestuserdetails['guest_id']])->get();
    if(!empty($vehicles)){
      $nn=[];
      foreach ($vehicles as $key => $value) {
        $nn[] = $value['id'];
      }
      $nnn = end($nn);
      dd($nnn);
    }
  }

  public function sendSmsonMobile($message,$mobileNumber)
  {
    //$message = "Use ".rand(0000,9999)." to verify your mobile number for MAAK account";
    //$mnumber = "96566751446";
    if (strpos($mobileNumber, '+') !== false) {
      $mnumber = str_replace("+","",$mobileNumber);
    }else{
      $mnumber = $mobileNumber;
    }
    // $url = "https://www.smsbox.com/SMSGateway/Services/Messaging.asmx/Http_SendSMS?username=Khaliefa&password=Qadsiaaaa&customerId=1812&senderText=SMSBOX.COM&messageBody=test&recipientNumbers=96566751446&defdate=&isBlink=false&isFlash=false";
    $client = new Client();
    // $res = $client->request('GET',$url);
    $url ="https://www.smsbox.com/SMSGateway/Services/Messaging.asmx/Http_SendSMS?username=".config('app.SMS_USER_NAME')."&password=".config('app.SMS_PASSWORD')."&customerId=".config('app.SMS_CUSTOMER_ID')."&senderText=MAAK&messageBody=".$message."&recipientNumbers=".$mnumber."&defdate=&isBlink=false&isFlash=false";

    $response = $client->request('GET',$url);
    $body = $response->getBody();
    $code = $response->getStatusCode();

    // Implicitly cast the body to a string and echo it
    //echo $body;
    // Explicitly cast the body to a string
    $stringBody = (string) $body;
    // Read 10 bytes from the body
    // $tenBytes = $body->read(10);
    // Read the remaining contents of the body as a string
    // $remainingBytes = $body->getContents();
      $strFailed = stristr($stringBody,'false');
      $strSuccess = stristr($stringBody,'true');
      $false =  substr($strFailed,0,5);
      $true =  substr($strSuccess,0,4);
      if($false ==='false'){
        return $false;
      }
      if($true ==='true'){
      return $true;
    }

  }

  public function logout(Request $request)
  {
   
    $user = \App\User::where('id',$request->user_id)->first();
    $user->deviceInfo()->delete();

    if(!empty($user->token())){

    }else{
      
      return response()->json(Api::apiSuccessResponse(__('message.userLogout')),200);
    }


    if (Auth::check()) {

      Auth('api')->user()->token()->delete();

      Auth::user()->deviceInfo()->delete();

      return response()->json(Api::apiSuccessResponse(__('message.userLogout')),200);

    }else{

      return response()->json(Api::apiErrorResponse(__('message.unauthorized')),401);
    }

  }

}
