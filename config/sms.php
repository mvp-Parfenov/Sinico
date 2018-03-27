<?php

return [
    'app_id' => env('SMS_RU_APP_ID'),
    'url' => env('SMS_RU_APP_URL'),
    // 'sms.ru', 'array'
    'driver' => env('SMS_DRIVER', 'sms.ru'),
    'drivers' => [
        'sms.ru' => [
            'app_id' => env('SMS_RU_APP_ID'),
            'url' => env('SMS_RU_APP_URL'),
        ]
    ]
];