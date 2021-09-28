<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Api;
use Validator;
use App\User;
use App\Models\Orders;
use App\Models\Suborders;
use App\Models\Serviceaddons;
use App\Http\Resources\TechnicianBookingListResource;
use App\Http\Resources\BookingServiceAddonsListResource;
use App\UserReport;
use App\UserReview;
use App\SubOrderExtraAddon;
use App\SubOrderExtraAddonPaymentHistory;
use App\Traits\PushNotifications;
use DB;
use App\Models\Ratings;
use App\Events\SendOrderNotification;
use App\OrderTracker ;
use App\Jobs\UserReportJob;




class TechnicianController extends Controller
{
    use PushNotifications;

    public function __construct(
        User                              $user,
        Orders                            $orders,
        Suborders                         $subOrder,
        Serviceaddons                     $serviceAddons,
        UserReport                        $userReport,
        UserReview                        $userReview,
        SubOrderExtraAddon                $subOrderExtraAddon,
        SubOrderExtraAddonPaymentHistory  $subOrderExtraAddonPaymentHistory,
        Ratings                           $ratings,
        OrderTracker                      $orderTracker

    )
    {
       $this->user                             = $user;
       $this->orders                           = $orders;
       $this->subOrder                         = $subOrder;
       $this->serviceAddons                    = $serviceAddons;
       $this->userReport                       = $userReport;
       $this->userReview                       = $userReview;
       $this->subOrderExtraAddon               = $subOrderExtraAddon;
       $this->subOrderExtraAddonPaymentHistory = $subOrderExtraAddonPaymentHistory;
       $this->ratings                          = $ratings;
       $this->orderTracker                     = $orderTracker;
    }

    public function serviceOnOff(Request $request)
    {
        $validator= Validator::make($request->all(),[
            'user_id'    => 'required|exists:users,id',
            'status'     => 'required|in:0,1'
        ]);

        if($validator->fails()){
            return response()->json(Api::apiValidationResponse($validator->errors()->first()),422);
        }

        $user = $this->user->where('id',$request->user_id)->where('is_service_active',$request->status);

        if($user->exists()){

            return response()->json(Api::apiErrorResponse('You are already performed this action'),422);
        
        }else{

          $technician = $this->user->where('id',$request->user_id)->first();
          $technician->is_service_active = $request->status;
          $technician->save();

          return response()->json(Api::apiSuccessResponse('Your status successfully changed'));

        }

    }  

    public function bookingList(Request $request)
    {

        $validator= Validator::make($request->all(),[
            'user_id'          => 'required|exists:users,id',
            'bookingType'      => 'required|in:1,2'
        ]);

        if($validator->fails()){
            return response()->json(Api::apiValidationResponse($validator->errors()->first()),422);
        }

        if($request->bookingType == 2){

            $booking = $this->orders
                ->with(['serviceProvider','orderStatus','subOrder','extraAddonOrder.extraAddonOrderPaymentHistoryDeatils'])
                ->whereHas('serviceProvider',function($query) use ($request){
                    $query->where('technician_id',$request->user_id)
                          ->where('status','<>',0);
                })
                ->whereHas('subOrder')
                ->whereIn('status',['2','3','4','7'])
                ->where('payment_status','2')
                ->orderBy('orders.id','desc')
                ->get();

        }else{

            $booking = $this->orders
                ->with(['serviceProvider','orderStatus','subOrder','extraAddonOrder.extraAddonOrderPaymentHistoryDeatils'])
                ->whereHas('serviceProvider',function($query) use ($request){
                    $query->where('technician_id',$request->user_id)
                          ->where('status','<>',0);
                })
                ->whereHas('subOrder')
                ->whereIn('status',['0','1','5','6'])
                ->where('payment_status','2')
                ->orderBy('orders.id','desc')
                ->get();
        }       
        return response()->json(Api::apiSuccessResponse('Booking list fetch successfully',TechnicianBookingListResource::collection($booking)),200);

    }

    public function serviceAddonList(Request $request)
    {
        $validator= Validator::make($request->all(),[
            'order_id'     => 'required|exists:orders,id'
        ]);

        if($validator->fails()){
            return response()->json(Api::apiValidationResponse($validator->errors()->first()),422);
        }

        $orderService = $this->subOrder->where('order_id',$request->order_id)->first();
        
        $serviceAddons = $this->serviceAddons
                // ->whereDoesntHave('subOrderAddon',function($k) use ($orderService){
                //    $k->where('suborder_id',$orderService->id);
                // })
                // ->whereDoesntHave('subOrderExtraAddon',function($q) use ($request){
                //     $q->where('order_id',$request->order_id);
                // })
                ->where('service_id',$orderService->service_id)
                ->get();

        return response()->json(Api::apiSuccessResponse('Service addons list fetch successfully',BookingServiceAddonsListResource::collection($serviceAddons)));
     
    }

    public function bookingStatusMarking(Request $request)
    {
        $validator= Validator::make($request->all(),[
            'order_id'     => 'required|exists:orders,id',
            'status'       => 'required|in:2,3,4' //0 : Pending, 1 : Accepted, 2: On the way, 3 : In Progress, 4 : Completed
        ]);

        if($validator->fails()){
            return response()->json(Api::apiValidationResponse($validator->errors()->first()),422);
        }

        $order = $this->orders->where('id',$request->order_id)->first();

        $isExits = $order->orderStatus()->where([
            'order_id'  => $request->order_id,
            'status'    => $request->status
        ])->exists();

        if($isExits)
           return response()->json(Api::apiErrorResponse('This action already performed'),422);

        $order->orderStatus()->updateOrCreate([
            'order_id'  => $request->order_id,
            'status'    => $request->status
        ],[
            'status'  => $request->status
        ]);

        $status       = 1;
        $statusText   = '';
        $statusTextAr = '';
        $spNameAr     = !empty($order->serviceProvider[0]) ? $order->serviceProvider[0]->serviceProvider->full_name_ar : '';
        $spName       = !empty($order->serviceProvider[0]) ? $order->serviceProvider[0]->serviceProvider->full_name_en : '';

        if($request->status == 2){

            $status         = 5;
            $statusText     = 'on the way';
            $statusTextAr   = 'علي الطريق';

        }elseif($request->status == 3){

            $status         = 1;
            $statusText     = 'started';
            $statusTextAr   = 'بداية';

        }elseif($request->status == 4){
           
            $status         = 2;
            $statusText     = 'completed';
            $statusTextAr   = 'منجز';
            $cashPayment    = 2;

            $order->serviceProvider[0]->status     = (string)$status;
            $order->serviceProvider[0]->save();

            if($order->extraAddonOrderPaymentHistory()->exists() && $order->extraAddonOrderPaymentHistory->payment_type == '1'){
        

                if(!empty($request->extraAddonPaymentStatus) && $request->extraAddonPaymentStatus == '1'){
                    $order->extraAddonOrderPaymentHistory->payment_status = '1';
                    $order->extraAddonOrderPaymentHistory->save();
                }
            }

        }

        $order->status     = (string)$status;
       
        $order->spend_time = !empty($request->totalTimeDurationSpend) ? $request->totalTimeDurationSpend : 0 ;
        $order->save();


        $orderNotification = $order->notification()->create([
            'title'       => 'Booking ID : '.$order->order_number .' is '.$statusText,
            'description' => 'This booking is '.$statusText.'. Please check booking details.'
        ]);

        $orderNotification->notificationStatus()->create([
            'user_id'  => !empty($order->serviceProvider[0]) ? $order->serviceProvider[0]->serviceProvider->id : null
        ]);

        event(new SendOrderNotification());

        $title_ar  = '';
        $body_ar   = '';
        $title_en  = '';
        $body_en   = '';

        foreach($order->user->deviceInfo as $key){
 
            if(!empty($key->device_token) && $order->user->is_notification == 1){
             
                if($order->user->is_language=='ar'){
                    $title     = $statusTextAr.' '.'الحجز';
                    $subject   = "لقد تم الحجز". $statusTextAr. " بواسطة ". $spNameAr;
                }else{
                    $title   = 'Booking '.$statusText;
                    $subject = 'Your booking has been '.$statusText.' by '.$spName;
                }

                $title_ar  = $statusTextAr.' '.'الحجز';
                $body_ar   = "لقد تم الحجز". $statusTextAr. " بواسطة ". $spNameAr;
                $title_en  = 'Booking '.$statusText;
                $body_en   = 'Your booking has been '.$statusText.' by '.$spName;

               // dd($title);
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


        return response()->json(Api::apiSuccessResponse('Booking status changed successfully',new TechnicianBookingListResource($order)));

    }

    public function userReport(Request $request)
    {
        $validator= Validator::make($request->all(),[
            'order_id'     => 'required|exists:orders,id',
            'user_id'      => 'required|exists:users,id',
            'is_marked'    => 'required|in:0,1' //0 : Un-marked, 1 : Marked
        ]);

        if($validator->fails()){
            return response()->json(Api::apiValidationResponse($validator->errors()->first()),422);
        }
        

        $userReport = $this->userReport->where([
            'order_id'   => $request->order_id,
            'user_id'    => $request->user_id,
            'is_marked'  => $request->is_marked
        ]);

        if($userReport->exists()){

            return response()->json(Api::apiErrorResponse('You are already marked this user for this booking'),422);

        }else{

            $report = $this->userReport->create([
                'order_id'   => $request->order_id,
                'user_id'    => $request->user_id,
                'is_marked'  => $request->is_marked
            ]);

            dispatch(new UserReportJob($report));

            return response()->json(Api::apiSuccessResponse('You successfully reported this user'));

        }
    }

    public function addonServicePaymentRequest(Request $request)
    {
        try{

            DB::beginTransaction();

            $validator= Validator::make($request->all(),[
                'order_id'     => 'required|exists:orders,id',
                'user_id'      => 'required|exists:users,id',
                'extraAddons'  => 'required',
                'paymentType'  => 'required|in:1,2' // 1 : Cash taken 2 : Online Payment 3:wallet
            ]);
    
            if($validator->fails()){
                return response()->json(Api::apiValidationResponse($validator->errors()->first()),422);
            }
          
            $order  = $this->orders->where('id',$request->order_id)->first();
            $amount = 0;
            
            

            /* I can't do payment status 1 in case of cash payment just because there 
            is issue comming on other apis like total amount issue 
            if($request->paymentType == 1){
               $payment_status = '1'; 
            }else{
                $payment_status = '0';
            }
            
            In case of multiple addOn request this function is not working properly 
               comment by - Kajal
            for($i = 0; $i < count($request->extraAddons);$i++){
                $amount +=$request->extraAddons[$i]['amount'];   
            }
            $getid = $order->extraAddonOrderPaymentHistory()->create([
                'amount'         => $amount,
                'payment_type'   => $request->paymentType,
                'payment_status' => $payment_status
            ]); */
         
            for($i = 0; $i < count($request->extraAddons);$i++){

                $getid = $order->extraAddonOrderPaymentHistory()->create([
                    'amount'         => $request->extraAddons[$i]['amount'],
                    'payment_type'   => $request->paymentType,
                    'payment_status' => '0'
                ]);

                $order->extraAddonOrder()->create([
                    'service_addon_id' => $request->extraAddons[$i]['service_addon_id'],
                    'amount'           => $request->extraAddons[$i]['amount'],
                    'sub_extra_payment_history_id'   => $getid->id
                ]);
            }
          
            if($request->paymentType == 1){
                $notification_type = '102';
            }else{
                $notification_type = '101';
            }
            $order->userNotification()->create([
                'user_id'           => $order->user_id,
                'order_id'          => $order->id,
                'title_en'          => 'Add-On Service Booking Received',
                'title_ar'          => 'تم إضافة إضافة عند الحجز',
                'body_en'           => 'You have received an add on booking. Please process to pay',
                'body_ar'           => 'لقد تلقيت إضافة على الحجز. يرجى معالجة الدفع',
                'notification_type' => $notification_type
            ]);
            
            foreach($order->user->deviceInfo as $key){
               
                if(!empty($key->device_token)){
    
                    if($order->user->is_language == 'ar'){
                        $title     = 'طلب خدمة الإضافات الإضافية';
                        $subject   = 'لقد تلقيت طلب جديد. يرجى الموافقة على الخدمات الإضافية الخاصة بك.';
                    }else{
                        $title   = 'Extra addons service request';
                        $subject = 'You have received a new request. Please approve your extra addons services.';
                    }
 
                    $this->sendNotification($title,$subject,$key->device_token,$notification_type,$order->id,'1');
                }
            }

            DB::commit();

            $data = [
                'isAddonAdded'      => true,
                'addonPaymentType'  => $request->paymentType
            ];

            return response()->json(Api::apiSuccessResponse('Please wait for payment approval',$data));
        
        }catch (\Exception $e) {
    
            DB::rollBack();
            return response()->json(Api::apiErrorResponse('something went wrong'),422);
        }

    }

  /*  public function addonServicePaymentRequest(Request $request)
    {

        try{

            DB::beginTransaction();

            $validator= Validator::make($request->all(),[
                'order_id'     => 'required|exists:orders,id',
                'user_id'      => 'required|exists:users,id',
                'extraAddons'  => 'required',
                'paymentType'  => 'required|in:1,2' // 1 : Cash taken 2 : Online Payment
            ]);
    
            if($validator->fails()){
                return response()->json(Api::apiValidationResponse($validator->errors()->first()),422);
            }
    
            $order  = $this->orders->where('id',$request->order_id)->first();
            $amount = 0;
    
            for($i = 0; $i < count($request->extraAddons) ; $i++){
    
                $order->extraAddonOrder()->create([
                    'service_addon_id' => $request->extraAddons[$i]['service_addon_id'],
                    'amount'           => $request->extraAddons[$i]['amount']
                ]);
    
                $amount +=$request->extraAddons[$i]['amount'];
            }
    
            $order->extraAddonOrderPaymentHistory()->create([
                'amount'         =>  $amount,
                'payment_type'   => $request->paymentType,
                'payment_status' => '0'
            ]);
            
     
            foreach($order->user->deviceInfo as $key){
               
                if(!empty($key->device_token)){
    
                    if($order->user->is_language=='ar'){
                        $title     = 'طلب خدمة اضافية addons';
                        $subject   = 'لقد تلقيت طلب جديد. يرجى الموافقة على الخدمات الإضافية الخاصة بك.';
                    }else{
                        $title   = 'Extra addons service request';
                        $subject = 'You have received a new request. Please approve your extra addons services.';
                    }

                    $this->sendNotification($title,$subject,$key->device_token,'extra_service_addons_request',$order->id,'1');
            
                }
            }

            DB::commit();

            $data = [
                'isAddonAdded'      => true,
                'addonPaymentType'  => $request->paymentType
            ];

            return response()->json(Api::apiSuccessResponse('Please wait for payment approval',$data));
        
        }catch (\Exception $e) {

            DB::rollBack();
            return response()->json(Api::apiErrorResponse('something went wrong'),422);

        }

    }*/

    public function userReview(Request $request)
    {
        $validator= Validator::make($request->all(),[
            'order_id'     => 'required|exists:orders,id',
            'user_id'      => 'required|exists:users,id',
            'description'  => 'required',
            'ratings'      => 'required'
        ]);

        if($validator->fails()){
            return response()->json(Api::apiValidationResponse($validator->errors()->first()),422);
        }

        $userReview = $this->ratings->where([
            'order_id'   => $request->order_id,
            'user_id'    => $request->user_id
        ]);

        if($userReview->exists()){

            return response()->json(Api::apiErrorResponse('You successfully reviewed this user'),422);

        }else{

            $order = $this->orders->where('id',$request->order_id)->first();


            $this->ratings->create([
                'order_id'              => $request->order_id,
                'user_id'               => $request->user_id,
                'reviews'               => $request->description,
                'rating'                => $request->ratings,
                'is_reviwed_technician' => 1,
                'service_provider'      => $order->serviceProvider[0]->service_provider_id
            ]);

            return response()->json(Api::apiSuccessResponse('Your are successfully review user for this booking'));

        }

    }

    public function success(Request $request)
    {
  
      try {

        $order = $this->orders->where('id',$request->Variable1)->first();
        $order->extraAddonOrderPaymentHistory->payment_status = '1';
        $order->save();

        //send notification to technician
        if(!empty($order->serviceProvider[0]->technician)){
            
            foreach($order->serviceProvider[0]->technician->deviceInfo as $key){
            
              if(!empty($key->device_token)){
  
                if($order->user->is_language=='ar'){
                    $title     = 'تم إجراء طلب دفع إضافي';
                    $subject   = 'تم إجراء طلب دفع إضافي';
                }else{
                    $title   = 'Extra addon payment request done';
                    $subject = 'Extra addons payment request done';
                }


                $this->sendNotification($title,$subject,$key->device_token,'booking_detail_timer_screen',$order->id,'2');
          
              }
              
            }

        }

        

        return response()->json(['status'=>1,'message' =>'success','walllet'=>'']);
     
      }catch(\Exception $e){

        return response()->json(['status'=>0,'message' =>'Something went wrong']);

      }
        
    }

    public function failure(Request $request)
    {
      return response()->json(['message' => 'Payment Fail...please try again....!']);
    }

    public function technicianTracking(Request $request)
    {
        $validator= Validator::make($request->all(),[
            'order_id'     => 'required|exists:orders,id',
            'longitude'    => 'required',
            'latitude'     => 'required'
        ]);

        if($validator->fails()){
            return response()->json(Api::apiValidationResponse($validator->errors()->first()),422);
        }

        $order = $this->orders->where('status','5');

        if($order->exists()){

           $this->orderTracker->where('status',0)->update(['status' => 1]);

           $this->orderTracker->create([
               'order_id'  => $request->order_id,
               'longitude' => $request->longitude,
               'latitude'  => $request->latitude
           ]); 

           return response()->json(Api::apiSuccessResponse('Your are successfully tracking technician current location'));
           
           
        }else{

            return response()->json(Api::apiErrorResponse('This booking status is not on the way'),422);
        }

    } 

    //remove add on service 
    public function removeAddOnService(Request $request)
    {
        try{
            $validator= Validator::make($request->all(),[
                'order_id'                      => 'required|exists:orders,id',
                'sub_extra_payment_history_id'  => 'required|exists:sub_order_extra_addon_payment_histories,id',
                'sub_extra_addon_id'            => 'required|exists:sub_order_extra_addons,id',
            ]);
            if($validator->fails()){
                return response()->json(Api::apiValidationResponse($validator->errors()->first()),422);
            }
          
            $extraAddOnPaymentOrderId = explode(',',$request->sub_extra_payment_history_id);
            $subExtraAddOnOrderId = explode(',',$request->sub_extra_addon_id);
       
            SubOrderExtraAddonPaymentHistory::whereIn('id',$extraAddOnPaymentOrderId)->delete();
         
            SubOrderExtraAddon::whereIn('id',$subExtraAddOnOrderId)->delete();
          
           

            $order  = $this->orders->where('id',$request->order_id)->first();

            $order->userNotification()->create([
                'user_id'           => $order->user_id,
                'order_id'          => $order->id,
                'title_en'          => 'Extra Add-On Service Booking Canceled',
                'title_ar'          => 'تم إلغاء حجز خدمة إضافية',
                'body_en'           => 'Your request is canceled for Extra Add-Ons service. Please check',
                'body_ar'           => 'تم إلغاء طلبك لخدمة الإضافات الإضافية. يرجى المراجعة',
                'notification_type' => '103'
            ]);
            foreach($order->user->deviceInfo as $key){
               
                if(!empty($key->device_token)){
    
                    if($order->user->is_language == 'ar'){
                        $title     = 'تم إلغاء حجز خدمة إضافية';
                        $subject   = 'تم إلغاء طلبك لخدمة الإضافات الإضافية';
                    }else{
                        $title   = 'Extra Add-On Service Booking Canceled';
                        $subject = 'Your request is canceled for extra addons service';
                    }
 
                    $this->sendNotification($title,$subject,$key->device_token,'103',$order->id,'1');
                }
            }
            return response()->json(Api::apiSuccessResponse('Successfully canceled the booking service'));

        }catch (\Exception $e) {
            DB::rollBack();
            return response()->json(Api::apiErrorResponse('something went wrong'),422);
        }

    }


}
