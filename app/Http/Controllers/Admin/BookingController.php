<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Orders;
use Yajra\Datatables\Datatables;
use DB;
use App\Transaction;
use App\SPTechnician;
use App\Traits\PushNotifications;
use App\User;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\TransactionReport;
use Auth;
use App\SubOrderExtraAddonPaymentHistory;


class BookingController extends Controller
{
   use PushNotifications;

   public function __construct(Orders $orders,Transaction $transaction,User $user)
   {
      $this->orders      = $orders;
      $this->transaction = $transaction;
      $this->user        = $user;
   } 

   public function index()
   {
      return view('admin.booking.booking');
   }

   public function list()
   {     
      $orders = $this->orders->where('payment_status','<>','0')->select([
         "orders.*",
      DB::raw("(SELECT full_name_en FROM users WHERE orders.user_id = users.id) as user_name_en"),
      DB::raw("(SELECT full_name_ar FROM users WHERE orders.user_id = users.id) as user_name_ar"),
      DB::raw("(SELECT full_name_en FROM users as us WHERE us.id = orders.service_provider_id) as service_provider"),
      DB::raw("(SELECT us.full_name_en FROM booked_technicians join users as us ON us.id = booked_technicians.technician_id WHERE booked_technicians.order_id = orders.id) as technician"),
      DB::raw("(SELECT ser.name_en FROM suborders join service as ser ON ser.id = suborders.service_id WHERE suborders.order_id = orders.id) as service"),
      DB::raw("(SELECT ser.category_id FROM suborders join service as ser ON ser.id = suborders.service_id WHERE suborders.order_id = orders.id) as catgeory_id")
      ])->with(['serviceProvider'])
      ->orderBy('id','desc')->get();
      // dd($orders);
      return Datatables::of($orders)
         // ->order(function ($query) {
         //    if (request()->has('name')) {
         //       $query->orderBy('name', 'asc');
         //    }
         // })
         ->addColumn('category_name',function($orders){
            
               $category = \App\Models\Category::find($orders->catgeory_id);
            
               if(!empty($category))
                  return $category->name_en;
               else
                  return '--';
         })
          ->addColumn('username',function($orders){
            return !empty($orders->user_name_en) ? $orders->user_name_en.$orders->id : $orders->user_name_ar.$orders->id;
               
         })
         ->addColumn('catgeory_type',function($orders){

               $category = \App\Models\Category::find($orders->catgeory_id);

               if(!empty($category)){
                  if($category->type == 1)
                     return 'Normal';
                  elseif($category->type == 2)
                     return 'Insurance';
                  elseif($category->type == 3)
                     return 'Emergency';
               }else
                  return '--';
               
         })
         ->addColumn('sp_name',function($orders){
               if(!empty($orders->service_provider))
               return $orders->service_provider;
               elseif(!empty($orders->technician))
               return $orders->technician;
               else
               return '--';
         })
         ->addColumn('payment_type',function($orders){
            if($orders->payment_type == '1')
               return 'Wallet';
            else if($orders->payment_type == '2')
               return 'Knet';
            else if($orders->payment_type == '3')
               return 'Wallet & Knet';
            else if($orders->payment_type == '4')
               return 'Credit Card';
            else if($orders->payment_type == '5')
               return 'Wallet & Credit Card';
            else
               return '--';
         })
         ->addColumn('payment_status',function($orders){
            if($orders->payment_status == '0')
               return '<strong style="color:blue">Pending<strong>';
            else if($orders->payment_status == '1')
               return '<strong style="color:cyan">Processing<strong>';
            else if($orders->payment_status == '2')
               return '<strong style="color:green">Success<strong>';
            else if($orders->payment_status == '3')
               return '<strong style="color:red">Failed<strong>';
            })
         ->addColumn('status',function($orders){
            if($orders->status == '0')
               return '<strong style="color:blue">Pending<strong>';
            else if($orders->status == '1')
               return '<strong style="color:cyan">Start<strong>';
            else if($orders->status == '2')
               return '<strong style="color:green">Completed<strong>';
            else if($orders->status == '3')
               return '<strong style="color:red">Failed<strong>';
            else if($orders->status == '4')
               return '<strong style="color:red">Cancelled<strong>';
            else if($orders->status == '5')
               return '<strong style="color:orange">On the way<strong> ';
            else if($orders->status == '6')
               return '<strong style="color:purple">Accepted<strong>';
            else if($orders->status == '7')
               return '<strong style="color:red">Rejected<strong>';
         }) 
         ->addColumn('action',function($orders){
            if($orders->payment_status == '2'){
               return '
                  <i class="fa fa-eye ikn"  data-toggle="tooltip" data-placement="top" title="Order Details" onclick="orderDetails(event,'.$orders->id.')"></i>
                  <i class="fa fa-money ikn"  data-toggle="tooltip" data-placement="top" title="Transaction Details" onclick="transactionDetails(event,'.$orders->id.')"></i>
                  <a href ="'.url('admin/booking/invoice/').'/'.$orders->id.'" ><i class="fa fa-download ikn"  data-toggle="tooltip" data-placement="top" title="Invoice"></i></a>'; 
            }else{
               return ' 
                  <i class="fa fa-eye ikn"  data-toggle="tooltip" data-placement="top" title="Order Details" onclick="orderDetails(event,'.$orders->id.')"></i>
                  <i class="fa fa-money ikn"  data-toggle="tooltip" data-placement="top" title="Transaction Details" onclick="transactionDetails(event,'.$orders->id.')"></i>'; 
            }
         })
         ->addColumn('booking_date',function($orders){
            return (isset($orders->serviceProvider[0]) && !empty($orders->serviceProvider[0])) ?
               $orders->serviceProvider[0]->booking_date ?? '--' : '--' ;
         
         })
         ->addColumn('booking_time',function($orders){
            return (isset($orders->serviceProvider[0]) && !empty($orders->serviceProvider[0])) ?
               $orders->serviceProvider[0]->booking_time ?? '--' : '--' ;
         
         })
       //   ->escapeColumns([])
       // ->addIndexColumn()
         ->make(true);   
   }

   public function transaction()
   { 
      $orders = $this->transaction->join('orders as ord',function($q){
         $q->on('ord.id','=','transactions.order_id')
            ->where('ord.payment_status','<>','0');
      })->orderBy('transactions.id','desc')->groupBy('transactions.service_provider_id')
      ->select('transactions.status as trnsStatus','ord.*','transactions.*','transactions.id as trnsID','ord.id as orderID')
      ->get(); //by kajal
      return view('admin.booking.transaction',compact('orders'));
   }

   public function listTransaction(Request $request)
   { 
     
      if($request->has('filter_date') && !empty($request->filter_date)){

         $range = explode('-', $request->filter_date);
         // $start_date = new DateTime();
         // $start_date->setTimestamp($dbResult->db_timestamp);
         $endDate = Carbon::parse($range[1])->format('Y-m-d')." 23:59:59";
  
         $startDate  = Carbon::parse($range[0])->format('Y-m-d')." 00:00:00";

      }else{
         $endDate   = Carbon::now()->format('Y-m-d')." 23:59:59";
         
         $startDate = Carbon::now()->subDays(30)->format('Y-m-d')." 00:00:00";
      } 
     
      if($request->settle_status == '1')
         $settleStatus = [0,1];
      elseif($request->settle_status == '2')
         $settleStatus = [1];
      elseif($request->settle_status == '3')
         $settleStatus = [0];
      else
         $settleStatus = [0,1];
      if($request->service_provider_id){
         $service_provider_id = $request->service_provider_id;
      }else{
         $service_provider_id = '';
      }
     
      $order = $this->transaction->join('orders as ord',function($q){
            $q->on('ord.id','=','transactions.order_id')
               ->where('ord.payment_status','<>','0');
      })
      ->orderBy('transactions.id','desc')
      ->whereRaw("transactions.created_at BETWEEN '$startDate' AND  '$endDate'")
      ->whereIn('transactions.is_settlement',$settleStatus);
        
      if($request->has('service_provider_id') && !empty($request->service_provider_id)){

         $order->where('transactions.service_provider_id',$service_provider_id);
      }

      $order->select('transactions.status as trnsStatus','ord.*','transactions.*','transactions.id as trnsID','ord.id as orderID');
      $orders =  $order->get(); 
   

      // $orders = $this->transaction->join('orders as ord',function($q){
      //    $q->on('ord.id','=','transactions.order_id')
      //       ->where('ord.payment_status','<>','0');
      // })->orderBy('transactions.id','desc')
      // ->whereRaw("transactions.created_at BETWEEN '$startDate' AND  '$endDate'")
      // ->whereIn('transactions.is_settlement',$settleStatus)
      // ->select('transactions.status as trnsStatus','ord.*','transactions.*','transactions.id as trnsID','ord.id as orderID')
      // ->get(); 
   
      return Datatables::of($orders)
         ->addColumn('checkbox', function ($users) {
            return '<input type="checkbox" id="'.$users['id'].'" name="someCheckbox" class="userId" />';
         })
         ->addColumn('sp_name',function($orders){
               if(!empty($orders->service_provider_id))
                  return \App\User::find($orders->service_provider_id)->full_name_en;
               else
                  return '--';
         })
         ->addColumn('order_id',function($orders){
               return $orders->order_number;
         })
         ->addColumn('payment_id',function($orders){
            if(!empty($orders->payment_id)){
               return $orders->payment_id;
            }else{
               return '--';
            }
            
         })
         ->addColumn('payment_token',function($orders){
            if(!empty($orders->payment_token)){
               return $orders->payment_token;
            }else{
               return '--';
            }
         })
         ->addColumn('to_be_paid',function($orders){
            if($orders->is_settlement == 1 && $orders->trnsStatus == '1'){
               return '0' ;
            }elseif($orders->is_settlement == 0 && $orders->trnsStatus=='1'){
               return $orders->service_amount-$orders->commission;
            }else{
               return '--';
            }
         })
         ->addColumn('settlement_amount',function($orders){
          
            if($orders->is_settlement == 1 && $orders->trnsStatus == '1'){
               return $orders->service_amount-$orders->commission ;
            }elseif($orders->is_settlement == 0 && $orders->trnsStatus=='1'){
               return '0';
            }else{
               return '--';
            }
          })
         ->addColumn('status',function($orders){
            if($orders->trnsStatus == '0'){
               return 'Pending';
            }elseif($orders->trnsStatus == '1'){
               return 'Success';
            }elseif($orders->trnsStatus == '2'){
               return 'Fail';
            }elseif($orders->trnsStatus == '3'){
               return 'Cancelled';
            }else{
               return '--';
            }
 
         })
         ->addColumn('is_settlement',function($orders){
            if($orders->is_settlement == 1 && $orders->trnsStatus == '1'){
               return '<div class="badge badge-success badge-pill">Settled</div>';
            }elseif($orders->is_settlement == 0 && $orders->trnsStatus=='1'){
               return '<div class="badge badge-danger badge-pill">Unsettled</div>';
            }else{
               return '--';
            }
         })
         ->addColumn('user_applicable_fee',function($orders){
            return $orders->user_applicable_fee;
         })
         ->addColumn('action',function($orders){

            if($orders->is_settlement == 1 && $orders->trnsStatus == '1'){
               return '
               <i class="fa fa-eye ikn"  data-toggle="tooltip" data-placement="top" title="Transaction Details" onclick="transactionDetails(event,'.$orders->orderID.')"></i>'; 
            }elseif($orders->is_settlement == 0 && $orders->trnsStatus=='1' && $orders->order->status == '2'){
               return '
               <i class="fa fa-money ikn" data-toggle="tooltip" data-placement="top" title="Settlement" onclick="settlement(event,'.$orders->orderID.')"></i>
               <i class="fa fa-eye ikn"  data-toggle="tooltip" data-placement="top" title="Transaction Details" onclick="transactionDetails(event,'.$orders->orderID.')"></i>'; 
            }else{
               return '
               <i class="fa fa-eye ikn"  data-toggle="tooltip" data-placement="top" title="Transaction Details" onclick="transactionDetails(event,'.$orders->orderID.')"></i>'; 
            }
            
         })
         ->make(true);  
   }

   public function getDayId($name)
   {
      switch($name) {

         case "Sunday" :
               $dayId = 1;
               break;

         case "Monday" :
               $dayId = 2;
               break;

         case "Tuesday" :
               $dayId = 3;
               break;

         case "Wednesday" :
               $dayId = 4;
               break;

         case "Thursday" :
               $dayId = 5;
               break;

         case "Friday" :
               $dayId = 6;
               break;

         case "Saturday" :
               $dayId = 7;
               break;

         default :
               $dayId = 0 ;
      }

      return $dayId;
   }

   public function technicianList(Request $request)
   {
      $orders = $this->orders->with('serviceProvider')->where('id',$request->booking_id)->first();
       
      if(!empty($orders->serviceProvider[0])){

         $bookingTime     =  $orders->serviceProvider[0]['booking_time'];
         $serviceProvider =  $orders->serviceProvider[0]['service_provider_id'];
         $bookingEndTime  =  $orders->serviceProvider[0]['booking_end_time'];
         $bookingDate     =  $orders->serviceProvider[0]['booking_date'];

         $d    = new \DateTime($bookingDate);

         $dayId = $this->getDayId($d->format('l'));

         $technician = \App\User::where('is_active',1)->where('is_technician','1');
 
         $techicians['list'] = $technician->where('created_by', $serviceProvider)->whereHas('technicaintimeslots',function($query) use ($dayId, $bookingTime,$bookingEndTime){
            $query->where('day_id','=',$dayId)
               ->whereTime('start_time','<=',$bookingTime.':59')
               ->whereTime('end_time','>',$bookingEndTime.' 00')
               ->whereNotExists(function($qu) use ($bookingEndTime,$dayId,$bookingTime){
               $qu->selectRaw(1)
                  ->from('technician_time_slot')
                  ->whereRaw('technician_time_slot.day_id="'.$dayId.'"')
                  ->whereRaw('technician_time_slot.is_active = 1')
                  ->whereRaw('technician_time_slot.break_from >= "'.$bookingTime.':59" AND technician_time_slot.break_from < "'.$bookingEndTime.':59"')
                  ->whereRaw('technician_time_slot.break_to <= "'.$bookingEndTime.':59"');
               });
         })
         ->whereDoesntHave('bookedTechnican',function($query) use ($bookingDate,$bookingTime,$bookingEndTime){
            $query->where('booking_date','=',$bookingDate)
               ->where(function ($q) use ($bookingTime) {
                  $q->where('booking_time','<=',$bookingTime.':59')
                  ->where('booking_end_time','>=',$bookingTime.':59');
               })
               ->orwhere(function ($q) use ($bookingEndTime) {
                  $q->where('booking_time','<=',$bookingEndTime)
                     ->where('booking_end_time','>=',$bookingEndTime);
               })
               ->where('status','1');
         })
         ->select('id','full_name_en')
         ->get();

         $techicians['booked_technician'] = $this->orders->join('booked_technicians',function($query) use ($request){
            $query->on('orders.id','=','booked_technicians.order_id')
            ->where('order_id',$request->booking_id)
            ->join('users',function($join){
               $join->on('users.id','=','technician_id');
            });
         })->where('orders.id',$request->booking_id)
            ->select('users.id as id','full_name_en')
            ->get();


         $techicians['order_id'] = $orders->id;
      
      }else{

         $techicians['list'] = [];
         
         $techicians['booked_technician'] = $this->orders->join('booked_technicians',function($query) use ($request){
            $query->on('orders.id','=','booked_technicians.order_id')
            ->where('order_id',$request->booking_id)
            ->join('users',function($join){
               $join->on('users.id','=','technician_id');
            });
         })->where('orders.id',$request->booking_id)
            ->select('users.id as id','full_name_en')
            ->get();


         $techicians['order_id'] = $orders->id;
         
      }


      return $techicians;
   }

   public function technicianUpdate(Request $request)
   {
      $order = $this->orders->where('id',$request->order_id)->first();

      if($order->status == '2'){
         return response()->json(['status' => 'Error','message'=> 'Booking is completed..you cant assign any technician'],422);
      }elseif($order->status == '3' || $order->status == '4' || $order->status == '7'){
         return response()->json(['status' => 'Error','message'=> 'Booking is denied..you cant assign any technician'],422);
      }elseif($order->status == '1' || $order->status == '6' || $order->status == '5'){
         return response()->json(['status' => 'Error','message'=> 'Booking is In-progress..you cant assign any technician'],422);
      }

      $bookedTechnician = \App\BookedTechnician::where('order_id',$request->order_id)->update([
         'technician_id' => $request->technician_id
      ]);

      //send push notification
      $user = $this->user->where('id',$request->technician_id)->first();

      foreach($user->deviceInfo as $key){
            
         if(!empty($key->device_token)){

             if($order->user->is_language=='ar'){
                 $title     = 'تم استلام الحجز الجديد';
                 $subject   = 'لقد استلمت الحجز الجديد. يرجى التحقق من تفاصيل الحجز.';
             }else{
                 $title   = 'New Booking Received';
                 $subject = 'You have received a new booking. Please check booking details.';
             }

             $this->sendNotification($title,$subject,$key->device_token,'booking_list',$order->id,'2');
     
         }

      }

      return response()->json(['message' => 'Successfully assigned']);
   }
 
   public function orderDetails(Request $request)
   {
      $orders = $this->orders->where('id',$request->order_id)->select([
         "orders.*",
      DB::raw("(SELECT full_name_en FROM users WHERE orders.user_id = users.id) as username"),
      DB::raw("(SELECT mobile_number FROM users WHERE orders.user_id = users.id) as mobile"),
      DB::raw("(SELECT full_name_en FROM users WHERE users.id = orders.service_provider_id) as service_provider_name"),
      DB::raw("(SELECT us.full_name_en FROM booked_technicians join users as us ON us.id = booked_technicians.technician_id WHERE booked_technicians.order_id = orders.id) as technician"),
      DB::raw("(SELECT ser.name_en FROM suborders join service as ser ON ser.id = suborders.service_id WHERE suborders.order_id = orders.id) as service"),
      DB::raw("(SELECT ser.category_id FROM suborders join service as ser ON ser.id = suborders.service_id WHERE suborders.order_id = orders.id) as catgeory_id")
      ]) 
      ->with(['orderStatus','subOrder','userAddress','serviceProvider','coupon','extraAddonOrder.extraAddonOrderPaymentHistoryDeatils','extraAddonOrderPaymentHistory','transaction'
      ,'extraAddonOrdeTotal'=> function($query){
         $query->select(DB::raw('sum(amount)'));
      }])
      ->orderBy('id','desc')
      ->get();
      // dd($orders->toArray());

      // $getTotalOrder = SubOrderExtraAddonPaymentHistory::where('order_id',$request->order_id)->get();
      // if($getTotalOrder){
      //     $orderAmount =0;
      //     foreach($getTotalOrder as $order){
      //             $orderAmount += $order->amount;
      //     }
      //     $totalAmount = $orderAmount;
      // }
      //dd($orders->toArray());
      $data['list'] = $orders;
    //  $data['amount'] = $totalAmount;
      return $data; 
   }

   public function settlement(Request $request)
   {
      $user  = Auth::user();

      if($user->type == '2'){
  
        $roleUserPermission = \App\Models\RolePermission::query();
  
        $roleUserPermission =  $roleUserPermission->where([
          'menu_id' => 5,
          'role_id' => $user->role[0]->role_id
        ])->first();
  
        if($roleUserPermission->is_write == 0){
  
          return response()->json(['message' => 'Permission Denied','status' => 0]);
        }
      
      }

      $transaction = $this->transaction->where('id',$request->transaction_id)->first();
      $transaction->is_settlement = 1;
      $transaction->paid_amount   = $transaction->cash_payable;
      $transaction->save();

      return response()->json(['message' => 'Settle successfully done','status' => 'Success']);
   }

   public function settlementAllTransaction(Request $request)
   {
      $user  = Auth::user();

      if($user->type == '2'){
  
        $roleUserPermission = \App\Models\RolePermission::query();
  
        $roleUserPermission =  $roleUserPermission->where([
          'menu_id' => 5,
          'role_id' => $user->role[0]->role_id
        ])->first();
  
        if($roleUserPermission->is_write == 0){
  
          return response()->json(['message' => 'Permission Denied','status' => 0]);
        }
      
      }

      $transactions = $this->transaction->whereHas('order',function($query){
         $query->where('status','2');
      })->whereIn('id',$request->id)->where('is_settlement',0)->get();

      if(!empty($transactions) && $transactions->count() > 0){
        
         foreach($transactions as $transaction){
            $transaction->is_settlement = 1;
            $transaction->paid_amount   = $transaction->cash_payable;
            $transaction->save();
         }

         return response()->json(['message' => 'Settle successfully done','status' => 'Success']);

      }else{

         return response()->json(['message'=>'All transaction already settled'],422);

      }
     
   }

   public function settlementDetails(Request $request)
   {
      
      $transaction['list'] = $this->transaction->where('order_id',$request->transaction_id)->get();
      return $transaction;
   }

   public function transactionDetails(Request $request)
   {
      
      $orders = $this->transaction->with('order')->where('order_id',$request->transaction_id)
      ->orderBy('transactions.id','desc')
      ->join('orders as od',function($join){
         $join->on('od.id','=','transactions.order_id');
      })
      ->select([
         "transactions.*",
         DB::raw("(SELECT full_name_en FROM users WHERE od.user_id = users.id) as username"),
         DB::raw("(SELECT full_name_en FROM users WHERE users.id = od.service_provider_id) as service_provider_name"),
         DB::raw("(SELECT us.full_name_en FROM booked_technicians join users as us ON us.id = booked_technicians.technician_id WHERE booked_technicians.order_id = od.id) as technician"),
         DB::raw("(SELECT ser.name_en FROM suborders join service as ser ON ser.id = suborders.service_id WHERE suborders.order_id = od.id) as service"),
         DB::raw("(SELECT ser.category_id FROM suborders join service as ser ON ser.id = suborders.service_id WHERE suborders.order_id = od.id) as catgeory_id")
      ]) 
      ->get();


      $data['list'] = $orders;
      return $data;
   }

   public function recentBooking(Request $request)
   {
      $recentBooking['list'] = $orders = $this->orders->where('payment_status','<>','0')->select([
         "orders.*",
      DB::raw("(SELECT full_name_en FROM users WHERE orders.user_id = users.id) as username"),
      DB::raw("(SELECT full_name_en FROM users WHERE users.id = orders.service_provider_id) as service_provider"),
      DB::raw("(SELECT us.full_name_en FROM booked_technicians join users as us ON us.id = booked_technicians.technician_id WHERE booked_technicians.order_id = orders.id) as technician"),
      DB::raw("(SELECT ser.name_en FROM suborders join service as ser ON ser.id = suborders.service_id WHERE suborders.order_id = orders.id) as service"),
      DB::raw("(SELECT name_en as category_name FROM categories as cat WHERE cat.id = (SELECT ser.category_id FROM suborders join service as ser ON ser.id = suborders.service_id WHERE suborders.order_id = orders.id)) as catgeory_id")
      ])
      ->orderBy('id','desc')
      ->limit(20)
      ->get();

      return $recentBooking;
   }

   public function exportTransaction(Request $request)
   {
       Excel::store(new TransactionReport($request->all()), "public/transaction_reports.xlsx");
       return '1';
   }  



}
