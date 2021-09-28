<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Ratings;
use Yajra\Datatables\Datatables;


class ReviewController extends Controller
{
    public function __construct(Ratings $ratings)
    {
        $this->ratings = $ratings;
    }

    public function index()
    {  
        return view('admin.review');
    }

    public function list(Request $request)
    {
        $ratings = $this->ratings->with('order','user')->orderBy('id','desc')->get();
      
        return Datatables::of($ratings)
            ->addColumn('order_number',function($ratings){
                if(!empty($ratings->order))
                   return '<strong style="color:blue;cursor:pointer;" onclick="showOrderDetails(event,'.$ratings->order_id.');">'.$ratings->order->order_number.'</strong>';
                else
                   return '--';
            })
            ->addColumn('who_reviewed',function($ratings){
                return !empty($ratings->user) ? $ratings->user->full_name_en : '--';
            })
            ->addColumn('whom_reviwed',function($ratings){
                if($ratings->is_reviwed_technician == 1){
                   return !empty($ratings->order->user) ? $ratings->order->user->full_name_en : '--';
                }else{
                   return !empty($ratings->serviceProvider) ? $ratings->serviceProvider->full_name_en : '--';
                }
            })
            ->addColumn('reviews',function($ratings){
              
                return strlen($ratings->reviews) > 30 ? substr($ratings->reviews,0,30)."......" : $ratings->reviews;
           
            })
            ->addColumn('action',function($ratings){
               return '
                  <button class="btn btn-gradient-danger btn-sm"  data-toggle="tooltip" data-placement="top" title="Review Details" onclick="reviewDetails(event,'.$ratings->id.')"><i class="fa fa-eye"></i></button>'; 
            })
            ->make(true);  
    }

    public function details(Request $request)
    {
        $list['list'] =  $this->ratings->where('id',$request->review_id)->first();
        return $list;
    }
}
