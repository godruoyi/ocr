<?php

require __DIR__ . '/../vendor/autoload.php';

use Godruoyi\OCR\Application;
use Godruoyi\OCR\Support\Log;

$client = new Application([
    'log' => [
        'level' => 'debug',
        'file' => __DIR__ . DIRECTORY_SEPARATOR .'test.log'
    ],

    'ocrs' => [
        'baidu' => ''
    ]
]);

$x = Log::info('hello world!');
var_dump($x);
