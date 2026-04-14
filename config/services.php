<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'key' => env('POSTMARK_API_KEY'),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
    ],

    'payment' => [
        'bank_id' => env('PAYMENT_BANK_ID', 'MB'),
        'bank_account' => env('PAYMENT_BANK_ACCOUNT', '80000534818'),
        'bank_name' => env('PAYMENT_BANK_NAME', 'DINH BA VU'),
    ],

    'sepay' => [
        'merchant_id' => env('SEPAY_MERCHANT_ID', ''),
        'secret_key' => env('SEPAY_SECRET_KEY', ''),
        'api_token' => env('SEPAY_API_TOKEN', ''),
        'environment' => env('SEPAY_ENVIRONMENT', 'sandbox'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

];
