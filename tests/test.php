<?php

require __DIR__ . '/../vendor/autoload.php';

use Godruoyi\OCR\Application;
use Godruoyi\OCR\Support\Log;

$client = new Application([
    'ocrs' => [

        'baidu' => [
            'app_key' => 'n84lW0qogPq6qGsuMU6kx0P0',
            'secret_key' => '2XhW1XBpSngY8nxkdGm5gl1tmko28EUR'
        ],

        'tencent' => [
            'app_id' => '1254032478',
            'secret_id' => 'AKIDzODdB1nOELz0T8CEjTEkgKJOob3t2Tso',
            'secret_key' => '6aHHkz236LOYu0nRuBwn5PwT0x3km7EL',
            'bucket' => 'test1'
        ],

        'aliyun' => [
            'appcode' => 'appcode',
        ]
    ]
]);


// $a = $client->tencent->namecard([__DIR__ . DIRECTORY_SEPARATOR .'3.jpg',__DIR__ . DIRECTORY_SEPARATOR .'3.jpg'], ['a' => 2]);
// $a = $client->tencent->idcard([__DIR__ . DIRECTORY_SEPARATOR .'5.jpg'], ['card_type' => 1]);
// $a = $client->tencent->drivinglicence('https://mc.qcloudimg.com/static/img/8a18f39819e718369782342e89a0f88c/image.png', ['card_type' => 1]);
$a = $client->baidu->generalEnhanced(['https://mc.qcloudimg.com/static/img/8a18f39819e718369782342e89a0f88c/image.png'], ['id_card_side' => 'front']);
// $a = $client->aliyun->idcard([__DIR__ . DIRECTORY_SEPARATOR .'2.jpg'], ['a' => 2]);
dump($a);
