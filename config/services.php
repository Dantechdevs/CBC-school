<?php

return [
    'postmark' => ['token' => env('POSTMARK_TOKEN')],
    'ses'      => ['key' => env('AWS_ACCESS_KEY_ID'), 'secret' => env('AWS_SECRET_ACCESS_KEY'), 'region' => env('AWS_DEFAULT_REGION', 'us-east-1')],
    'slack'    => ['notifications' => ['bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'), 'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL')]],

    'mpesa' => [
        'env'             => env('MPESA_ENV', 'sandbox'),
        'consumer_key'    => env('MPESA_CONSUMER_KEY'),
        'consumer_secret' => env('MPESA_CONSUMER_SECRET'),
        'shortcode'       => env('MPESA_SHORTCODE'),
        'passkey'         => env('MPESA_PASSKEY'),
        'callback_url'    => env('MPESA_CALLBACK_URL'),
        'confirmation_url'=> env('MPESA_CONFIRMATION_URL'),
        'validation_url'  => env('MPESA_VALIDATION_URL'),
    ],

    'africastalking' => [
        'api_key'   => env('AT_API_KEY'),
        'username'  => env('AT_USERNAME', 'sandbox'),
        'sender_id' => env('AT_SENDER_ID', 'SCHOOL'),
        'env'       => env('AT_ENV', 'sandbox'),
    ],

    'firebase' => [
        'server_key' => env('FIREBASE_SERVER_KEY'),
        'project_id' => env('FIREBASE_PROJECT_ID'),
    ],

    'kemis' => [
        'api_url'     => env('KEMIS_API_URL', 'https://kemis.education.go.ke/api'),
        'api_key'     => env('KEMIS_API_KEY'),
        'school_code' => env('KEMIS_SCHOOL_CODE'),
    ],
];
