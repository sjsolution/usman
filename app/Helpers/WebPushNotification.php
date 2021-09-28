<?php 

namespace App\Helpers;

class WebPushNotification 
{ 
    public static function webPush($title='New Booking Received',$message='You have received a new booking. Please check booking details.')
    {
        $end_point = 'https://app.webpushr.com/api/v1/notification/send/all';

        $http_header = array( 
            "Content-Type: Application/Json", 
            "webpushrKey: KNNO195PMxkW7JKrEu1ojazy2hhflEsyOnqtarfZw9I", 
            "webpushrAuthToken: 3319"
        );

        $req_data = array(
            'title' 			 => $title, //required
            'message' 		     => $message, //required
            'target_url'	     => 'http://qa.maak.live', //required
            'name'			     => 'Booking',
            'icon'			     => 'http://qa.maak.live/images/DashLogo.png',
            // 'image'			     => 'http://qa.maak.live/images/DashLogo.png',
            // 'auto_hide'		     => 2,
            // 'expire_push'	=> '5m',
            // 'send_at'		=> '2019-10-10 19:31 +5:30',
            // 'action_buttons'=> array(	
            //     array('title'=> 'Demo', 'url' => 'https://www.webpushr.com/demo'),
            //     array('title'=> 'Rates', 'url' => 'https://www.webpushr.com/pricing')
            // )
        );

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_HTTPHEADER, $http_header);
        curl_setopt($ch, CURLOPT_URL, $end_point );
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($req_data) );
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
       // echo $response;

    }
}