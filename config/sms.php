<?php

return [

    'gateway' => env('SMS_GATEWAY'),

    'future_club' => [
        'url' => 'https://smsapi.future-club.com/fccsms.aspx',
        'method' => 'GET',
        'params' => [
            'UID' => env('FUTURE_CLUB_USERNAME'),
            'P' => env('FUTURE_CLUB_PASSWORD'),
            'S' => env('FUTURE_CLUB_SENDER_ID'),
        ]
    ],

    'twilio' => [
        'user_id' => env('TWILIO_ACCOUNT_SID'),
        'auth_token' => env('TWILIO_AUTH_TOKEN'),
        'from_number' => env('TWILIO_FROM_NUMBER'),
    ]
];
