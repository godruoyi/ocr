<?php

return [

    'default' => 'aliyun',


    'drivers' => [

        'aliyun' => [
            'appcode' => '62fc576437df41e49b35ceebafc504b7',
            'secret_id'  => '',
            'secret_key' => '',
        ],

        'baidu' => [
            'access_key' => 'f4d0a3dcbebc46b1b2ec57da8cf49e90',
            'secret_key' => 'd24a4c0f9e0343409ee93ada307c6a22'
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
