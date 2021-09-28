<?php

namespace App\Http\Controllers\ServiceProvider;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Orders;
use App\OrderStatus;
use DB;
use App\Transaction;
use App\SPTechnician;
use App\Traits\PushNotifications;
use Carbon\Carbon;

class RescheduleBookingController extends Controller
{
    public function __construct(
        Orders          $orders,
        Transaction     $transaction,
        OrderStatus     $orderStatus)
     {
        $this->orders      = $orders;
        $this->transaction = $transaction;
        $this->orderStatus = $orderStatus;
     } 

     
    public function index(Request $request)
    {
        $orders = $this->orders->where('id',$request->order_id)->select([
            "orders.*",
         DB::raw("(SELECT full_name_en FROM users WHERE orders.user_id = users.id) as username"),
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

    public function rescheduleBooking(Request $request)
    {
        $orders = $this->orders->where('id',$request->order_id)->where('payment_status','2')->first();

        if(!empty($orders) && $orders->status == 0){

            // $technician = \App\User::where('is_active',1)->where('is_technician','1')->where('is_service_active',1);
            
            // if($technician->exists()){
          
            //   $techicians = $technician->where('created_by', $serviceProvider )
            //       ->whereHas('technicaintimeslots',function($query) use ($dayId, $bookingTime,$bookingEndTime){
            //         $query->where('day_id','=',$dayId)
            //           ->where('is_active',1)
            //           ->where('status',1)
            //           ->whereRaw('technician_time_slot.start_time <= "'.$bookingTime.'" AND technician_time_slot.end_time > "'.$bookingTime.'"')
            //           ->whereRaw('technician_time_slot.start_time <= "'.$bookingEndTime.'" AND technician_time_slot.end_time >= "'.$bookingEndTime.'"')
            //           ->whereNotExists(function($q) use ($bookingEndTime,$dayId,$bookingTime){
            //             $q->whereRaw('technician_time_slot.break_from <= "'.$bookingTime.'" AND technician_time_slot.break_to > "'.$bookingTime.'"')
            //               ->orwhereRaw('technician_time_slot.break_from < "'.$bookingEndTime.'" AND technician_time_slot.break_to > "'.$bookingEndTime.'"');
            //           })
            //           ->whereNotExists(function($q) use ($bookingEndTime,$dayId,$bookingTime){
            //             $q->whereRaw('technician_time_slot.break_from > "'.$bookingTime.'" AND technician_time_slot.break_to <= "'.$bookingEndTime.'"');
            //           });
            //       })
            //       ->whereDoesntHave('bookedTechnican',function($query) use ($bookingDate,$bookingTime,$bookingEndTime){
            //         $query->where('booking_date','=',$bookingDate)
            //             ->whereIn('status',['1'])
            //             ->whereExists(function($q) use ($bookingEndTime,$bookingTime){
            //               $q->whereRaw('booked_technicians.booking_time <= "'.$bookingTime.'" AND booked_technicians.booking_end_time > "'.$bookingTime.'"')
            //                 ->orwhereRaw('booked_technicians.booking_time < "'.$bookingEndTime.'" AND booked_technicians.booking_end_time > "'.$bookingEndTime.'"');
            //             });
            //             // ->whereExists(function($q) use ($bookingEndTime,$bookingTime){
            //             //   $q->whereRaw('booked_technicians.booking_time >= "'.$bookingTime.'" AND booked_technicians.booking_end_time <= "'.$bookingEndTime.'"');
            //             // });;
            //       });

            //       $serviceReq = \App\Models\Service::where('id',$sid)->first();

            //       if(!empty($serviceReq) && $serviceReq->category->type == 3){

            //         $techicians = $techicians->whereHas('serviceProviderForTechnician.userEmergencyTimeSlot',function($query) use ($bookingTime,$dayId) {
            //           $query->where('day_id','=',$dayId)
            //             ->whereTime('start_time','<=',$bookingTime.':59')
            //             ->whereTime('end_time','>',$bookingTime.' 00'); 
            //         });


            //       }

            //       $techicians = $techicians->with('bookedTechnican')->first();

                
              
            //   if(!empty($techicians)){
               
            //     $serviceProviderIds = \App\User::find($techicians->id)->created_by;

            //     $user->bookingTechnican()->create([
            //       'order_id'               => $order->id,
            //       'service_provider_id'    => $serviceProviderIds,
            //       'technician_id'          => $techicians->id,
            //       'booking_date'           => $bookingDate,
            //       'booking_time'           => $bookingTime,
            //       'booking_end_time'       => $bookingEndTime,
            //       'status'                 => (string)$isBooked
            //     ]);

            //     $order->service_provider_id = $serviceProviderIds;
            //     $order->save();

            //   }else{ 
             
            //     $servieProviderAsTech = \App\User::where('is_active',1)->where('is_technician','1')->where('id',$serviceProvider);
                
            //     if($servieProviderAsTech->exists()){
                
            //       $servieProviderAsTechcian = $servieProviderAsTech
            //       ->whereHas('userTimeSlot',function($query) use ($dayId, $bookingTime,$bookingEndTime){
            //         $query->where('day_id','=',$dayId)
            //           ->where('is_active',1)
            //           ->where('status',1)
            //           ->whereRaw('sp_time_slot.start_time <= "'.$bookingTime.'" AND sp_time_slot.end_time > "'.$bookingTime.'"')
            //           ->whereRaw('sp_time_slot.start_time <= "'.$bookingEndTime.'" AND sp_time_slot.end_time >= "'.$bookingEndTime.'"')
            //           ->whereNotExists(function($q) use ($bookingEndTime,$dayId,$bookingTime){
            //             $q->whereRaw('sp_time_slot.break_from < "'.$bookingTime.'" AND sp_time_slot.break_to > "'.$bookingTime.'"')
            //               ->orwhereRaw('sp_time_slot.break_from < "'.$bookingEndTime.'" AND sp_time_slot.break_to > "'.$bookingEndTime.'"');
            //           })
            //           ->whereNotExists(function($q) use ($bookingEndTime,$dayId,$bookingTime){
            //             $q->whereRaw('sp_time_slot.break_from > "'.$bookingTime.'" AND sp_time_slot.break_to <= "'.$bookingEndTime.'"');
            //           });
            //       })
            //       ->whereDoesntHave('bookedTechnicanAsServiceProviders',function($query) use ($bookingDate,$bookingTime,$bookingEndTime){
            //         $query->where('booking_date','=',$bookingDate)
            //           ->whereIn('status',['1'])
            //           ->whereNull('technician_id')
            //           ->whereRaw('booked_technicians.booking_date="'.$bookingDate.'"')
            //           ->whereRaw('booked_technicians.booking_time <= "'.$bookingTime.'" AND booked_technicians.booking_end_time > "'.$bookingTime.'"')
            //           ->orwhereRaw('booked_technicians.booking_time <= "'.$bookingEndTime.'" AND booked_technicians.booking_end_time > "'.$bookingEndTime.'"');
            //       })
            //       ->first();

               
            //       if(!empty($servieProviderAsTechcian)){
                   
            //         $user->bookingTechnican()->create([
            //           'order_id'               => $order->id,
            //           'service_provider_id'    => $serviceProvider,
            //           'booking_date'           => $bookingDate,
            //           'booking_time'           => $bookingTime,
            //           'booking_end_time'       => $bookingEndTime,
            //           'status'                 => (string)$isBooked
            //         ]);

            //         $order->service_provider_id = $serviceProvider;
            //         $order->save();

            //         $serviceProviderIds = $serviceProvider;
                      
            //       }else{

            //         DB::rollBack();
            //         return response()->json(['status'=>0,'message' =>__('message.spTimeSlotBusy')]);

            //       }

            //     }else{

            //       DB::rollBack();
            //       return response()->json(['status'=>0,'message' =>__('message.spTimeSlotBusy')]);

            //     }

            //   }


            // }else{
            //   DB::rollBack();
            //   return response()->json(['status'=>0,'message' =>__('message.spTimeSlotBusy')]);
            // }


            $date_time  = \Carbon\Carbon::parse($request->date_time);

            $orders->bookedServiceProvider->booking_date = $date_time->format('Y-m-d');
            $orders->bookedServiceProvider->booking_time = $date_time->format('H:i:s');
            $orders->bookedServiceProvider->save();

            return response()->json([
                'status'  => 'Success',
                'message' => 'Booking successfully reschedule'
            ]);

        }else{
            return response()->json([
                'status'  => 'Error',
                'message' => 'You cant reschedule this booking'.$request->order_id
            ],422);
        }


    }
}
