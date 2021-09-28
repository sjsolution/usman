<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Device;
use App\Traits\PushNotifications;
use App\Models\UserPushNotification;
use DB;
use App\UserNotification;
use Yajra\Datatables\Datatables;
use Edujugon\PushNotification\PushNotification;

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
        })->get()->unique('user_id');
       
        foreach($userDevice as $key){
           
            $email['user-'.$key->user['id']] = $key->user['email'];
        } 
        return view('admin.push_notification.push_send',compact('email'));
    }

    public function userlist()
    {
        $email = [];

        $userDevice = $this->device->whereHas('user',function($q){
          $q->where('user_type',2);
        })->get()->unique('user_id');
       
        foreach($userDevice as $key){
           
            $email[] = $key->user['email'];
        } 

        return response()->json(['list' => $email]);
    }

    public function sendUserPushNotification(Request $request)
    {
      
        $notification = $this->userPushNotification->where('id',$request->notification_id)->first();
       
        $userDevice = $this->device->whereHas('user',function($q){
            $q->where('user_type',2)->where('is_notification',1);
        })->get();

        //All user
        if($notification->send_to == 0){

            foreach ($userDevice as $key) {
                if($key->user['is_language'] == 'en' && !empty($key->device_token)){
                    
                     $this->sendNotification($notification->title_en,$notification->description_en,$key->device_token,'','','');
                }elseif($key->user['is_language'] == 'ar' && !empty($key->device_token)){
                    $this->sendNotification($notification->title_ar,$notification->description_ar,$key->device_token,'','','');
                }
            }
        }
     
        //Specific user
        if($notification->send_to == 1){

            $specificUserNotify = $notification->specificUser()->pluck('user_id')->toArray();
    
            $userDevice =  $this->device->whereHas('user',function($q){
                $q->where('user_type',2)->where('is_notification',1);
            })->whereIn('user_id',$specificUserNotify)->get();
           
            foreach ($userDevice as $key) {

                if($key->user['is_language'] == 'en' && !empty($key->device_token)){
                    $this->sendNotification($notification->title_en,$notification->description_en,$key->device_token,'','','');
                }elseif($key->user['is_language'] == 'ar' && !empty($key->device_token)){
                    $this->sendNotification($notification->title_ar,$notification->description_ar,$key->device_token,'','','');
                }

            }

            for($i=0; $i< count($specificUserNotify); $i++){
                
                $this->userNotification->create([
                    'user_id'           => $specificUserNotify[$i],
                    'notification_type' => '3',
                    'title_en'          => $notification->title_en,
                    'title_ar'          => $notification->title_ar,
                    'body_en'           => $notification->description_en,
                    'body_ar'           => $notification->description_ar
                ]);
            }

           
             
        }

        $notification->status = 1;
        $notification->save();

        return response()->json(['status' => 1,'message' => 'Notification send successfully']);


    }

    public function store(Request $request)
    {
        
        $pushNotify = $this->userPushNotification->create([
            'title_en'        => $request->title_en,
            'title_ar'        => $request->title_ar,
            'description_en'  => $request->message_en,
            'description_ar'  => $request->message_ar,
            'send_to'         => $request->send_to
        ]);

        if($request->send_to==1){
             
            foreach($request->email as $key){
               
                $pushNotify->specificUser()->create([
                    'user_id'  => (int)str_replace('user-','',$key)
                ]);
            }
            
        }

        return redirect('admin/settings/push-notification/list')->with(['success' => 'Push Notification saved successfully']);

    }

    public function notificationList()
    {
        return view('admin.push_notification.list');
    }
    public function notificationDelete($id)
  {
         $this->userPushNotification->where('id',$id)->delete();
      return response()->json(['data' => 'Notification Successfully Deleted']);
  } 

    public function notificationListView(Request $request)
    {
        $users = $this->userPushNotification->whereNull('user_id')->orderBy('id','desc');

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
                if($users->send_to == 1)
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
                    <a href='notification/view/".$users->id."' class='btn btn-gradient-danger btn-sm' title='view notification'><i class='fa fa-eye'></i></a>
                    <span class='btn btn-gradient-info btn-sm' title='send notification' onclick='sendNotification(event,".$users->id.")'><i class='fa fa-send-o'></i></span>
                    <span class='btn btn-gradient-danger  btn-sm' data-toggle='tooltip' data-placement='top' title='Delete' onclick='deleteNotification(event,".$users->id.")'><i class='fa fa-trash-o'></i></span>";
                // }
             
            })
            ->make(true);
    }

    public function notificationView(Request $request,UserPushNotification $notification)
    {
        return view('admin.push_notification.view',compact('notification')); 
    }
    public function sendPushToUser(){
        try{
             
                 $push = new PushNotification('fcm');
       
                    $push->setUrl('https://fcm.googleapis.com/fcm/send');

                    $response =  $push->setMessage([
                        'notification' => [
                            'title'  => "Maak Update",
                            'body'   => "Stay home, Stay safe",
                            // 'notificationType' => $notification_type,
                            'sound'  => 'default'
                        ],
                        'data' => [
                            // 'notificationType' => $notification_type,
                            'image'            => $image ?? '',
                            'title'  => "Maak Update",
                            'body'   => "Stay home, Stay safe",
                        ],
                        'content_available' =>true,
                        'mutable_content'   =>true,  
                    ])
                    // ->setApiKey('')
                    ->setApiKey("AAAAEXjTM5U:APA91bEKfyHX98dZK7qN6owZO9U_ys9kDspyNo8WrrT4WniCbfHw2EjYKows0qnvGk06d-6TWxoVP7WmOKxq47xfGBXVB6qtPAs3xybt4iWlz_Tdl-nN7-xP24SmhwVGDbI40QoRUUjb")
                    // ->setDevicesToken('eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOjY5LCJpc3MiOiJodHRwczovL21vcm5pa3cuY29tL2FwaS9wcm92aWRlci9vYXV0aC90b2tlbiIsImlhdCI6MTYyMDAxOTU4MSwiZXhwIjoxNjIwMzc5NTgxLCJuYmYiOjE2MjAwMTk1ODEsImp0aSI6IlB4UEVvRlQ1WldBbzJiOVAifQ.zRF2OiOZe0yzkx0CzSM6e8tVAwgTCgWnBccN46LhwMo')
                    ->setDevicesToken("fbxz1CqWku8:APA91bEGIC-nxng0F_PAEBrJPe-dv9Oq4vt7HFXwRr5MpZikw2PZL-iZf3ERj-T9-e_IpX6LZlPC6ndG3eSunJzQD5SXeXEpwr-ILhj2IHhhiBwkxgNWRP2f7ob9DdMhnC-x4_WmGfcZ")
                    ->send()
                    ->getFeedback();
                    dd($response);
            


        } catch(\Exception $e){
            return $e;
        }

    }
}
