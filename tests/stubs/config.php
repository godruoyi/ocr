<?php

/*
 * This file is part of the godruoyi/ocr.
 *
 * (c) Godruoyi <gmail@godruoyi.com>
 *
 * This source file is subject to the MIT license that is bundled.
 */

return [
    /*
    |--------------------------------------------------------------------------
    | Default client
    |--------------------------------------------------------------------------
    |
    | 指定一个默认的 client 名称，其值需要在下列的 drivers 数组中配置。
    |
    */
    'default' => 'aliyun',

    /*
    |--------------------------------------------------------------------------
    | Client 配置
    |--------------------------------------------------------------------------
    |
    | Client 配置信息，包括基本密钥等；注意目前 aliyun 暂只支持 appcode 方式。
    |
    */
    'drivers' => [
        'aliyun' => [
            'appcode' => $_ENV['ALIYUN_APPCODE'] ?? '',
            'secret_id' => '',
            'secret_key' => '',
        ],

        'baidu' => [
            'access_key' => $_ENV['BAIDU_ACCESS_KEY'] ?? '',
            'secret_key' => $_ENV['BAIDU_SECRET_KEY'] ?? '',
        ],

        'tencent' => [
            'secret_id' => $_ENV['TENCENT_SECRET_ID'] ?? '',
            'secret_key' => $_ENV['TENCENT_SECRET_KEY'] ?? '',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | 日志配置
    |--------------------------------------------------------------------------
    |
    | 基于 Monolog，可用的日志驱动有： "single", "daily", "slack", "syslog",
    | "errorlog", "monolog", "custom", "stack"
    |
    */
    'log' => [
        'enable' => true,
        'default' => 'daily',
        'channels' => [
            'daily' => [
                'name' => 'OCR',
                'driver' => 'daily',
                'path' => __DIR__ . '/ocr.log',
                'level' => 'debug',
                'days' => 14,
            ],
            'errorlog' => [
                'name' => 'OCR',
                'level' => 'debug',
            ],
        ],
    ],
];
