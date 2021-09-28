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



class BookingControllerBKP extends Controller
{
    use PushNotifications;

    public function __construct(
        BookedTechnician $bookedTechnician,
        Orders           $orders,
        Ratings          $ratings,
        OrderTracker     $orderTracker)
    {
        $this->bookedTechnician = $bookedTechnician;
        $this->orders           = $orders;
        $this->ratings          = $ratings;
        $this->orderTracker     = $orderTracker;
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
            'service_provider_id' => 'required' 
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

}
