<?php

return [

    'keycloak' => [
        'client_id'                => env('KEYCLOAK_CLIENT_ID'),
        'client_secret'            => env('KEYCLOAK_CLIENT_SECRET'),
        'base_url'                 => env('KEYCLOAK_BASE_URL'),
        'redirect'                 => env('APP_URL') . '/login/keycloak/callback',
        'notify_settings_base_url' => env('KEYCLOAK_NOTIFY_SETTINGS_BASE_URL'),
        'notify_website_title'     => env('NOTIFY_WEBSITE_TITLE'),
    ],
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

];
