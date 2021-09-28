<?php

namespace App\Http\Controllers\Api;
use Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\Models\Admin;
use App\Models\Wallet;
use GuzzleHttp\Client;
use App\Models\Country;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{

  public function updateprofile(Request $request)
  { 
        $name = "full_name_".app()->getLocale();
        if($request->isMethod('post')) {
            $validator = Validator::make($request->all(), [
               'name' => 'required|max:20',
               'user_id' => 'required|exists:users,id',
               //'user_id' => 'exists:users,id'
            ]);
            // if($validator->fails()){
            //     return response()->json(['status'=>0,'message'=> $validator->errors()->first()]);
            //  }
            $user   = User::find($request->user_id);
            if($request->email !=''){
              $validator1   = Validator::make($request->all(), [
                 'email'   =>   'required|email|unique:users,email,'.$user->id,
                 'mobile_number'    => 'required|numeric'
             ]);
            }
            if ($validator1->fails()) {
              return response()->json(['status'=>0,'message'=> $validator1->errors()->first()]);
            }
            if($request->mobile_number !=''){
              $validator2   = Validator::make($request->all(), [
                 // 'mobile_number'   =>   'required|numeric|unique:users,mobile_number,'.$user->id,
                'mobile_number'    => ['required','numeric',Rule::unique('users')->ignore($user->id)->where(function ($query)use($user){
                                return $query->where('user_type', $user->user_type);
                              })],
             ]);

             if ($validator2->fails()) {
              return response()->json(['status'=>0,'message'=> $validator2->errors()->first()]);
             }

            }
         
           $user->$name=$request->name;
           $user->email=$request->email;
           if($request->profile_pic !='') {
             $user->profile_pic=$request->profile_pic;
           }
           if(!empty($request->mobile_number) && !empty($request->country_code)){
              if($user->country_code.$user->mobile_number != $request->country_code.$request->mobile_number){
                $randString = $this->generateRandomString();
                $user->otp = $randString;
                $user->temp_country_code = $request->country_code;
                $user->temp_mobile_number = $request->mobile_number;
                //$user->country_code = $request->country_code;
                //$user->mobile_number = $request->mobile_number;
                //$user->is_verified_mobile = 0;
                $mobileNumber = $user->country_code.$user->mobile_number;
                $status=2;
                $message="Otp has been sent succesfully, ".$user->temp_country_code.'-'.$user->temp_mobile_number." Please verified otp,";
                if(app()->getLocale()=="ar"){
                  $messageContent = "Use ".$randString." to verify your mobile number for MAAK account";
                }else{
                  $messageContent = "Use ".$randString." to verify your mobile number for MAAK account";
                }
                $messageSuccess = $this->sendSmsonMobile($messageContent,$mobileNumber);
              }else{
                $user->country_code = $request->country_code;
                $user->mobile_number = $request->mobile_number;
                $status=1;
                $message='Your profile has been updated successfully.';
              }
            }else{
             $user->country_code = $request->country_code;
             $user->mobile_number = $request->mobile_number;
             $status=1;
             $message='Your profile has been updated successfully.';
           }
           $user->save();
           if(!empty($request->country_code) && $request->country_code == $user->temp_country_code){
             $coutrycode = $request->country_code;
           }else{
             $coutrycode = $user->country_code;
           }
           if(!empty($request->mobile_number) && $user->temp_mobile_number == $request->mobile_number){
             $mobile = $request->mobile_number;
           }else{
             $mobile = $user->mobile_number;
           }
           $users=['id'=>$user->id,'name'=>$user->$name,'email'=>$user->email,'country_code'=>$coutrycode,'mobile_number'=>$mobile,'profile_pic'=>$user->profile_pic];
           return response()->json(['status'=>$status,'message'=>$message,'details'=>$users]);
       }
  }
  
  public function changepassword(Request $request)
  {  
    $validator= Validator::make($request->all(),[
      'user_id'          =>'required|exists:users,id',
      'current_password' => 'required|max:20',
      'new_password'     => 'required|max:20',
    ]);
    if($validator->fails()){
    return response()->json(['status'=>0,'message'=> $validator->errors()->first()]);
    }
    $user   = User::find($request->user_id);
    if (Hash::check($request->current_password, $user->password)) {
    $user->password  = Hash::make($request->new_password);
    $user->save();
    return response()->json(['status'=>1,'message'=>'Password has been changed successfully']);
    }else{
      return response()->json(['status'=>0,'message'=>'Current password does not match!']);
    }
    
  }


  public function notificationonoff(Request $request)
  {
    $validator= Validator::make($request->all(),[
      'user_id'          =>'required|exists:users,id',
    ]);

    if($validator->fails()){
      return response()->json(['status'=>0,'message'=> $validator->errors()->first()]);
    }

    $user   = User::find($request->user_id);

    if($user->is_notification==0){
      $user->is_notification=1;
      $message ="Notification on succesfully.";
    }else if($user->is_notification==1){
      $user->is_notification=0;
      $message ="Notification off succesfully.";
    }
    
    $user->save();
    
    return response()->json(['status'=>1,'message'=>$message,'notificationStatus' => $user->is_notification]);
    
  }


  public function generateRandomString($length = 4) 
  {
    $characters       = '0123456789';
    $charactersLength = strlen($characters);
    $randomString     = '';

    for ($i = 0; $i < $length; $i++) {
      $randomString .= $characters[rand(0, $charactersLength - 1)];
    }

    return $randomString;
  }

  public function sendSmsonMobile($message,$mobileNumber)
  {
    if (strpos($mobileNumber, '+') !== false) {
      $mnumber = str_replace("+","",$mobileNumber);
    }else{
      $mnumber = $mobileNumber;
    }

    $client = new Client();
    $url =config('app.SMS_URL')."?username=".config('app.SMS_USER_NAME')."&password=".config('app.SMS_PASSWORD')."&customerid=".config('app.SMS_CUSTOMER_ID')."&sendertext=SMSBOX.COM&messageBody=".$message."&recipientnumbers=".$mnumber."&defdate=&isBlink=false&isFlash=false";

    //$url ="https://www.smsbox.com/SMSGateway/Services/Messaging.asmx/Http_SendSMS?username=".env('SMS_USER_NAME')."&password=".env('SMS_PASSWORD')."&customerId=".env('SMS_CUSTOMER_ID')."&senderText=SMSBOX.COM&messageBody=".$message."&recipientNumbers=".$mnumber."&defdate=&isBlink=false&isFlash=false";
    $response = $client->request('GET',$url);
    $body = $response->getBody();
    $code = $response->getStatusCode();
    $stringBody = (string) $body;
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

}
