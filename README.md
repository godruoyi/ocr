# The Best Image OCR SDK For BAT

<p align="center"><a href="https://godruoyi.com" target="_blank"><img src="https://laravel.com/assets/img/components/logo-laravel.svg"></a></p>

<p align="center">
<a href="https://travis-ci.org/godruoyi/ocr"><img src="https://travis-ci.org/godruoyi/ocr.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/godruoyi/ocr"><img src="https://poser.pugx.org/godruoyi/ocr/d/total.svg" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/godruoyi/ocr"><img src="https://poser.pugx.org/godruoyi/ocr/v/stable.svg" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/godruoyi/ocr"><img src="https://poser.pugx.org/laravel/ocr/license.svg" alt="License"></a>
</p>

# Feature

 - 自定义缓存支持；
 - 详细的 Debug 日志，一切交互一目了然；
 - 符合 PSR 标准，可以很方便的与你的框架结合，（[laravel-ocr](https://github.com/godruoyi/laravel-ocr)）；
 - 命名不那么乱七八糟；
 - 支持目前市面多家服务商

# Support

 - [百度 OCR](http://ai.baidu.com/tech/ocr)
 - [腾讯 OCR](https://cloud.tencent.com/product/ocr)
 - [阿里 OCR](https://data.aliyun.com/product/ocr)

# Requirement

 - PHP > 7.0
 - [composer](https://getcomposer.org/)

# Installation

```bash
composer require "godruoyi/ocr:~1.0"
```

# Usage

基本使用（以百度OCR为例）

```php

use Godruoyi\OCR\Application;

$app = new Application([
    'log' => [
        'level' => 'debug',
        'file' => __DIR__ . DIRECTORY_SEPARATOR .'test.log'
    ],

    'ocrs' => [
        'baidu' => [
            'app_key' => 'app_key',
            'secret_key' => 'secret_key'
        ],
    ]
]);

//身份证识别
$result = $app->baidu->idcard($filePath);

```

# 各平台支持的方法

> 详情请参考官方文档

所有平台支持的方法中，都满足以下结构：

```php

$app->platform->$method($file, $options = [])

```

`$file` 的值可以为

 1. 文件路径（完整）
 2. `SplFileInfo` 对象
 3. `Resource`
 4. 在线图片地址（部分服务商不支持）

### [百度OCR](http://ai.baidu.com/tech/ocr)

> 所有 `options` 的值都是可选的

 1、通用文字识别

```php

$app->baidu->generalBasic($file, [
    'language_type'         => 'CHN_ENG',  //支持的语言，默认为CHN_ENG（中英文混合）
    'detect_direction'      => false,      //是否检测图像朝向
    'detect_language'       => false,      //是否检测语言，默认不检测
    'probability'           => false,      //是否返回识别结果中每一行的置信度
]);

```

 2、通用文字识别（高精度版）

```php

$app->baidu->accurateBasic($file, [
    'detect_direction'      => false,      //是否检测图像朝向
    'probability'           => false,      //是否返回识别结果中每一行的置信度
]);

```

 3、通用文字识别（含位置信息版）

```php

$app->baidu->general($file, [
    'recognize_granularity' => 'big',      //是否定位单字符位置
    'language_type'         => 'CHN_ENG',  //CHN_ENG：中英文混合；默认为CHN_ENG
    'detect_direction'      => false,      //是否检测图像朝向
    'detect_language'       => false,      //是否检测语言，默认不检测
    'vertexes_location'     => false,      //是否返回文字外接多边形顶点位置，不支持单字位置。默认为false
    'probability'           => false,      //是否返回识别结果中每一行的置信度
]);

 ```

 4、通用文字识别（含位置高精度版）

```php

$app->baidu->accurate($file, [
    'recognize_granularity' => 'big',      //是否定位单字符位置
    'detect_direction'      => false,      //是否检测图像朝向
    'vertexes_location'     => false,      //是否返回文字外接多边形顶点位置，不支持单字位置。默认为false
    'probability'           => false,      //是否返回识别结果中每一行的置信度
]);

```

 5、通用文字识别（含生僻字版）

```php

$app->baidu->generalEnhanced($file, [
    'language_type'         => 'CHN_ENG',  //CHN_ENG：中英文混合；默认为CHN_ENG
    'detect_direction'      => false,      //是否检测图像朝向
    'vertexes_location'     => false,      //是否返回文字外接多边形顶点位置，不支持单字位置。默认为false
    'probability'           => false,      //是否返回识别结果中每一行的置信度
]);

```

 6、网络图片文字识别

```php

$app->baidu->webimage($file, [
    'detect_direction'      => false,      //是否检测图像朝向
    'detect_language'       => false,      //是否检测语言，默认不检测
]);

```

 7、身份证识别

```php

$app->baidu->idcard($file, [
    'detect_direction'      => false,      //是否检测图像朝向
    'id_card_side'          => 'front',    //front：身份证正面；back：身份证背面 （注意，该参数必选）
    'detect_risk'           => false,      //是否开启身份证风险类型功能，默认false
]);

```

 8、银行卡识别

```php

$app->baidu->bankcard($file, [
]);

```

 9、驾驶证识别

```php

$app->baidu->drivingLicense($file, [
    'detect_direction'      => false,      //是否检测图像朝向
]);

```

 10、行驶证识别

```php

$app->baidu->vehicleLicense($file, [
    'detect_direction'      => false,      //是否检测图像朝向
    'accuracy'              => 'normal'    // normal 使用快速服务，1200ms左右时延,缺省或其它值使用高精度服务，1600ms左右时延
]);

```

 11、车牌识别

```php

$app->baidu->licensePlate($file, [
    'multi_detect'          => false,      //是否检测多张车牌，默认为false
]);

```

 12、营业执照识别

```php

$app->baidu->businessLicense($file, [
]);

```

 13、通用票据识别

```php

$app->baidu->receipt($file, [
    'recognize_granularity' => 'big',      //是否定位单字符位置
    'probability'           => false,      //是否返回识别结果中每一行的置信度
    'accuracy'              => 'normal'    // normal 使用快速服务，1200ms左右时延,缺省或其它值使用高精度服务，1600ms左右时延
    'detect_direction'      => false,      //是否检测图像朝向
]);

```
