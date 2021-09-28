<?php

namespace App\Http\Controllers\ServiceProvider;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Device;
use App\Traits\PushNotifications;
use App\Models\UserPushNotification;
use Auth;
use DB;
use Yajra\Datatables\Datatables;
use App\UserNotification;
class PushNotificationController extends Controller
{
    use PushNotifications;

    public function __construct(
        Device                       $device,
        UserPushNotification         $userPushNotification,
        UserNotification             $userNotification
    )
    {
        $this->device                       = $device;
        $this->userPushNotification         = $userPushNotification;
        $this->userNotification             = $userNotification;
    }

    public function index()
    {
        $email = [];

        $userDevice = $this->device->whereHas('user',function($q){
            $q->where('user_type',2);
          })
          ->whereHas('order',function($query){
             $query->where('payment_status','2')
                  ->where('service_provider_id',Auth::user()->id);
          })
          ->get()->unique('user_id');
       
        foreach($userDevice as $key){
           
            $email['user-'.$key->user['id']] = $key->user['email'];
        } 

        return view('service-providers.push_notification',compact('email'));
    } 

    public function store(Request $request)
    {
        
        $pushNotify = $this->userPushNotification->create([
            'title_en'        => $request->title_en,
            'title_ar'        => $request->title_ar,
            'description_en'  => $request->message_en,
            'description_ar'  => $request->message_ar,
            'send_to'         => $request->send_to,
            'user_id'         => Auth::user()->id
        ]);

        if($request->send_to==1){
             
            foreach($request->email as $key){
               
                $pushNotify->specificUser()->create([
                    'user_id'  => (int)str_replace('user-','',$key)
                ]);
            }
            
        }

        return redirect('settings/push-notification/list')->with(['success' => 'Push Notification saved successfully']);

    }


    public function notificationList()
    {
        return view('service-providers.push_notification_list');
    }

    public function notificationView(Request $request,UserPushNotification $notification)
    {
        return view('service-providers.push_notification_view',compact('notification')); 
    }

    public function notificationListView(Request $request)
    {
        $users = $this->userPushNotification->where('user_id',Auth::user()->id)->orderBy('id','desc')->get();
      
        return Datatables::of($users)
            // ->addColumn('checkbox', function ($users) {
            //     return '<input type="checkbox" id="'.$users['id'].'" name="someCheckbox" class="userId" />';
            // })
            ->addColumn('status',function($users){
                if($users->status == 1)
                    return '<strong style="color:green;">Send</strong>'; 
                else
                    return '<strong style="color:red;">Not Send</strong>'; 
             })
             ->addColumn('send_to',function($users){
                if($users->send_to == '1')
                    return 'Specific User'; 
                else
                    return 'All User'; 
             })
            ->addColumn('action',function($users){

                // if($users->status == 1){
                //     return "
                //     <a href='notification/view/".$users->id."' class='btn btn-gradient-danger btn-sm' title='view'><i class='fa fa-eye'></i></a>";
                // }else{
                    return "
                    <a href='notification/view/".$users->id."' class='btn btn-gradient-danger btn-sm' title='View Notification'><i class='fa fa-eye'></i></a>
                    <span class='btn btn-gradient-info btn-sm' title='Send Notification' onclick='sendNotification(event,".$users->id.")'><i class='fa fa-send-o'></i></span>";
                //}
             
            })
            ->make(true);
    }

    public function userlist()
    {
        $email = [];

        $userDevice = $this->device->whereHas('user',function($q){
          $q->where('user_type',2);
        })
        ->whereHas('order',function($query){
           $query->where('payment_status','2')
                ->where('service_provider_id',Auth::user()->id);
        })
        ->get()->unique('user_id');
       
        foreach($userDevice as $key){
           
            $email[] = $key->user['email'];
        } 

        return response()->json(['list' => $email]);
    }

    public function sendUserPushNotification(Request $request)
    {
        $notification = $this->userPushNotification->where('id',$request->notification_id)->first();

        $userDevice = $this->device->whereHas('user',function($q){
            $q->where('user_type',2);
        })
        ->whereHas('order',function($query){
           $query->where('payment_status','2')
                ->where('service_provider_id',Auth::user()->id);
        })
        ->get();

        if(!empty( $userDevice)){
             //All user
            if($notification->send_to == 0){
                
                $userArr = [];

                foreach ($userDevice as $key) {

                    if($key->user['is_language'] == 'en' && !empty($key->device_token && $key->user['is_notification'] == 1)){
                        $this->sendNotification($notification->title_en,$notification->description_en,$key->device_token,'','','');
                    }elseif($key->user['is_language'] == 'ar' && !empty($key->device_token  && $key->user['is_notification'] == 1)){
                        $this->sendNotification($notification->title_ar,$notification->description_ar,$key->device_token,'','','');
                    }

                
                    if (!in_array($key->user['id'], $userArr)) {

                        $this->userNotification->create([
                            'user_id'           => $key->user['id'],
                            'title_en'          => $notification->title_en,
                            'title_ar'          => $notification->title_ar,
                            'body_en'           => $notification->description_en,
                            'body_ar'           => $notification->description_ar,
                            'notification_type' => '3'
                        ]);

                        $userArr[] = $key->user['id'];


                    }

                    $userArr[] = $key->user['id'];


                }
            }

        
            //Specific user
            if($notification->send_to == 1){

                $specificUserNotify = $notification->specificUser()->pluck('user_id')->toArray();
        
                $userDevice =  $this->device->whereIn('user_id',$specificUserNotify)->get();
                $userArr = [];
                foreach ($userDevice as $key) {

                    if($key->user['is_language'] == 'en' && !empty($key->device_token && $key->user['is_notification'] == 1)){
                        $this->sendNotification($notification->title_en,$notification->description_en,$key->device_token,'','','');
                    }elseif($key->user['is_language'] == 'ar' && !empty($key->device_token && $key->user['is_notification'] == 1)){
                        $this->sendNotification($notification->title_ar,$notification->description_ar,$key->device_token,'','','');
                    }

                  

                    if (!in_array($key->user['id'], $userArr)) {

                        $this->userNotification->create([
                            'user_id'           => $key->user['id'],
                            'title_en'          => $notification->title_en,
                            'title_ar'          => $notification->title_ar,
                            'body_en'           => $notification->description_en,
                            'body_ar'           => $notification->description_ar,
                            'notification_type' => '3'
                        ]);

                        $userArr[] = $key->user['id'];


                    }

                    $userArr[] = $key->user['id'];

                   
                }
                
            }

            $notification->status = 1;
            $notification->save();

            return response()->json(['status' => 1,'message' => 'Notification send successfully']);
       
        }else{
            
            toast('User not found','warning')->timerProgressBar();

            return back();
        }

        

    }
}
