<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Hesabe Payment Gateway Constant
    |--------------------------------------------------------------------------
    |
    | This value is the name of your application. This value is used when the
    | framework needs to place the application's name in a notification or
    | any other location as required by the application or its packages.
    |
    */

    'PAYMENT_API_URL'     => env('PAYMENT_API_URL', 'https://api.hesabe.com'),
    'MERCHANT_SECRET_KEY' => env('MERCHANT_SECRET_KEY','v60zByOVZEnwaW7bxPvaYgXrNb1jp23L'),
    'MERCHANT_IV'         => env('MERCHANT_IV','ZEnwaW7bxPvaYgXr'),
    'ACCESS_CODE'         => env('ACCESS_CODE','5bc9fd45-bd4e-4291-a602-355ea7881b33'),
    'MERCHANT_CODE'       => env('MERCHANT_CODE','16701519'),
    'PAYMENT_URL'         => env('PAYMENT_URL','https://www.hesabe.com/authpostsingle')
    
];