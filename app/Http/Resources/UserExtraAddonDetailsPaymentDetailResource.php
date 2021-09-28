<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\SubOrderExtraAddonPaymentHistory;
use App\Http\Services\HesabeOrderPaymentServices;

class UserExtraAddonDetailsPaymentDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    { 
      
        $getTotalOrder = SubOrderExtraAddonPaymentHistory::where('order_id',$this->order_id)->where('payment_type',"2")->where('payment_status',"0")->get();
        if($getTotalOrder){
            $orderAmount =0;
            foreach($getTotalOrder as $order){
                    $orderAmount += $order->amount;
            }
            $totalAmount = $orderAmount;
        }
         

        $response =  $this->paymentUrl((double)$this->amount,$this->order_id,1);

        if($request->header('X-localization')=='en'){

            return [
                'id'                 => $this->id,
                'totalAmount'        => $totalAmount,
                'paymentType'        => $this->payment_type,
                'paymentStatus'      => $this->payment_status,
                'paymentGatewayLink' => $response['data']['paymenturl'].$response['data']['token']
            ];

        }else if($request->header('X-localization')=='ar'){

            return [
                'id'                 => $this->id,
                'totalAmount'        =>$totalAmount,
                'paymentType'        => $this->payment_type,
                'paymentStatus'      => $this->payment_status,
                'paymentGatewayLink' => $response['data']['paymenturl'].$response['data']['token']
            ];
        }
    }

    //its only for knet and credit ,not in use
    public function paymentUrl($amount=0,$orderId='',$pType=1)
    {

      try{

        $data = [
            'MerchantCode' => config('app.payment_merchant_code'), 
            'Amount'       => number_format($amount,3),
            'Ptype'        =>  $pType,  //1 : Knet 2: Card
            'SuccessUrl'   => url('/addons/payment/success'),
            'FailureUrl'   => url('/addons/payment/failure'),
            'Variable1'    => $orderId,
            'Variable2'    => $amount
        ];

        $client   = new \GuzzleHttp\Client();
      
        $url      = config('app.payment_url'). "?". http_build_query($data);
      
        $res      = $client->post($url);

        $response =  json_decode((string)$res->getBody(), true);

        if(!empty($response) && $response['status'] == 'success'){
            return $response;
        }else{
            return false;
        }

      }catch (\Exception $e) {
        return response()->json(['Payment gateway not responding'],200);
      }
     
    }
}
