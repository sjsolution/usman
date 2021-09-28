<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\BookedTechnician;
use App\Http\Resources\UserBookingListResource;
use Api;
use App\Models\Orders;
use App\Models\Ratings;
use Validator;
use Carbon\Carbon;
use App\Traits\PushNotifications;
use App\Events\SendOrderNotification;
use App\OrderTracker;
use App\Http\Resources\UserOrderTrackingResource;
use App\Models\Service;
use App\User;
use App\Revenue;
use DB;
use App\OrderStatus;



class BookingController extends Controller
{
    use PushNotifications;

    public function __construct(
        BookedTechnician $bookedTechnician,
        Orders           $orders,
        Ratings          $ratings,
        OrderTracker     $orderTracker,
        Revenue          $revenue,
        Service          $service,
        OrderStatus      $orderStatus,
        User       $user)
    {
        $this->bookedTechnician = $bookedTechnician;
        $this->orders           = $orders;
        $this->ratings          = $ratings;
        $this->orderTracker     = $orderTracker;
        $this->orderTracker     = $orderTracker;
        $this->revenue  = $revenue;
        $this->service  = $service;
        $this->orderStatus = $orderStatus;
        $this->user = $user;
    }

    public function userBookingList(Request $request)
    {
        $validator= Validator::make($request->all(),[
            'user_id'       => 'required|exists:users,id',
            'bookingType'   => 'required|in:1,2'
        ]);


        if($validator->fails()){
            return response()->json(Api::apiValidationResponse($validator->errors()->first()),422);
        }
      

        if($request->bookingType == 2){
            
            $booking = $this->orders->where('payment_status','<>','0')
            //    ->with(['serviceProvider','orderStatus','subOrder','rating','extraAddOnDetails.extraAddonOrderPaymentHistoryDeatils'])
            ->with(['serviceProvider','orderStatus','subOrder','rating','extraAddonOrder.extraAddonOrderPaymentHistoryDeatils'])
                ->whereIn('status',['2','3','4','7'])
                ->where('payment_status','2')
                ->orderBy('orders.id','desc')
                ->whereHas('subOrder')
                ->where('orders.user_id',$request->user_id)
                ->get();

     
        }else{
            
            $booking = $this->orders->where('payment_status','<>','0')->where('orders.user_id',$request->user_id)
                ->with(['serviceProvider','orderStatus','subOrder','rating','extraAddonOrder.extraAddonOrderPaymentHistoryDeatils'])
                ->whereIn('status',['0','1','5','6'])
                ->where('payment_status','2')  
                ->whereHas('subOrder')
                ->orderBy('id','desc')            
                ->get();

        };
        
        if($booking->count()){
            return response()->json(Api::apiSuccessResponse(__('message.booking_list'),UserBookingListResource::collection($booking)),200);
        }else{
            return response()->json(Api::apiErrorResponse(__('message.noRecordFound')),422);
        }
        
    }

    public function userBookingDetails(Request $request)
    {
        $validator= Validator::make($request->all(),[
            'order_id'       => 'required|exists:orders,id',
        ]);


        if($validator->fails()){
            return response()->json(Api::apiValidationResponse($validator->errors()->first()),422);
        }
      
        $booking = $this->orders->where('payment_status','<>','0')
            ->with(['serviceProvider','orderStatus','subOrder','rating'])
            ->where('payment_status','2')
            ->orderBy('orders.id','desc')
            ->where('orders.id',$request->order_id)
            ->get();


        if($booking->count()){
            return response()->json(Api::apiSuccessResponse(__('message.booking_list'),UserBookingListResource::collection($booking)),200);
        }else{
            return response()->json(Api::apiErrorResponse(__('message.noRecordFound')),422);
        }
        
    }

    public function rating(Request $request)
    {
        $validator= Validator::make($request->all(),[
            'user_id'             => 'required|exists:users,id',
            'order_id'            => 'required|exists:orders,id',
            'rating'              => 'required',
            'reviews'             => 'required',
            'service_provider_id' => 'required|exists:users,id' 
        ]);

        if($validator->fails()){
            return response()->json(Api::apiValidationResponse($validator->errors()->first()),422);
        }

        $isExists = $this->ratings->where('order_id',$request->order_id)->where('user_id',$request->user_id)->exists();

        if($isExists){

            return response()->json(Api::apiErrorResponse('You already rated this order'),422);
        
        }else{

            $this->ratings->create([
                'user_id'             => $request->user_id,
                'order_id'            => $request->order_id,
                'rating'              => $request->rating,
                'reviews'             => $request->reviews,
                'service_provider'    => $request->service_provider_id
            ]);

            return response()->json(Api::apiSuccessResponse(__('message.ratingOrder')),200);
    
        }
        

    }

    public function cancelBooking(Request $request)
    {
        $validator= Validator::make($request->all(),[
            'user_id'    => 'required|exists:users,id',
            'order_id'   => 'required|exists:orders,id'
        ]);

        if($validator->fails()){
            return response()->json(Api::apiValidationResponse($validator->errors()->first()),422);
        }

        $isExists = $this->orders->where('id',$request->order_id)
                    ->where('user_id',$request->user_id)
                    ->where('status','4')
                    ->exists();

        if($isExists){

            return response()->json(Api::apiErrorResponse('Your are already cancelled this order'),422);
 
        }else{
             
            $percent          = 0;
            $deductionAmount  = 0;
            $order = $this->orders->with('serviceProvider')->where('id',$request->order_id)
                ->where('user_id',$request->user_id)
                ->first();

            $bookingDate = Carbon::parse($order->serviceProvider[0]->booking_date.' '.$order->serviceProvider[0]->booking_time);

            if(!empty($order)){
                //24 hours before
                if($bookingDate->diffInHours(\Carbon\Carbon::now()) >= 24){
                    $percent = 100;
                }elseif($bookingDate->diffInHours(\Carbon\Carbon::now()) >= 6 && $bookingDate->diffInHours(\Carbon\Carbon::now()) < 24){
                    $percent = 50;
                }elseif($bookingDate->diffInHours(\Carbon\Carbon::now()) < 6){
                    $percent = 0;
                }

                $deductionAmount = ((($order->total_amount - $order->coupon_amount) * $percent) / 100); 

                //Refund Amount
                $order->revenue()->create([
                    'status' => '0',
                    'amount' =>  -$deductionAmount
                ]);

                //Release service provider        
                if(!empty($order->serviceProvider[0])){
                    $order->serviceProvider[0]->status = '3';
                    $order->serviceProvider[0]->save();
                } 

                //Refund user wallet
                $closingAmount  = $order->user->amount + $deductionAmount;
                $refundAmount   = $deductionAmount;

                $statusText     = 'Cancelled. Booking amount : '.$refundAmount.' refunded in your wallet';
                $statusTextAr   = 'مرفوض';
                $spName         = '';

                if($percent > 0){
                    
                    $order->user->wallet()->create([
                        'description'         => 'Booking amount refunded',
                        'description_ar'      => 'تم رد مبلغ الحجز',
                        'transaction_type'    => '3',
                        'closing_amount'      =>  $closingAmount,
                        'transaction_amount'  =>  $refundAmount,
                    ]);
                }
           
                

                $order->user->amount = $closingAmount;
                $order->user->save();
                //End of user refund


                $order->status = '4';
                $order->save(); 

                $order->orderStatus()->updateOrCreate([
                    'order_id'  => $order->id,
                    'status'    => '6'
                ],[
                    'status'  => '6'
                ]);

                $orderNotification = $order->notification()->create([
                    'title'       => 'Booking ID : '.$order->order_number .' is cancelled',
                    'description' => 'This booking is cancelled Please check booking details.'
                ]);
        
                $orderNotification->notificationStatus()->create([
                    'user_id'  => !empty($order->serviceProvider[0]) ? $order->serviceProvider[0]->serviceProvider->id : null
                ]);
        
                event(new SendOrderNotification());

                $title_ar  = '';
                $body_ar   = '';
                $title_en  = '';
                $body_en   = '';
                
                //Send push notification to user
                foreach($order->user->deviceInfo as $key){
        
                    if(!empty($key->device_token)){
                    
                        if($order->user->is_language=='ar'){
                            $title     = $statusTextAr.' '.'الحجز';
                            $subject   = "لقد تم الحجز". $statusTextAr. " بواسطة ";
                        }else{
                            $title   = 'Booking '.$statusText;
                            $subject = 'Your booking has been '.$statusText;
                        }

                        $title_ar  = $statusTextAr.' '.'الحجز';
                        $body_ar   = "لقد تم الحجز". $statusTextAr. " بواسطة ";
                        $title_en  = 'Booking '.$statusText;
                        $body_en   = 'Your booking has been '.$statusText;

                        
                        $this->sendNotification($title,$subject,$key->device_token,'booking_list',$order->id,'1');
                
                    }
                }

                if($percent > 0){

                    $order->userNotification()->create([
                        'user_id'           => $order->user_id,
                        'title_en'          => $title_en,
                        'title_ar'          => $title_ar,
                        'body_en'           => $body_en,
                        'body_ar'           => $body_ar,
                        'notification_type' => '1'
                    ]);

                }
           



                return response()->json(Api::apiSuccessResponse(__('message.bookingCancel')),200);
            
            }else{

                return response()->json(Api::apiErrorResponse(__('message.somethingWentWrong')),422);

            }
       
        }

    }

    public function userBookingTracking(Request $request)
    {
        $validator= Validator::make($request->all(),[
            'order_id'   => 'required|exists:orders,id'
        ]);

        if($validator->fails()){
            return response()->json(Api::apiValidationResponse($validator->errors()->first()),422);
        }

        $orderTracking = $this->orders->where('id',$request->order_id);

        if($orderTracking->first()->status == '5'){

            return response()->json(Api::apiSuccessResponse('Order tracking details fetch successfully',UserOrderTrackingResource::collection($orderTracking->get())),200);
        
        }else{

            return response()->json(Api::apiErrorResponse('This booking is not on the way'),422);

        }
         
   
    }

    public function vendorRecentBookings(Request $request){
        $validator= Validator::make($request->all(),[
            'user_id'       => 'required',
        ]);

        if($validator->fails()){
            return response()->json(Api::apiValidationResponse($validator->errors()->first()),422);
        }

        $startDate      = Carbon::now()->format('Y-m-d')." 00:00:00";
        $endDate        = Carbon::now()->format('Y-m-d'). " 23:59:59";

        $todayOrder     = $this->orders->where('payment_status','2')->where('service_provider_id',$request->user_id)->whereBetween('created_at',[ $startDate,$endDate ])->get()->count();
        $totalOrder     = $this->orders->where('payment_status','2')->where('service_provider_id',$request->user_id)->get()->count();
    
        $totalRevenue   = $this->revenue->whereHas('orders', function($query) use ($request){
           $query->where('service_provider_id',$request->user_id)
              ->where('payment_status','2');
        })->sum('sp_amount');
    
        $todayRevenue   = $this->revenue->whereHas('orders', function($query) use ($startDate,$endDate,$request){
          $query->where('service_provider_id',$request->user_id)
            ->where('payment_status','2')
            ->whereBetween('created_at',[ $startDate,$endDate ]);
        })->sum('sp_amount');

    
        $totalCustomer = $this->orders->where('payment_status','2')->where('service_provider_id',$request->user_id)->select('user_id', DB::raw('count(user_id) total_booking'))->groupBy('user_id')->get()->count();

        $todayCustomer = $this->orders->where('payment_status','2')->where('service_provider_id',$request->user_id)->select('user_id', DB::raw('count(user_id) total_booking'))->groupBy('user_id')->whereBetween('created_at',[ $startDate,$endDate ])->get()->count();

        $activeService = $this->service->where('is_active','1')->where('user_id',$request->user_id)->get()->count();

        $recentBooking['list'] = $orders = $this->orders->where('payment_status','<>','0')->where('service_provider_id',$request->user_id)->select([
            "orders.*",
            DB::raw("(SELECT full_name_en FROM users WHERE orders.user_id = users.id) as username"),
            DB::raw("(SELECT full_name_en FROM users WHERE users.id = orders.service_provider_id) as service_provider"),
            DB::raw("(SELECT us.full_name_en FROM booked_technicians join users as us ON us.id = booked_technicians.technician_id WHERE booked_technicians.order_id = orders.id) as technician"),
            DB::raw("(SELECT ser.name_en FROM suborders join service as ser ON ser.id = suborders.service_id WHERE suborders.order_id = orders.id) as service"),
            DB::raw("(SELECT name_en as category_name FROM categories as cat WHERE cat.id = (SELECT ser.category_id FROM suborders join service as ser ON ser.id = suborders.service_id WHERE suborders.order_id = orders.id)) as catgeory_id"),
            DB::raw("(SELECT image as category_image FROM categories as cat WHERE cat.id = (SELECT ser.category_id FROM suborders join service as ser ON ser.id = suborders.service_id WHERE suborders.order_id = orders.id)) as category_image")
        ])
        ->orderBy('id','desc')
        ->limit(20)
        ->get();

        $data['today_orders'] = $todayOrder;
        $data['total_orders'] = $totalOrder;
        $data['today_revenue'] = $todayRevenue;
        $data['total_revenue'] = $totalRevenue;
        $data['today_customers'] = $todayCustomer;
        $data['total_customers'] = $totalCustomer;
        $data['active_services'] = $activeService;
        $data['recent_bookings'] = $recentBooking['list'];


        return response()->json(Api::apiSuccessResponse(__('Statistics and recent bookings'),$data),200);
    }

    public function vendorOrderDetail(Request $request){
        $validator= Validator::make($request->all(),[
            'order_id'       => 'required',
        ]);

        if($validator->fails()){
            return response()->json(Api::apiValidationResponse($validator->errors()->first()),422);
        }

        $orders = $this->orders->where('id',$request->order_id)->select([
            "orders.*",
         DB::raw("(SELECT full_name_en FROM users WHERE orders.user_id = users.id) as username"),
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
        ->first();

        return response()->json(Api::apiSuccessResponse(__('Booking Details'),$orders),200);

    }

    public function vendorOrderStatusChange(Request $request){
        $validator= Validator::make($request->all(),[
            'order_id'       => 'required|numeric|integer|exists:orders,id',
            'status'       => 'required|numeric|integer|in:0,1,2,3,4,5',
        ]);

        if($validator->fails()){
            return response()->json(Api::apiValidationResponse($validator->errors()->first()),422);
        }

        // $orders = $this->orders->where('id',$request->order_id)->update([
        //     'status' => $request->status,
        // ]);

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
            $spNameAr     = !empty($order->serviceProvider[0]) ? $order->serviceProvider[0]->serviceProvider->full_name_ar : '';
            $spName       = !empty($order->serviceProvider[0]) ? $order->serviceProvider[0]->serviceProvider->full_name_en : '';
            
            if($request->status == 1){  // Accepted
                
                $ordStatus      = 6;
                $statusText     = 'accepted';
                $statusTextAr   = 'قبلت';
            }elseif($request->status == 2){ //On the way
                $ordStatus      = 5;
                $statusText     = 'on the way';
                $statusTextAr   = 'علي الطريق';
            }elseif($request->status == 3){ // In Progress
                $ordStatus      = 1;
                $statusText     = 'started';
                $statusTextAr   = 'بداية';
            }elseif($request->status == 4){  //Completed
                $ordStatus      = 2;
                $statusText     = 'completed';
                $statusTextAr   = 'منجز';
                $order->serviceProvider[0]->status = '2';
                $order->serviceProvider[0]->save();
            }elseif($request->status == 5){  //Rejected
    
                if($order->total_amount != 0 && $order->payment_type != ''){
    
                    $closingAmount  = $order->user->amount + ($order->total_amount - $order->coupon_amount);
                    $refundAmount   = $order->total_amount - $order->coupon_amount;
    
                    $ordStatus      = 7;
                    $statusText     = 'Rejected. Booking amount : '.$refundAmount.' refunded in your wallet';
                    $statusTextAr   = 'مرفوض';
                
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
                        $subject   = "لقد تم الحجز". $statusTextAr. " بواسطة ". $spNameAr;
                    }else{
                        $title   = 'Booking '.$statusText;
                        $subject = 'Your booking has been '.$statusText.' by '.$spName;
                    }
    
                        $title_ar = $statusTextAr.' '.'الحجز';
                        $body_ar  = "لقد تم الحجز". $statusTextAr. " بواسطة ". $spNameAr;
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

            return response()->json(Api::apiSuccessResponse(__('Booking status changed successfully')),200);
        }
        else{
            return response()->json(Api::apiSuccessResponse(__('This action already performed')),200);

        }
    }

    public function adminRecentBookings(Request $request){
        $validator= Validator::make($request->all(),[
            'user_id'       => 'required',
        ]);

        if($validator->fails()){
            return response()->json(Api::apiValidationResponse($validator->errors()->first()),422);
        }

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

        $recentBooking['list'] = $orders = $this->orders->where('payment_status','<>','0')->select([
            "orders.*",
         DB::raw("(SELECT full_name_en FROM users WHERE orders.user_id = users.id) as username"),
         DB::raw("(SELECT profile_pic FROM users WHERE orders.service_provider_id = users.id) as profile_pic"),
         DB::raw("(SELECT full_name_en FROM users WHERE users.id = orders.service_provider_id) as service_provider"),
         DB::raw("(SELECT us.full_name_en FROM booked_technicians join users as us ON us.id = booked_technicians.technician_id WHERE booked_technicians.order_id = orders.id) as technician"),
         DB::raw("(SELECT ser.name_en FROM suborders join service as ser ON ser.id = suborders.service_id WHERE suborders.order_id = orders.id) as service"),
         DB::raw("(SELECT name_en as category_name FROM categories as cat WHERE cat.id = (SELECT ser.category_id FROM suborders join service as ser ON ser.id = suborders.service_id WHERE suborders.order_id = orders.id)) as catgeory_id")
         ])
         ->orderBy('id','desc')
         ->limit(20)
         ->get();

        $data['today_orders'] = $todayOrders;
        $data['total_orders'] = $totalOrders;
        $data['today_revenue'] = $todayRevenue;
        $data['total_revenue'] = $totalRevenue;
        $data['today_customers'] = $todayUser;
        $data['total_customers'] = $totalUser;
        $data['active_services'] = $activeService;
        $data['recent_bookings'] = $recentBooking['list'];


        return response()->json(Api::apiSuccessResponse(__('Statistics and recent bookings'),$data),200);
    }

    public function adminOrderDetail(Request $request){
        $validator= Validator::make($request->all(),[
            'order_id'       => 'required',
        ]);

        if($validator->fails()){
            return response()->json(Api::apiValidationResponse($validator->errors()->first()),422);
        }

        $orders = $this->orders->where('id',$request->order_id)->select([
            "orders.*",
         DB::raw("(SELECT full_name_en FROM users WHERE orders.user_id = users.id) as username"),
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
        ->first();

        $activeService = $this->service->where('is_active','1')->where('user_id', $orders->service_provider_id)->get()->count();
        $totalOrders     = $this->orders->where('payment_status','2')->where('service_provider_id', $orders->service_provider_id)->get()->count();

        $data['active_services'] = $activeService;
        $data['total_orders'] = $activeService;
        $data['orders'] = $orders;

        return response()->json(Api::apiSuccessResponse(__('Booking Details'),$data),200);

    }

    public function adminOrderStatusChange(Request $request){
        $validator= Validator::make($request->all(),[
            'order_id'       => 'required|numeric|integer|exists:orders,id',
            'status'       => 'required|numeric|integer|in:0,1,2,3,4,5',
        ]);

        if($validator->fails()){
            return response()->json(Api::apiValidationResponse($validator->errors()->first()),422);
        }

        // $orders = $this->orders->where('id',$request->order_id)->update([
        //     'status' => $request->status,
        // ]);

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
            $spNameAr     = !empty($order->serviceProvider[0]) ? $order->serviceProvider[0]->serviceProvider->full_name_ar : '';
            $spName       = !empty($order->serviceProvider[0]) ? $order->serviceProvider[0]->serviceProvider->full_name_en : '';
            
            if($request->status == 1){  // Accepted
                
                $ordStatus      = 6;
                $statusText     = 'accepted';
                $statusTextAr   = 'قبلت';
            }elseif($request->status == 2){ //On the way
                $ordStatus      = 5;
                $statusText     = 'on the way';
                $statusTextAr   = 'علي الطريق';
            }elseif($request->status == 3){ // In Progress
                $ordStatus      = 1;
                $statusText     = 'started';
                $statusTextAr   = 'بداية';
            }elseif($request->status == 4){  //Completed
                $ordStatus      = 2;
                $statusText     = 'completed';
                $statusTextAr   = 'منجز';
                $order->serviceProvider[0]->status = '2';
                $order->serviceProvider[0]->save();
            }elseif($request->status == 5){  //Rejected
    
                if($order->total_amount != 0 && $order->payment_type != ''){
    
                    $closingAmount  = $order->user->amount + ($order->total_amount - $order->coupon_amount);
                    $refundAmount   = $order->total_amount - $order->coupon_amount;
    
                    $ordStatus      = 7;
                    $statusText     = 'Rejected. Booking amount : '.$refundAmount.' refunded in your wallet';
                    $statusTextAr   = 'مرفوض';
                
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
                        $subject   = "لقد تم الحجز". $statusTextAr. " بواسطة ". $spNameAr;
                    }else{
                        $title   = 'Booking '.$statusText;
                        $subject = 'Your booking has been '.$statusText.' by '.$spName;
                    }
    
                        $title_ar = $statusTextAr.' '.'الحجز';
                        $body_ar  = "لقد تم الحجز". $statusTextAr. " بواسطة ". $spNameAr;
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

            return response()->json(Api::apiSuccessResponse(__('Booking status changed successfully')),200);
        }
        else{
            return response()->json(Api::apiSuccessResponse(__('This action already performed')),200);

        }
    }

}
