<?php

namespace App\Http\Controllers\ServiceProvider;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Orders;
use Yajra\Datatables\Datatables;
use App\OrderStatus;
use DB;
use App\Transaction;
use App\SPTechnician;
use App\Traits\PushNotifications;
use Carbon\Carbon;

  
class BookingController extends Controller
{
   use PushNotifications;

   public function __construct(
      Orders          $orders,
      Transaction     $transaction,
      OrderStatus     $orderStatus)
   {
      $this->orders      = $orders;
      $this->transaction = $transaction;
      $this->orderStatus = $orderStatus;
   } 

   public function spBookingList(Request $request)
   { 
      return view('service-providers.booking');
   }

   public function spTransactionList()
   {
      return view('service-providers.transaction');
   } 

   public function list()
   {      
      $orders = $this->orders->where('payment_status','<>','0')->where('service_provider_id',\Auth::user()->id)->select([
         "orders.*",
      DB::raw("(SELECT full_name_en FROM users WHERE orders.user_id = users.id) as user_name_en"),
      DB::raw("(SELECT full_name_ar FROM users WHERE orders.user_id = users.id) as user_name_ar"),
      DB::raw("(SELECT full_name_en FROM users WHERE users.id = orders.service_provider_id) as service_provider"),
      DB::raw("(SELECT us.full_name_en FROM booked_technicians join users as us ON us.id = booked_technicians.technician_id WHERE booked_technicians.order_id = orders.id) as technician"),
      DB::raw("(SELECT ser.name_en FROM suborders join service as ser ON ser.id = suborders.service_id WHERE suborders.order_id = orders.id) as service"),
      DB::raw("(SELECT ser.category_id FROM suborders join service as ser ON ser.id = suborders.service_id WHERE suborders.order_id = orders.id) as catgeory_id")
      ])
      ->with('serviceProvider')
      ->orderBy('id','desc')->get();
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
            return !empty($orders->user_name_en) ? $orders->user_name_en : $orders->user_name_ar;
               
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
            $category = \App\Models\Category::find($orders->catgeory_id);

            if(!empty($category)){
               if($category->type == 2){
                  if($orders->payment_status == '2'){
                     return '
                        <i class="fa fa-eye ikn"  data-toggle="tooltip" data-placement="top" title="Order Details" onclick="orderDetails(event,'.$orders->id.')"></i>
                        <i class="fa fa-money ikn"  data-toggle="tooltip" data-placement="top" title="Transaction Details" onclick="transactionDetails(event,'.$orders->id.')"></i>
                        <a href ="'.url('/booking/invoice/').'/'.$orders->id.'" ><i class="fa fa-download ikn"  data-toggle="tooltip" data-placement="top" title="Invoice"></i></a>'; 
                  }else{
                     return '
                        <i class="fa fa-eye ikn"  data-toggle="tooltip" data-placement="top" title="Order Details" onclick="orderDetails(event,'.$orders->id.')"></i>
                        <i class="fa fa-money ikn"  data-toggle="tooltip" data-placement="top" title="Transaction Details" onclick="transactionDetails(event,'.$orders->id.')"></i>'; 
                  }
               }else{
                  if($orders->payment_status == '2'){
                     return '
                        <i class="fa fa-address-book-o ikn"  data-toggle="tooltip" data-placement="top" title="Re-assignment" onclick="assignServiceProvider(event,'.$orders->id.')"></i>
                        <i class="fa fa-eye ikn"  data-toggle="tooltip" data-placement="top" title="Order Details" onclick="orderDetails(event,'.$orders->id.')"></i>
                        <i class="fa fa-money ikn"  data-toggle="tooltip" data-placement="top" title="Transaction Details" onclick="transactionDetails(event,'.$orders->id.')"></i>
                        <a href ="'.url('/booking/invoice/').'/'.$orders->id.'" ><i class="fa fa-download ikn"  data-toggle="tooltip" data-placement="top" title="Invoice"></i></a>
                        <a href ="#" class="reschedule"><i class="fa fa-calendar ikn"  data-toggle="tooltip" data-placement="top" title="Reschedule Booking" onclick="rescheduleBooking(event,'.$orders->id.')"></i></a>'; 
                  }else{
                     return '
                        <i class="fa fa-address-book-o ikn"  data-toggle="tooltip" data-placement="top" title="Re-assignment" onclick="assignServiceProvider(event,'.$orders->id.')"></i>
                        <i class="fa fa-eye ikn"  data-toggle="tooltip" data-placement="top" title="Order Details" onclick="orderDetails(event,'.$orders->id.')"></i>
                        <i class="fa fa-money ikn"  data-toggle="tooltip" data-placement="top" title="Transaction Details" onclick="transactionDetails(event,'.$orders->id.')"></i>'; 
                  }
               }
                 
            }
              
            
           
         })->addColumn('booking_date',function($orders){
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

   public function listTransaction()
   {
 
      // $orders = $this->transaction->join('orders as ord',function($q){
      //    $q->on('ord.id','=','transactions.order_id')
      //       ->where('ord.payment_status','<>','0');
      // })
      // ->where('ord.service_provider_id',\Auth::user()->id)
      // ->orderBy('transactions.id','desc')
      // ->select('transactions.status as trnsStatus','ord.*','transactions.*','transactions.id as trnsID','ord.id as orderID')
      // ->get();

      $orders = $this->transaction->whereHas('order',function($q){
            $q->where('payment_status','<>','0');
      }) 
      ->where('service_provider_id',\Auth::user()->id)
      ->orderBy('id','desc');
      
   
      return Datatables::of($orders)
         ->addColumn('sp_name',function($orders){
               if(!empty($orders->service_provider_id))
                  return \App\User::find($orders->service_provider_id)->full_name_en;
               else
                  return '--';
         })
         ->addColumn('order_id',function($orders){
            return $orders->order->order_number;
         })
         ->addColumn('discount',function($orders){
               return $orders->order->coupon_amount;
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
         ->addColumn('status',function($orders){
            if($orders->status == '0'){
               return 'Pending';
            }elseif($orders->status == '1'){
               return 'Success';
            }elseif($orders->status == '2'){
               return 'Fail';
            }elseif($orders->status == '3'){
               return 'Cancelled';
            }else{
               return '--';
            }
         })
         ->addColumn('user_applicable_fee',function($orders){
            return $orders->user_applicable_fee;
         })
         ->addColumn('action',function($orders){
            return '
               <i class="fa fa-eye ikn"  data-toggle="tooltip" data-placement="top" title="Transaction Details" onclick="transactionDetails(event,'.$orders->order_id.')"></i>'; 
         })
         ->make(true);  
   } 

   public function orderDetails(Request $request)
   {
      $orders = $this->orders->where('id',$request->order_id)->select([
         "orders.*",
      DB::raw("(SELECT full_name_en FROM users WHERE orders.user_id = users.id) as username"),
      DB::raw("(SELECT mobile_number FROM users WHERE orders.user_id = users.id) as mobile"),DB::raw("(SELECT mobile_number FROM users WHERE orders.user_id = users.id) as mobile"),
      DB::raw("(SELECT full_name_en FROM users WHERE users.id = orders.service_provider_id) as service_provider_name"),
      DB::raw("(SELECT us.full_name_en FROM booked_technicians join users as us ON us.id = booked_technicians.technician_id WHERE booked_technicians.order_id = orders.id) as technician"),
      DB::raw("(SELECT ser.name_en FROM suborders join service as ser ON ser.id = suborders.service_id WHERE suborders.order_id = orders.id) as service"),
      DB::raw("(SELECT ser.category_id FROM suborders join service as ser ON ser.id = suborders.service_id WHERE suborders.order_id = orders.id) as catgeory_id")
      ]) 
      ->with(['orderStatus','subOrder','userAddress','serviceProvider','coupon','extraAddonOrder.extraAddonOrderPaymentHistoryDeatils','extraAddonOrderPaymentHistory','transaction'])
      ->orderBy('id','desc')
      ->get();
      // $getTotalOrder = SubOrderExtraAddonPaymentHistory::where('order_id',$request->order_id)->get();
     
      // if($getTotalOrder){
      //    $orderAmount =0;
      //    foreach($getTotalOrder as $orderd){
      //          $orderAmount += $orderd->amount;
      //    }
      //    $totalAmount = $orderAmount;
      // }
      $data['list'] = $orders;
     
      return $data;
   }

   public function changeStatus(Request $request)
   {
    
      $order = $this->orders->where('id',$request->order_id)->first();

      $orderStatus = $this->orderStatus->where([
         'status'   => $request->status,
         'order_id' => $request->order_id
      ])->exists();

      if(!$orderStatus){

         $isPossibleRejection = $this->orderStatus->where([
            'status'   => '5',
            'order_id' => $request->order_id
         ])->exists();

         if($isPossibleRejection)
            return response()->json(['message' => 'This booking already rejected..we cant perform any action on this booking','status' => 'Error']);  

         $isPossibleProcessing = $this->orderStatus->where([
               'status'   => '6',
               'order_id' => $request->order_id
         ])->exists();
   
         if($isPossibleProcessing)
            return response()->json(['message' => 'This booking is cancelled by user..we cant perform any action on this booking','status' => 'Error']);  
         
         if($request->status == 5){
  
            $isPossibleRejection = $this->orderStatus->where([
               'status'   => '1',
               'order_id' => $request->order_id
            ])->exists();

            if($isPossibleRejection)
               return response()->json(['message' => 'This booking cant rejected ','status' => 'Error']);  
         }

         $isBookingStatus = Carbon::parse($order->serviceProvider[0]->booking_date.' '.$order->serviceProvider[0]->booking_time)->lte(Carbon::now()->addHour(2)) ? true : false;
         
         if(!$isBookingStatus && $request->status == 2)
            return response()->json(['message' => 'This booking status can changed before 2 hours. ','status' => 'Error']);  


         $order->orderStatus()->updateOrCreate([
            'order_id'  => $request->order_id,
            'status'    => $request->status
         ],[
            'status'  => $request->status
         ]);
       

         $ordStatus    = '';
         $statusText   = '';
         $statusTextAr = '';
         $statusDescriptionAr = '';
         $spNameAr     = !empty($order->serviceProvider[0]) ? $order->serviceProvider[0]->serviceProvider->full_name_ar : '';
         $spName       = !empty($order->serviceProvider[0]) ? $order->serviceProvider[0]->serviceProvider->full_name_en : '';
        
         if($request->status == 1){  // Accepted
           
            $ordStatus      = 6;
            $statusText     = 'accepted';
            $statusDescriptionAr = "لقد تم تأكيد طلبك مع شركة {$spNameAr}";
            $statusTextAr   = 'قبلت';
         }elseif($request->status == 2){ //On the way
            $ordStatus      = 5;
            $statusText     = 'on the way';
            $statusTextAr   = 'علي الطريق';
            $statusDescriptionAr = "لقد تم ارسال الفني من قبل شركة {$spNameAr}";

         }elseif($request->status == 3){ // In Progress
            $ordStatus      = 1;
            $statusText     = 'started';
            $statusTextAr   = 'بداية';
            $statusDescriptionAr = "لقد بدأ الفني بتنفيذ الخدمة من قبل شركة {$spNameAr}";

         }elseif($request->status == 4){  //Completed
            $ordStatus      = 2;
            $statusText     = 'completed';
            $statusTextAr   = 'منجز';
            $statusDescriptionAr = "لقد تم الانتهاء من تنفيذ الخدمة المقدمة من شركة {$spNameAr}";

            $order->serviceProvider[0]->status = '2';
            $order->serviceProvider[0]->save();
         }elseif($request->status == 5){  //Rejected

            if($order->total_amount != 0 && $order->payment_type != ''){

               $closingAmount  = $order->user->amount + ($order->total_amount - $order->coupon_amount);
               $refundAmount   = $order->total_amount - $order->coupon_amount;

               $ordStatus      = 7;
               $statusText     = 'Rejected. Booking amount : '.$refundAmount.' refunded in your wallet';
               $statusTextAr   = 'مرفوض';
              $statusDescriptionAr = "لقد تم رفض طلبك من شركة {$spNameAr}";

            
               $order->revenue()->delete();
               $order->serviceProvider[0]->status = '4';
               $order->serviceProvider[0]->save();

               $order->user->wallet()->create([
                  'description'         => 'Booking amount refunded',
                  'description_ar'      => 'تم رد مبلغ الحجز',
                  'transaction_type'    => '3',
                  'closing_amount'      =>  $closingAmount,
                  'transaction_amount'  =>  $refundAmount,
               ]);

            

               $order->user->amount = $closingAmount;
               $order->user->save();
            }else{ // If payable amount is 0

               $ordStatus      = 7;
               $statusText     = 'Booking Rejected.';
               $statusTextAr   = 'مرفوض';
              $statusDescriptionAr = "لقد تم رفض طلبك من شركة {$spNameAr}";
               $order->revenue()->delete();
               $order->serviceProvider[0]->status = '4';
               $order->serviceProvider[0]->save();

            }

         }

         $order->status = (string)$ordStatus;
         $order->save();
         $title_ar= '';
         $title_en = '';
         $body_en = '';
         $body_ar = '';
       
         //Send push notification to user
         foreach($order->user->deviceInfo as $key){
 
            if(!empty($key->device_token) && $order->user->is_notification == 1){
                if($order->user->is_language=='ar'){
                    $title     = $statusTextAr.' '.'الحجز';
                    // $subject   = "لقد تم الحجز". $statusTextAr. " بواسطة ". $spNameAr;
                    $subject   = $statusDescriptionAr;
                }else{
                    $title   = 'Booking '.$statusText;
                    $subject = 'Your booking has been '.$statusText.' by '.$spName;
                }

                  $title_ar = $statusTextAr.' '.'الحجز';
                  $body_ar  = $statusDescriptionAr;
                  // $body_ar  = "لقد تم الحجز". $statusTextAr. " بواسطة ". $spNameAr;
                  $title_en = 'Booking '.$statusText;
                  $body_en  = 'Your booking has been '.$statusText.' by '.$spName;
             
                
                $this->sendNotification($title,$subject,$key->device_token,'booking_list',$order->id,'1');
        
            }
        }
       

      $order->userNotification()->create([
         'user_id'           => $order->user_id,
         'title_en'          => $title_en,
         'title_ar'          => $title_ar,
         'body_en'           => $body_en,
         'body_ar'           => $body_ar,
         'notification_type' => '1'
      ]);

         return response()->json(['message' => 'Booking status successfully changed','status'=>'Success']);
   
      }else{

         return response()->json(['message' => 'This action already performed','status' => 'Error']);  
      }
     
     

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
      $recentBooking['list'] = $orders = $this->orders->where('payment_status','<>','0')->where('service_provider_id',\Auth::user()->id)->select([
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

}
