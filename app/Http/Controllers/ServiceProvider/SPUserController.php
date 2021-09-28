<?php

namespace App\Http\Controllers\ServiceProvider;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Orders;
use DB;
use Yajra\Datatables\Datatables;
use Auth;
use App\User;


class SPUserController extends Controller
{
    public function __construct(Orders $orders)
    {
        $this->orders = $orders;
    }

    public function index()
    {
        return view('service-providers.users');
    }

    public function list(Request $request)
    {
        $users = $this->orders->whereHas('user')->where('payment_status','2')->with('user')->where('service_provider_id',Auth::user()->id)->groupBy('user_id')->get();
    
        return Datatables::of($users)
        ->addColumn('username',function($orders){
            return !empty($orders->user) ? $orders->user->full_name_en : '--';
        })
        ->addColumn('email',function($orders){
            return !empty($orders->user) ? $orders->user->email : '--';
        })
        ->addColumn('phone',function($orders){
            return !empty($orders->user) ? $orders->user->mobile_number : '--';
        })
        ->addColumn('status',function($orders){
            if($orders->user->is_active){
                return '<div class="badge badge-gradient-success badge-pill">Active</div>';
            }else{
                return '<div class="badge badge-gradient-danger badge-pill">In-active</div>';
            }
        })
        ->addColumn('action',function($users){
            return "<a href='users/details/".$users->user->id."' class='btn btn-gradient-danger btn-sm' title='view'><i class='fa fa-eye'></i></a>";
            //  return '<i class="fa fa-eye ikn"  data-toggle="tooltip" data-placement="top" title="View" onclick="couponDetails(event,'.$users->id.')"></i>'; 
        })
        ->make(true);
    }

    /**
     * User proifle details
     */
    public function usersDetails(User $user)
    {
        return view('service-providers.user-details',compact('user'));  
    }

}
