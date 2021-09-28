<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Stripe, Mailgun, SparkPost and others. This file provides a sane
    | default location for this type of information, allowing packages
    | to have a conventional place to find your various credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'sparkpost' => [
        'secret' => env('SPARKPOST_SECRET'),
    ],

    'stripe' => [
        'model' => App\User::class,
        'key' => env('STRIPE_KEY'),
        'secret' => env('STRIPE_SECRET'),
        'webhook' => [
            'secret' => env('STRIPE_WEBHOOK_SECRET'),
            'tolerance' => env('STRIPE_WEBHOOK_TOLERANCE', 300),
        ],
    ],

    'myfatoorah' => [
        "token"   => '_wAbIhoPWBtUlKvijrGRSovkFYOZNMo37aPLqKOTWlPubnsL2rwKITqnvnGm_h7TqN2mnYUlKmlr1YjT5IkXSskYug8_K9GC85vnu0JUmoS2GPdTEpEuJE28llXld0me0hnJ3S6i1zr3cqXNo927nu8OOsqRxsv001SGPHxxGdI102s9F4w5ZZVMSgK7mqKW2yi9SGuXC93GgMov4zZhu8iVk_mibGbBdMP2WaqjpFnVB7wxkGz3tlME4smykPx-dO4aKUiueeItb0k7KXf24i5HXSa_mxfPSkLapMuE2ep_ZD27dE2xxAgF_v1S-W4nWJk02Qk3JiU7XVUfKvgKaoK3LmLqlxsITqHoeUvyeirsNpjZRqmRu7OUeTETXL73dW5ix0HF0W3QrOr8d4kp40x7kCAI7tZUjPVqqu9yPeuqQKRpyxlYnykJ4bPyAuIa96CoJlyNYQ0lZIPbtthoL3eO-Rxj3HB2a2L-Pv_TO7WWhbHk5LwIH-34x6nmqCKzW1waLLQ8CpDPo6Xuzo-23rLLqYDQXYiVStpUBCGhm6OEhAmwj_Dx_7iwuL06kkcBX_C8RgS0QJeQptX59SoAuLaWWfMKp5z2lSCTWJJkJypg6EWSwaMUW7qeLXgVYR_LWueleYIL8GPCUmj4OljPWTWN_QHBcx5OTtXi6uQrnvh4YvNS',
        "paymentURL"   =>   'https://apitest.myfatoorah.com'
    ],

];
