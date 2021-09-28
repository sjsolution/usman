<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Pusher\Pusher;
use App\Notification;
use App\NotificationStatus;
use Auth;

class SendNotificationController extends Controller
{
    public function __construct(Notification $notification,NotificationStatus $notificationStatus)
    {
       $this->notification       = $notification;
       $this->notificationStatus = $notificationStatus;
    }

    public function adminNotificationRender(Request $request)
    {
       
        $notification['list'] = $this->notification->with('notificationStatus')->whereHas('notificationStatus')->orderBy('id','desc')->limit(50)->get();

        return $notification;
    }

    public function adminNotificationStatus(Request $request)
    {
        $this->notificationStatus->where('is_admin_seen',0)->update([
            'is_admin_seen' => 1
        ]);

        return response()->json(['message' => 'Admin seen notitfication']);

    }

    public function spNotificationRender(Request $request)
    {
        
        $notification['list'] = $this->notification->with('notificationStatus')->whereHas('notificationStatus',function($query){
            $query->where('user_id',Auth::user()->id);
        })->orderBy('id','desc')->limit(50)->get();

        return $notification;
    }

    public function spNotificationStatus(Request $request)
    {
        $this->notificationStatus->where('user_id',Auth::user()->id)->where('is_sp_seen',0)->update([
            'is_sp_seen' => 1
        ]);

        return response()->json(['message' => 'Service provider seen notitfication']);

    }

    public function spNotificationClicked(Request $request)
    {
       
        $this->notificationStatus->where('notification_id',$request->id)->where('user_id',Auth::user()->id)->update([
            'sp_is_clicked' => 1
        ]);

        return response()->json(['status' => 'success','message' => 'Notification clicked successfully']);

    }

    public function adminNotificationClicked(Request $request)
    {
       
        $this->notificationStatus->where('notification_id',$request->id)->update([
            'admin_is_clicked' => 1
        ]);

        return response()->json(['status' => 'success','message' => 'Notification clicked successfully']);

    }
}
