<?php

namespace App\Traits;

use Edujugon\PushNotification\PushNotification;
use App\Http\Resources\UserExtraAddonRequestResource;

trait PushNotifications
{
    public function sendNotification($title,$body,$token,$type,$orderId = '',$userDeviceType='')
    {
       
        $push = new PushNotification('fcm');
       
        $extraPaymentStatus = 0;

        $push->setUrl('https://fcm.googleapis.com/fcm/send');
      
        if(!empty($orderId)){

            $order = \App\Models\Orders::where('id',$orderId)->first();
            $extraPaymentStatus = !empty($order->extraAddonOrderPaymentHistory) &&  ($order->extraAddonOrderPaymentHistory->payment_status == '2' ) ? 1 : 0;

        }

        
        if(!empty($userDeviceType) && $userDeviceType =='2'){
            
            $response =  $push->setMessage([
                'data' => [
                    'notificationType'         => $type,
                    'title'                    => $title,
                    'body'                     => $body,
                    'extraAddonData'           => new UserExtraAddonRequestResource($order),
                    'extraAddonsPaymentStatus' => $extraPaymentStatus
                ],
                'content_available' =>true,
                'mutable_content'   =>true,  
            ]);

        }else{
            if($userDeviceType=='ios'){
              if($type == 'extra_service_addons_request'){

                $response =  $push->setMessage([
                    'notification' => [
                        'title'  => $title,
                        'body'   => $body,
                        'sound'  => 'default'
                    ],
                    'data' => [
                        'notificationType' => $type,
                        'title'            => $title,
                        'body'             => $body,
                        'extraAddonData'   => new UserExtraAddonRequestResource($order)
                    ],
                    'content_available' =>true,
                    'mutable_content'   =>true,  
                ]);

            }else{
              
                $response =  $push->setMessage([
                    'notification' => [
                        'title'  => $title,
                        'body'   => $body,
                        'sound'  => 'default'
                    ],
                    'data' => [
                        'notificationType' => $type,
                        'title'            => $title,
                        'body'             => $body,
                        'orderId'          => $orderId
                    ],
                    'content_available' =>true,
                    'mutable_content'   =>true,  
                ]);
             }
            }else{
                if($type == 'extra_service_addons_request'){

                $response =  $push->setMessage([
                   
                    'data' => [
                        'notificationType' => $type,
                        'title'            => $title,
                        'body'             => $body,
                        'extraAddonData'   => new UserExtraAddonRequestResource($order)
                    ],
                    'content_available' =>true,
                    'mutable_content'   =>true,  
                ]);

            }else{
              
                $response =  $push->setMessage([
                    
                    'data' => [
                        'notificationType' => $type,
                        'title'            => $title,
                        'body'             => $body,
                        'orderId'          => $orderId
                    ],
                    'content_available' =>true,
                    'mutable_content'   =>true,  
                ]);
            }
            }
            

        }
       
        $response->
        // setApiKey('AIzaSyB8brRKdAi9bHTjdQ2OgMG4umfp_6yK-nY')
        setApiKey('AAAAEXjTM5U:APA91bEKfyHX98dZK7qN6owZO9U_ys9kDspyNo8WrrT4WniCbfHw2EjYKows0qnvGk06d-6TWxoVP7WmOKxq47xfGBXVB6qtPAs3xybt4iWlz_Tdl-nN7-xP24SmhwVGDbI40QoRUUjb')
        ->setDevicesToken($token)
        ->send()
        ->getFeedback();

    }

    public function silentNotification($token,$status,$type ='')
    {
        $push = new PushNotification('fcm');
       
        $push->setUrl('https://fcm.googleapis.com/fcm/send');
      
        $response =  $push->setMessage([
            'aps' => [
                'alert'             => '',
                'content-available' =>true,
                'priority' => 'high'
            ],
            'data' => [
                'notificationType' => $type,
                'isVenderHidden'   => $status
            ]
        
        ])
        ->setApiKey('AAAAEXjTM5U:APA91bEKfyHX98dZK7qN6owZO9U_ys9kDspyNo8WrrT4WniCbfHw2EjYKows0qnvGk06d-6TWxoVP7WmOKxq47xfGBXVB6qtPAs3xybt4iWlz_Tdl-nN7-xP24SmhwVGDbI40QoRUUjb')
        // ->setApiKey('AIzaSyB8brRKdAi9bHTjdQ2OgMG4umfp_6yK-nY')
        ->setDevicesToken($token)
        ->send()
        ->getFeedback();
    }
}