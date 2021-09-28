<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Yajra\Datatables\Datatables;
use App\Notification;

class NotificationController extends Controller
{
    public function __construct(Notification $notification)
    {
      $this->middleware('auth');
      $this->notification = $notification;
    }
    
    public function index()
    {
      return view('admin.notification.notifications');
    }

    public function list(Request $request)
    {
      $notification = $this->notification->orderBy('id','desc');
      
      return Datatables::of($notification)
          ->make(true);
    }
}
