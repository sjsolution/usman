<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use DB;
use Carbon\Carbon;
use App\Transaction;

class TransactionReport implements FromCollection,WithHeadings
{
    public function __construct($data)
    {
        $this->data        = $data;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        if(!empty($this->data['date_range'])){

            $range = explode('-', $this->data['date_range']);

            $endDate = Carbon::parse($range[1])->format('Y-m-d')." 23:59:59";
     
            $startDate  = Carbon::parse($range[0])->format('Y-m-d')." 00:00:00";

        }else{

            $endDate   = Carbon::now()->format('Y-m-d')." 23:59:59";
            
            $startDate = Carbon::now()->subDays(30)->format('Y-m-d')." 00:00:00";
        }



        $trnxStatus = '';

        $resposneArr = [];

        
        if($this->data['settle_status'] == '1')
            $settleStatus = [0,1];
        elseif($this->data['settle_status'] == '2')
            $settleStatus = [1];
        elseif($this->data['settle_status'] == '3')
            $settleStatus = [0];
        else
            $settleStatus = [0,1];
               
         

        $result = Transaction::join('orders as ord',function($q){
            $q->on('ord.id','=','transactions.order_id')
               ->where('ord.payment_status','<>','0');
         })->orderBy('transactions.id','desc')
         ->whereRaw("transactions.created_at BETWEEN '$startDate' AND  '$endDate'")
         ->whereIn('transactions.is_settlement',$settleStatus)
         ->select('transactions.status as trnsStatus','ord.*','transactions.*','transactions.id as trnsID','ord.id as orderID')
         ->get();

         foreach($result as $item){

            if($item->trnsStatus == '0'){
                $trnxStatus =  'Pending';
             }elseif($item->trnsStatus == '1'){
                $trnxStatus =  'Success';
             }elseif($item->trnsStatus == '2'){
                $trnxStatus =  'Fail';
             }elseif($item->trnsStatus == '3'){
                $trnxStatus =  'Cancelled';
             }else{
                $trnxStatus =  '--';
             }

            
            
            $resposneArr[] = [
                'order_no'                 => $item->order_number,
                'service_provider'         => !empty($item->service_provider_id) ? \App\User::find($item->service_provider_id)->full_name_en : '--',
                'service_amount'           => $item->total_amount,
                'commission'               => $item->commission,
                'discount'                 => !empty($item->coupon_amount)         ? $item->coupon_amount        : 0,
                'knet_fees'                => !empty($item->knet_fees)             ? $item->knet_fees            : 0,
                'others_fee'               => !empty($item->others_fees)           ? $item->others_fees          : 0,
                'user_applicable_fee'      => !empty($item->user_applicable_fee)   ? $item->user_applicable_fee  : 0,
                'net_payable'              => !empty($item->net_payable)           ? $item->net_payable          : 0,
                'settle_status'            => ($item->is_settlement == 1)          ? 'Settled'                   : 'Unsettled',
                'status'                   => $trnxStatus,
                'created_at'               => $item->created_at
            ];
        }

        return collect($resposneArr);
    }

    public function headings(): array
    {
        return [
            'Order Number',
            'Service Provider',
            'Service Amount',
            'Commission',
            'Discount',
            'KNET Fees',
            'Others Fees',
            'User Applicable Fees',
            'Net Payable',
            'Settle Status',
            'Transaction Status',
            'Date'
        ];
    }
}
