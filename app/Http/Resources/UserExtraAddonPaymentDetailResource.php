<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Services\HesabeOrderPaymentServices;


class UserExtraAddonPaymentDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {  
 
        $response =  $this->paymentUrl((double)$this->amount,$this->order_id,1);

        if($request->header('X-localization')=='en'){

            return [
                'id'                 => $this->id,
                'totalAmount'        => $this->amount,
                'paymentType'        => $this->payment_type,
                'paymentStatus'      => $this->payment_status,
                'paymentGatewayLink' => $response['data']['paymenturl'].$response['data']['token']
            ];

        }else if($request->header('X-localization')=='ar'){

            return [
                'id'                 => $this->id,
                'totalAmount'        => $this->amount,
                'paymentType'        => $this->payment_type,
                'paymentStatus'      => $this->payment_status,
                'paymentGatewayLink' => $response['data']['paymenturl'].$response['data']['token']
            ];
        }
    }


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
