<?php

namespace App\Http\Controllers\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use App\User;
use App\Models\Orders;
use App\Revenue;
use DB;
use App\Models\Service;

class DashboardController extends Controller
{
  public function __construct(
    User       $user,
    Orders     $orders,
    Revenue    $revenue,
    Service    $service)
  {
    $this->middleware('auth');
    $this->user     = $user;
    $this->orders   = $orders;
    $this->revenue  = $revenue;
    $this->service  = $service;
  }

  public function index(Request $request)
  { 

    $startDate = Carbon::now()->format('Y-m-d')." 00:00:00";
    $endDate   = Carbon::now()->format('Y-m-d'). " 23:59:59";

    $todayUser     = $this->user->where('user_type','2')->whereBetween('created_at',[ $startDate,$endDate ])->get()->count();
    $totalUser     = $this->user->where('user_type','2')->get()->count();

    $todayServiceProvider   = $this->user->where('user_type','1')->whereBetween('created_at',[ $startDate,$endDate ])->get()->count();
    $totalServiceProvider   = $this->user->where('user_type','1')->get()->count();

    $todayOrders     = $this->orders->where('payment_status','2')->whereBetween('created_at',[ $startDate,$endDate ])->get()->count();
    $totalOrders     = $this->orders->where('payment_status','2')->get()->count();

    $totalRevenue   = $this->revenue->whereHas('orders', function($query){
      $query->where('payment_status','2');
    })->sum('amount');

    $todayRevenue   = $this->revenue->whereHas('orders', function($query) use ($startDate,$endDate){
      $query->where('payment_status','2')
        ->whereBetween('created_at',[ $startDate,$endDate ]);
    })->sum('amount');

    $activeService = $this->service->where('is_active','1')->get()->count();

    return view('admin.dashboard.home',compact('todayUser','totalUser','todayServiceProvider','totalServiceProvider','todayOrders','totalOrders','totalRevenue','todayRevenue','activeService'));
  }

  public function revenueGraph(Request $request)
  {
    $revenue = $this->revenue->select(
      DB::raw('SUM(amount) as `revenue`'), 
      DB::raw("DATE_FORMAT(created_at, '%m-%Y') new_date"),  
      DB::raw('YEAR(created_at) year, MONTHNAME(created_at) month'))
      ->groupby('year','month')
      ->orderBy('created_at','asc')
      ->get();

    return response()->json(['message' => 'Revenue data fetch successfully','data'=>$revenue]);
  }

  public function orderGraph(Request $request)
  {
    $revenue = $this->orders->select(
      DB::raw('COUNT(id) as `order_count`'), 
      DB::raw("DATE_FORMAT(created_at, '%m-%Y') new_date"),  
      DB::raw('YEAR(created_at) year, MONTHNAME(created_at) month'))
      ->groupby('year','month')
      ->orderBy('created_at','asc')
      ->get();

    return response()->json(['message' => 'booking data fetch successfully','data'=>$revenue]);
  }

  public function dashbaordCardDetails(Request $request)
  { 

    $startDate = Carbon::now()->format('Y-m-d')." 00:00:00";
    $endDate   = Carbon::now()->format('Y-m-d'). " 23:59:59";

    $todayUser     = $this->user->where('user_type','2')->whereBetween('created_at',[ $startDate,$endDate ])->get()->count();
    $totalUser     = $this->user->where('user_type','2')->get()->count();

    $todayServiceProvider   = $this->user->where('user_type','1')->whereBetween('created_at',[ $startDate,$endDate ])->get()->count();
    $totalServiceProvider   = $this->user->where('user_type','1')->get()->count();

    $todayOrders     = $this->orders->where('payment_status','2')->whereBetween('created_at',[ $startDate,$endDate ])->get()->count();
    $totalOrders     = $this->orders->where('payment_status','2')->get()->count();

    $totalRevenue   = $this->revenue->whereHas('orders', function($query){
      $query->where('payment_status','2');
    })->sum('amount');

    $todayRevenue   = $this->revenue->whereHas('orders', function($query) use ($startDate,$endDate){
      $query->where('payment_status','2')
        ->whereBetween('created_at',[ $startDate,$endDate ]);
    })->sum('amount');

    $activeService = $this->service->where('is_active','1')->get()->count();

    return response()->json([
      'todayUser'            => $todayUser,
      'totalUser'            => $totalUser,
      'todayServiceProvider' => $todayServiceProvider,
      'totalServiceProvider' => $totalServiceProvider,
      'todayOrders'          => $todayOrders,
      'totalOrders'          => $totalOrders,
      'totalRevenue'         => $totalRevenue,
      'todayRevenue'         => $todayRevenue,
      'activeService'        => $activeService
    ]);

  }


}
