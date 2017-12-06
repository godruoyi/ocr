# The Best Image Ocr SDK For BAT.

[![Latest Stable Version](https://poser.pugx.org/godruoyi/ocr/v/stable)](https://packagist.org/packages/godruoyi/ocr)
[![Total Downloads](https://poser.pugx.org/godruoyi/ocr/downloads)](https://packagist.org/packages/godruoyi/ocr)
[![License](https://poser.pugx.org/godruoyi/ocr/license)](https://packagist.org/packages/godruoyi/ocr)

- [百度 OCR](#baidu-ocr)
    - [通用文字识别](#baidu-generalBasic)
    - [通用文字识别（高精度版）](#baidu-accurateBasic)
    - [通用文字识别（含位置信息版）](#baidu-general)
    - [通用文字识别（含位置高精度版）](#baidu-accurate)
    - [通用文字识别（含生僻字版）](#baidu-generalEnhanced)
    - [网络图片文字识别](#baidu-webimage)
    - [银行卡识别](#baidu-bankcard)
    - [身份证识别](#baidu-idcard)
    - [驾驶证识别](#baidu-drivingLicense)
    - [行驶证识别](#baidu-vehicleLicense)
    - [车牌识别](#baidu-licensePlate)
    - [营业执照识别](#baidu-businessLicense)
    - [通用票据识别](#baidu-receipt)
- [Aliyun OCR](#aliyun-ocr)
    - [身份证识别](#aliyun-idcard)
    - [行驶证识别](#aliyun-vehicle)
    - [驾驶证识别](#aliyun-driverLicense)
    - [门店识别](#aliyun-shopSign)
    - [英文识别](#aliyun-english)
    - [营业执照识别](#aliyun-businessLicense)
    - [银行卡识别](#aliyun-bankCard)
    - [名片识别](#aliyun-businessCard)
    - [火车票识别](#aliyun-trainTicket)
    - [车牌识别](#aliyun-vehiclePlate)
    - [通用文字识别](#aliyun-general)
- [Tencent OCR](#tencent-ocr)
    - [名片识别](#tencent-namecard)
    - [身份证识别](#tencent-idcard)
    - [行驶证驾驶证识别](#tencent-drivingLicence)
    - [通用印刷体识别](#tencent-general)
    - [银行卡识别](#tencent-bankcard)
    - [车牌号识别](#tencent-plate)
    - [营业执照识别](#tencent-bizlicense)
- [Tencent OCR For AI](#tencent-ocr-for-ai)
    - [身份证识别](#tencent-idcard-for-ai)
    - [名片识别](#tencent-namecard-for-ai)
    - [行驶证驾驶证识别](#tencent-driverlicen-for-ai)
    - [银行卡识别](#tencent-bankcard-for-ai)
    - [通用印刷体识别](#tencent-general-for-ai)
    - [营业执照识别](#tencent-bizlicense-for-ai)

# Feature

 - 自定义缓存支持；
 - 符合 PSR 标准，可以很方便的与你的框架结合；
 - 命名不那么乱七八糟；
 - 支持目前市面多家服务商

 [查看更新日志](https://github.com/godruoyi/ocr/blob/master/CHANGELOG.md)

# Support

 - [百度 OCR](http://ai.baidu.com/tech/ocr)
 - [腾讯 万象优图](https://cloud.tencent.com/product/ocr)
 - [腾讯 AI 开放平台](https://ai.qq.com/)
 - [阿里 OCR](https://data.aliyun.com/product/ocr)

# Requirement

 - PHP > 5.6
 - [composer](https://getcomposer.org/)

# Installation

```bash
composer require godruoyi/ocr
```

[Laravel 5 拓展包](https://github.com/godruoyi/laravel-ocr)

# Usage

基本使用（以百度OCR为例）

```php
use Godruoyi\OCR\Application;

$app = new Application([
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

**返回结果**

```json
{
    "log_id": 530427582,
    "image_status": "normal",
    "words_result_num": 6,
    "words_result": {
        "住址": {
            "words": "上海市闵行区华漕镇红卫村宗家巷1号"
        },
        "出生": {
            "words": "19870723"
        },
        "姓名": {
            "words": "鹿晗"
        },
        "公民身份号码": {
            "words": "123456789123456132X"
        },
        "性别": {
            "words": "男"
        },
        "民族": {
            "words": "汉"
        }
    }
}
```

# 各平台支持的方法

> 详情请参考官方文档

所有平台支持的方法中，都满足以下结构：

```php
$app->platform->$method($files, $options = [])
```

`$files` 的值可以为

 1. 文件路径（完整）
 2. `SplFileInfo` 对象
 3. `Resource`
 4. 在线图片地址（部分服务商不支持）
 5. Array

 > 注：`options` 的值都是可选的

<a name="baidu-ocr"></a>
## [百度OCR](http://ai.baidu.com/tech/ocr)

目前采用 `AccessToken` 作为 `API` 认证方式，查看[鉴权认证机制](http://ai.baidu.com/docs#/Auth/top)

<a name="baidu-generalBasic"></a>
#### 通用文字识别

```php
$app->baidu->generalBasic($file, [
    'language_type'         => 'CHN_ENG',  //支持的语言，默认为CHN_ENG（中英文混合）
    'detect_direction'      => false,      //是否检测图像朝向
    'detect_language'       => false,      //是否检测语言，默认不检测
    'probability'           => false,      //是否返回识别结果中每一行的置信度
]);
```
<a name="baidu-accurateBasic"></a>
#### 通用文字识别（高精度版）

```php
$app->baidu->accurateBasic($file, [
    'detect_direction'      => false,      //是否检测图像朝向
    'probability'           => false,      //是否返回识别结果中每一行的置信度
]);
```
<a name="baidu-general"></a>
#### 通用文字识别（含位置信息版）

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
 <a name="baidu-accurate"></a>
#### 通用文字识别（含位置高精度版）

```php
$app->baidu->accurate($file, [
    'recognize_granularity' => 'big',      //是否定位单字符位置
    'detect_direction'      => false,      //是否检测图像朝向
    'vertexes_location'     => false,      //是否返回文字外接多边形顶点位置，不支持单字位置。默认为false
    'probability'           => false,      //是否返回识别结果中每一行的置信度
]);
```
<a name="baidu-generalEnhanced"></a>
#### 通用文字识别（含生僻字版）

```php
$app->baidu->generalEnhanced($file, [
    'language_type'         => 'CHN_ENG',  //CHN_ENG：中英文混合；默认为CHN_ENG
    'detect_direction'      => false,      //是否检测图像朝向
    'vertexes_location'     => false,      //是否返回文字外接多边形顶点位置，不支持单字位置。默认为false
    'probability'           => false,      //是否返回识别结果中每一行的置信度
]);

```
<a name="baidu-webimage"></a>
#### 网络图片文字识别

```php
$app->baidu->webimage($file, [
    'detect_direction'      => false,      //是否检测图像朝向
    'detect_language'       => false,      //是否检测语言，默认不检测
]);
```
<a name="baidu-idcard"></a>
#### 身份证识别

```php
$app->baidu->idcard($file, [
    'detect_direction'      => false,      //是否检测图像朝向
    'id_card_side'          => 'front',    //front：身份证正面；back：身份证背面 （注意，该参数必选）
    'detect_risk'           => false,      //是否开启身份证风险类型功能，默认false
]);
```
<a name="baidu-bankcard"></a>
#### 银行卡识别

```php
$app->baidu->bankcard($file, []);          //无参数
```
<a name="baidu-drivingLicense"></a>
#### 驾驶证识别

```php
$app->baidu->drivingLicense($file, [
    'detect_direction'      => false,      //是否检测图像朝向
]);
```

<a name="baidu-vehicleLicense"></a>
#### 行驶证识别

```php
$app->baidu->vehicleLicense($file, [
    'detect_direction'      => false,      //是否检测图像朝向
    'accuracy'              => 'normal'    // normal 使用快速服务，1200ms左右时延,缺省或其它值使用高精度服务，1600ms左右时延
]);
```

<a name="baidu-licensePlate"></a>
#### 车牌识别

```php
$app->baidu->licensePlate($file, [
    'multi_detect'          => false,      //是否检测多张车牌，默认为false
]);
```

<a name="baidu-businessLicense"></a>
#### 营业执照识别

```php
$app->baidu->businessLicense($file, []);   //无参数
```

<a name="baidu-receipt"></a>
#### 通用票据识别

```php
$app->baidu->receipt($file, [
    'recognize_granularity' => 'big',      //是否定位单字符位置
    'probability'           => false,      //是否返回识别结果中每一行的置信度
    'accuracy'              => 'normal'    // normal 使用快速服务，1200ms左右时延,缺省或其它值使用高精度服务，1600ms左右时延
    'detect_direction'      => false,      //是否检测图像朝向
]);
```

<a name="aliyun-ocr"></a>
## [Aliyun OCR](https://data.aliyun.com/product/ocr)

目前采用 `APPCODE` 作为 `API` 认证方式，[查看我的APPCODE](https://market.console.aliyun.com/imageconsole/index.htm)

```php
use Godruoyi\OCR\Application;

$app = new Application([
    'ocrs' => [
        'aliyun' => [
            'appcode' => '40bc103c7fe6417b87152f6f68bead2f',
        ]
    ]
]);
```

> 阿里云OCR不支持在线图片地址

<a name="aliyun-idcard"></a>
#### 身份证识别

```php
$app->aliyun->idcard($file, [
    'side'                  => 'face',     //身份证正反面类型:face/back
]);
```

<a name="aliyun-vehicle"></a>
#### 行驶证识别

```php
$app->aliyun->vehicle($file, []);          //无可选参数
```

<a name="aliyun-driverLicense"></a>
#### 驾驶证识别

```php
$app->aliyun->driverLicense($file, [
    'side'                  => 'face',     //驾驶证首页/副页:face/back
]);

```
<a name="aliyun-shopSign"></a>
#### 门店识别

```php
$app->aliyun->shopSign($file, []);         //无可选参数
```

<a name="aliyun-english"></a>
#### 英文识别

```php
$app->aliyun->english($file, []);          //无可选参数
```

<a name="aliyun-businessLicense"></a>
#### 营业执照识别

```php
$app->aliyun->businessLicense($file, []);  //无可选参数
```

<a name="aliyun-bankCard"></a>
#### 银行卡识别

```php
$app->aliyun->bankCard($file, []);         //无可选参数
```

<a name="aliyun-businessCard"></a>
#### 名片识别

```php
$app->aliyun->businessCard($file, []);     //无可选参数
```

<a name="aliyun-trainTicket"></a>
#### 火车票识别

```php
$app->aliyun->trainTicket($file, []);      //无可选参数
```

<a name="aliyun-vehiclePlate"></a>
#### 车牌识别

```php
$app->aliyun->vehiclePlate($file, [
    'multi_crop'            => false,     //当设成true时,会做多crop预测，只有当多crop返回的结果一致，并且置信度>0.9时，才返回结果
]);

```

<a name="aliyun-general"></a>
#### 通用文字识别

```php
$app->aliyun->general($file, [
    'min_size'              => 16,        //图片中文字的最小高度，
    'output_prob'           => false,     //是否输出文字框的概率，
]);
```

<a name="tencent-ocr"></a>
## [Tencent OCR](https://cloud.tencent.com/product/ocr)

> 可登录 [云API密钥控制台](https://console.cloud.tencent.com/capi)查看你的[个人 API 密钥](https://console.cloud.tencent.com/capi)

```php
use Godruoyi\OCR\Application;

$app = new Application([
    'ocrs' => [
        'tencent' => [
            'app_id' => '1254032478',
            'secret_id' => 'AKIDzODdB1nOELz0T8CEjTEkgKJOob3t2Tso',
            'secret_key' => '6aHHkz236LOYu0nRuBwn5PwT0x3km7EL',
            'bucket' => 'test1'
        ],
    ]
]);
```

> Tencent OCR 暂不支持在线图片地址

<a name="tencent-namecard"></a>
#### 名片识别

```php
$app->tencent->namecard($file, [
    'ret_image'             => 0,        //0 不返回图片，1 返回图片，
]);
```
<a name="tencent-idcard"></a>
#### 身份证识别

```php
$app->tencent->idcard($file, [
    'card_type'             => 0,        //0 为身份证有照片的一面，1为身份证有国徽的一面
]);
```
<a name="tencent-drivingLicence"></a>
#### 行驶证驾驶证识别

```php
$app->tencent->drivingLicence($file, [
    'type'                  => 0,        //识别类型，0表示行驶证，1表示驾驶证，
]);
```
<a name="tencent-general"></a>
#### 通用印刷体识别

```php
$app->tencent->general($file, []);       //无可选参数
```
<a name="tencent-bankcard"></a>
#### 银行卡识别

```php
$app->tencent->bankcard($file, []);      //无可选参数
```

<a name="tencent-plate"></a>
#### 车牌号识别

```php
$app->tencent->plate($file, []);         //无可选参数
```

<a name="tencent-bizlicense"></a>
#### 营业执照识别

```php
$app->tencent->bizlicense($file, []);    //无可选参数
```
<a name="tencent-ocr-for-ai"></a>
## [Tencent OCR For AI](https://ai.qq.com/product/ocr.shtml#identify)

> 可登录 [腾讯 AI 控制台](https://ai.qq.com/cgi-bin/console_overview)查看你的[个人 APP_ID 及 APP_KEY](https://ai.qq.com/cgi-bin/console_overview)

```php
use Godruoyi\OCR\Application;

$app = new Application([
    'ocrs' => [
        'tencentai' => [
          'app_id' => '1106584682',
          'app_key' => 'XGgkqVif73v8wH6W',
        ],
    ]
]);
```

<a name="tencent-idcard-for-ai"></a>
#### 身份证识别

```php
$app->tencentai->idcard($file, [
    'card_type'             => 0,          //0 为身份证有照片的一面，1为身份证有国徽的一面 默认0
]);
```

<a name="tencent-namecard-for-ai"></a>
#### 名片识别

```php
$app->tencentai->namecard($file, []);
```

<a name="tencent-driverlicen-for-ai"></a>
#### 行驶证驾驶证识别

```php
$app->tencentai->driverlicen($file, [
    'type'                  => 0,          //识别类型，0表示行驶证，1表示驾驶证，默认0
]);
```

<a name="tencent-bankcard-for-ai"></a>
#### 银行卡识别

```php
$app->tencentai->bankcard($file, []);      //无可选参数
```

<a name="tencent-general-for-ai"></a>
#### 通用印刷体识别

```php
$app->tencentai->general($file, []);       //无可选参数
```

<a name="tencent-bizlicense-for-ai"></a>
#### 营业执照识别

```php
$app->tencentai->bizlicense($file, []);    //无可选参数
```

MIT
