<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Traits\PushNotifications;
use App\Events\SendOrderNotification;
use App\Jobs\SendInvoiceEmailJob;
use WebPushNotification;
use App\Models\Orders;
use App\User;
use App\FeeCharge;
use App\Notifications\SPNotifyBookingNotification;
use App\Http\Services\HesabeOrderPaymentServices;
use App\Http\Services\MyFatoorah\MyFatoorahPaymentServices;
use DB;
use App\Payment;



class PaymentController extends Controller
{
    use PushNotifications;

    public function succeess(Request $request)
    {        
        $maakPercent      = 0;
        $maakFixedPrice   = 0;
        $commissionAmount = 0;
        $knetFee          = 0;
        $otherFee         = 0;
  
        //Hesabe Payment
        $hesabe                     = new HesabeOrderPaymentServices;
        $hesabeSuccessResponse      = $hesabe->getPaymentResponse($request->data);
        //
       

        $order = Orders::where('id',$hesabeSuccessResponse['response']['variable1'])->with('subOrder')->first();

        //Knet & Wallet Transaction
        if(!empty($order->wallet_amount) && ($order->payment_type == '3')){

            $user = User::find($order->user_id);
            $remaningTotalAmount = (double)$user->amount - (double)$order->wallet_amount;
           
            if( $remaningTotalAmount >= 0) {
                $user->amount            = $remaningTotalAmount;
                $user->save();

                $user->wallet()->create([
                    'transaction_amount' => $order->wallet_amount,
                    'closing_amount'     => $remaningTotalAmount,
                    'transaction_type'   => '2', //debit amount from wallet
                    'description'        => "Debited from wallet",
                    'description_ar'     => "مدين من المحفظة"
                ]);
            } 
        }
        
        $serviceProviderDetails = User::find($hesabeSuccessResponse['response']['variable2']);

        if(!empty($serviceProviderDetails)){
            $maakPercent      = !empty($serviceProviderDetails->maak_percentage) ? $serviceProviderDetails->maak_percentage : 0;
            $maakFixedPrice   = !empty($serviceProviderDetails->fixed_price) ? $serviceProviderDetails->fixed_price : 0;
            $commissionAmount = $maakFixedPrice + (((double)$order->total_amount - $order->user_applicable_fee ) *  $maakPercent) / 100;
        } 

        $feeCharges = FeeCharge::where('status',1)->first();


        //Knet
        if($order->payment_type == '2' || $order->payment_type == '3'){
            $knetFee = $feeCharges->knet_fixed_charges + ((double)$order->net_payable_amount *  $feeCharges->knet_commission_charges) / 100;
        //Credit
        }else if($order->payment_type == '4' || $order->payment_type == '5'){
            $otherFee =  $feeCharges->other_fixed_charges + ((double)$order->net_payable_amount *  $feeCharges->other_commission_charges) / 100;

        }

        $order->transaction()->create([
            'service_amount'      => $order->total_amount,
            'service_provider_id' => $order->service_provider_id,
            'maak_percentage'     => $maakPercent,
            'fixed_amount'        => $maakFixedPrice,
            'commission'          => $commissionAmount + $order->user_applicable_fee,
            'knet_fees'           => $knetFee,
            'others_fees'         => $otherFee,
            'net_payable'         => $order->total_amount -  $order->coupon_amount,
            'payment_token'       => $hesabeSuccessResponse['response']['paymentToken'],
            'payment_id'          => $hesabeSuccessResponse['response']['paymentId'],
            'status'              => '1',
            'user_applicable_fee' => $order->user_applicable_fee,
            'cash_payable'        => $order->total_amount - ( $commissionAmount + $order->user_applicable_fee ),
            'actual_sp_share'     => $order->total_amount - ( $commissionAmount + $order->user_applicable_fee ),
            'net_sp_share'        => $order->total_amount - ( $commissionAmount + $order->user_applicable_fee ),
            'net_commission'      => ($commissionAmount + $order->user_applicable_fee) - $order->coupon_amount
        ]);  

        $order->payment_status = '2';

        // if($order->category_type == '1')
        //    $order->status = '2';
           
        $order->save();

        $order->serviceProvider[0]->status = '1';
        $order->serviceProvider[0]->save();


        $order->revenue()->create([
            'status'    => '1',
            'amount'    =>  ($commissionAmount + $order->user_applicable_fee) - $order->coupon_amount,
            'sp_amount' =>  $order->total_amount - ( $commissionAmount + $order->user_applicable_fee )
        ]);

        $orderNotification = $order->notification()->create([
            'title'       => 'New Booking Received',
            'description' => 'You have received a new booking. Please check booking details.',
            'type'        => '1'
        ]);

        $orderNotification->notificationStatus()->create([
            'user_id'  => $hesabeSuccessResponse['response']['variable2'] ??  null
        ]);

        $userServiceProvider = User::where('id',$order->service_provider_id)->first();
        
        $mailDetails = [
            'greeting'     => 'Hello',
            'body'         => 'New Booking Received',
            'booking_id'   => $order->order_number,
            'booking_amt'  => $order->total_amount,
            'userName'     => !empty($order->user->full_name_en) ? $order->user->full_name_en : $order->user->full_name_ar,
            'userEmail'    => $order->user->email,
            'bookingDate'  => $order->serviceProvider[0]->booking_date,
            'bookingTime'  => $order->serviceProvider[0]->booking_time,
            'thanks'       => 'Thanks & Regards,',
            'subject'      => 'MAAK : New Booking Received'
        ];

        //Mail send to service provider on secondary mail
        $spUserEmail = new User;
        
        $spUserEmail->email = $userServiceProvider->secondary_email ?? $userServiceProvider->email;
        $spEmail =  $spUserEmail->email;
        // $spUserEmail->notify(new SPNotifyBookingNotification($mailDetails));
        // dd($spUserEmail);
        event(new SendOrderNotification());

        dispatch(new SendInvoiceEmailJob($order,$spEmail));

        dispatch(new SendInvoiceEmailJob($order,'orders@maak.live'));

        //send notification to technician
        if(!empty($order->serviceProvider[0]->technician)){

            foreach($order->serviceProvider[0]->technician->deviceInfo as $key){
            
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
        }

        $order->userNotification()->create([
            'user_id'           => $order->user_id,
            'title_en'          => 'New Booking Received',
            'title_ar'          => 'تم استلام الحجز الجديد',
            'body_en'           => 'You have received a new booking. Please check booking details.',
            'body_ar'           => 'لقد استلمت الحجز الجديد. يرجى التحقق من تفاصيل الحجز.',
            'notification_type' => '1'
        ]);

        //Web push
        WebPushNotification::webPush('New Booking Received','You have received a new booking. Please check booking details.');

        return view('payment_success');

    }

    public function failure(Request $request)
    {
        $maakPercent      = 0;
        $maakFixedPrice   = 0;
        $commissionAmount = 0;
        $knetFee          = 0;
        $otherFee         = 0;

        //Hesabe Payment
        $hesabe                     = new HesabeOrderPaymentServices;
        $hesabeSuccessResponse      = $hesabe->getPaymentResponse($request->data);
        //

        $order = \App\Models\Orders::where('id',$hesabeSuccessResponse['response']['variable1'])->delete();

        return view('payment_fail');
        
    }

    /**
     * Fatoorah payment success url
     */
    public function successCallback(Request $request)
    {

        DB::beginTransaction();

        $hesabe         = new MyFatoorahPaymentServices;
        $response       = $hesabe->successUrl($request);

        if($response['IsSuccess'] == true){


            $order_id       = $response['Data']['UserDefinedField'];
            /**UPDATE STATUS DATA IN Orders */
            $order = Orders::find($order_id);
            
            if(!empty($order))
            {
                $order->payment_id             = $request->paymentId;
                $order->transaction_id         = $response['Data']['InvoiceTransactions'][0]['TransactionId'] ?? null;
                $order->payment_status         = '2';
                $order->gateway                = '1';
                $order->save();
      
                $paymentStore = Payment::create([
                    'order_id'                       => $order->id,
                    'category_type'                  => $order->category_type,
                    'service_amount'                 => $order->total_amount,
                    'net_payable'                    => $response['Data']['InvoiceItems'][0]['UnitPrice'],
                    'paymentId'                      => $response['Data']['InvoiceTransactions'][0]['PaymentId'],
                    'transactionId'                  => $response['Data']['InvoiceTransactions'][0]['TransactionId'] ?? null,
                    'getCustomerServiceCharge'       => $response['Data']['InvoiceTransactions'][0]['CustomerServiceCharge'],
                    'referenceId'                    => $response['Data']['InvoiceTransactions'][0]['ReferenceId'],                       
                    'invoiceReference'               => $response['Data']['InvoiceReference'],                       
                    'invoiceStatus'                  => $response['Data']['InvoiceStatus'],                       
                    'invoiceId'                      => $response['Data']['InvoiceId'],
                    'customerName'                   => $response['Data']['CustomerName'],
                    'paymentGateway'                 => $response['Data']['InvoiceTransactions'][0]['PaymentGateway'],                    
                ]);
                        
                DB::commit();
      
                    echo "<h1 style='color:green'>Transaction Success, Refrence Id: $paymentStore->referenceId </h1>";
            }else{

                DB::rollBack();
                return response()->json(['status' => 0 ,'message' => 'something went wrong'],200);

            }

        }else{

            DB::rollBack();
            return response()->json(['status' => 0 ,'message' => 'something went wrong'],200);
        }

    }

    /**
     * Fatoorah payment error url
     */
    public function errorCallback(Request $request)
    {
        try{

            DB::beginTransaction();

            $hesabe         = new MyFatoorahPaymentServices;
            $response       = $hesabe->failureUrl($request);

            $order_id       = $json['Data']['UserDefinedField'];
            /**UPDATE STATUS DATA IN Orders */
            
            $order = Orders::find($order_id);
           
            if(!empty($order)){
                $order->payment_id             = $request->paymentId;
                $order->transaction_id         = $request['Data']['InvoiceTransactions'][0]['TransactionId'] ?? null;
                $order->payment_status         = '3';
                $order->gateway                = '1';
                $order->save();

                /**STORE DATA IN PAYMENT */
                $paymentStore = Payment::create([
                    'order_id'                       => $order->id,
                    'category_type'                  => $order->category_type,
                    'service_amount'                 => $order->total_amount,
                    'net_payable'                    => $request['Data']['InvoiceItems'][0]['UnitPrice'],
                    'paymentId'                      => $request['Data']['InvoiceTransactions'][0]['PaymentId'],
                    'transactionId'                  => $request['Data']['InvoiceTransactions'][0]['TransactionId'] ?? null,
                    'getCustomerServiceCharge'       => $request['Data']['InvoiceTransactions'][0]['CustomerServiceCharge'],
                    'referenceId'                    => $request['Data']['InvoiceTransactions'][0]['ReferenceId'],                       
                    'invoiceReference'               => $request['Data']['InvoiceReference'],                       
                    'invoiceStatus'                  => $request['Data']['InvoiceStatus'],                       
                    'invoiceId'                      => $request['Data']['InvoiceId'],
                    'customerName'                   => $request['Data']['CustomerName'],
                    'paymentGateway'                 => $request['Data']['InvoiceTransactions'][0]['PaymentGateway'],                    
                ]);
                
                DB::commit();
  
                echo "<h1 style='color:red'>Transaction Failure, Refrence Id: $paymentStore->referenceId </h1>";
            }
    
        }catch(\Exception $e){
            DB::rollback();
            throw $e;
            return redirect()->back()->with('success',__('message.something_went_wrong'));
        }
    }
}
