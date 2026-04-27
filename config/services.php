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
        'scheme' => 'https',
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],


    'whatsapp' => [
        'enabled' => env('WHATSAPP_ENABLED', false),
        'api_version' => env('WHATSAPP_API_VERSION', 'v20.0'),
        'phone_number_id' => env('WHATSAPP_PHONE_NUMBER_ID'),
        'token' => env('WHATSAPP_ACCESS_TOKEN'),
        'admin_phone' => env('WHATSAPP_ADMIN_PHONE', '6287834755177'),
        'use_template' => env('WHATSAPP_USE_TEMPLATE', false),
        'template_language' => env('WHATSAPP_TEMPLATE_LANGUAGE', 'id'),
        'templates' => [
            'laporan_diterima' => env('WHATSAPP_TEMPLATE_LAPORAN_DITERIMA'),
            'laporan_diverifikasi' => env('WHATSAPP_TEMPLATE_LAPORAN_DIVERIFIKASI'),
            'laporan_ditolak' => env('WHATSAPP_TEMPLATE_LAPORAN_DITOLAK'),
        ],
    ],
];
