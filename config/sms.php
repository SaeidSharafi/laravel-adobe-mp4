<?php

return [
    'default' => 'rangine',
    'sandbox' => env('SMS_SANDBOX', true),
    'gateway' => [
        'melipayamak' => [
            'website'    => 'http://melipayamak.ir',
            'webService' => 'http://melipayamak.ir/post/send.asmx?wsdl',
            'username'   => '',
            'password'   => '',
            'from'       => '',
        ],
        'rangine' => [
            'website'    => 'https://sms.rangine.ir/',
            'webService' => 'http://ippanel.com/class/sms/wsdlservice/server.php?wsdl',
            'username'   => env('SMS_USERNAME', 'username'),
            'password'   => env('SMS_PASSWORD', 'password'),
            'from'       => env('SMS_FROM', '5000 '),
        ],
    ],

    'patterns' => [
        'appointment_created'   => env('APPOINTMENT_CREATED_NOTIFICATION_PATTERN', ''),
        'appointment_updated'   => env('APPOINTMENT_UPDATED_NOTIFICATION_PATTERN', ''),
        'appointment_reminder'  => env('APPOINTMENT_REMINDER_NOTIFICATION_PATTERN', ''),
    ]

];
