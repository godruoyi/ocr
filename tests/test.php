<?php

require __DIR__ . '/../vendor/autoload.php';

use Godruoyi\OCR\Application;
use Godruoyi\OCR\Support\Log;

$client = new Application([
    'log' => [
        'level' => 'debug',
        'file' => __DIR__ . DIRECTORY_SEPARATOR .'test.log'
    ],

    'providers' => [
        \Godruoyi\OCR\Providers\BaiduProvider::class,
        \Godruoyi\OCR\Providers\TencentProvider::class,
        \Godruoyi\OCR\Providers\AliyunProvider::class,
    ],

    'ocrs' => [
        'baidu' => [
            'app_key' => 'n84lW0qogPq6qGsuMU6kx0P0',
            'secret_key' => '2XhW1XBpSngY8nxkdGm5gl1tmko28EUR'
        ]
    ]
]);


// $a = $client->baidu->generalBasic(__DIR__ . DIRECTORY_SEPARATOR .'1.jpg');
$a = $client->baidu->accurateBasic(__DIR__ . DIRECTORY_SEPARATOR .'2.jpg');
dump($a);
