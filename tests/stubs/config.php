<?php

return [

    'default' => 'aliyun',


    'drivers' => [

        'aliyun' => [
            'appcode' => '62fc576437df41e49b35ceebafc504b7',
            'secret_id'  => '',
            'secret_key' => '',
        ],
    ],

    'disable_log' => false,

    'log' => [
        'default' => 'errorlog',
        'channels' => [
            'errorlog' => [
                'driver' => 'errorlog',
                'level' => \Psr\Log\LogLevel::DEBUG,
                'name' => 'OCR',
                'formatter' => \GuzzleHttp\MessageFormatter::DEBUG,
            ],
        ]
    ],
];
