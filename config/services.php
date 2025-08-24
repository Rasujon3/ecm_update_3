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

    'demo' => [
        'url' => env('DEMO_URL', 'https://dummy.hosstify.com/'),
    ],
    
    'surjopay' => [
        'username' => env('SURJOPAY_USERNAME', 'hawlader_drug'),
        'password' => env('SURJOPAY_PASSWORD', 'hawl29yj4dvxj5nd'),
        'token_url' => env('SURJOPAY_TOKEN_URL', 'https://engine.shurjopayment.com/api/get_token'),
    ],

];
