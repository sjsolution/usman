<?php

namespace App\Http\Controllers\ServiceProvider;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Yajra\Datatables\Datatables;
use App\Notification;
use Auth;

class NotificationController extends Controller
{
    public function __construct(Notification $notification)
    {
      $this->notification = $notification;
    }
    
    public function index()
    {
      return view('service-providers.notifications');
    }

    public function list(Request $request)
    {
      $notification = $this->notification->whereHas('orders',function($query){
          $query->where('service_provider_id',Auth::user()->id);
      })->orderBy('id','desc');

      return Datatables::of($notification)
          ->make(true);
    }
}
