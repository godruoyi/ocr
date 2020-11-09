<?php

/*
 * This file is part of the godruoyi/ocr.
 *
 * (c) Godruoyi <gmail@godruoyi.com>
 *
 * This source file is subject to the MIT license that is bundled.
 */

namespace Test;

use Godruoyi\OCR\Application;
use Godruoyi\OCR\Clients\TencentClient;
use Godruoyi\OCR\Support\Response;

class TencentClientTest extends TestCase
{
    public function testBasic()
    {
        $tencent = $this->application->tencent;

        $this->assertInstanceOf(TencentClient::class, $tencent);
    }

    // public function testGeneralBasic()
    // {
    //     $response = $this->application->tencent->generalBasic('https://img.alicdn.com/tfs/TB1de9cvHuWBuNjSszgXXb8jVXa-900-2767.jpg', [
    //     // $response = $this->application->tencent->generalBasic(__DIR__.'/stubs/common.png', [
    //         'Region' => 'ap-shanghai'
    //     ]);

    //     $this->assertInstanceOf(Response::class, $response);
    // }

    // public function testGeneralFast()
    // {
    //     $response = $this->application->tencent->generalBasic(__DIR__.'/stubs/idcard_0.jpeg', [
    //         'Region' => 'ap-shanghai'
    //     ]);

    //     $this->assertInstanceOf(Response::class, $response);
    // }

    public function testIdCard()
    {
        $response = $this->application->tencent->idCard(__DIR__.'/stubs/chepai.png', [
            'Region' => 'ap-shanghai',
        ]);

        // {"Response":{"Error":{"Code":"FailedOperation.OcrFailed","Message":"OCR识别失败"},"RequestId":"e99734d7-1540-496a-a343-b46760583c04"}}
        // {"Response":{"Name":"徐连波","Sex":"男","Nation":"苗","Birth":"1992/3/12","Address":"贵州省务川仡佬族苗族自治县黄都镇高洞村双龙组","IdNum":"522126199203121552","Authority":"","ValidDate":"","AdvancedInfo":"{}","RequestId":"97f72468-4d65-4e64-b13e-8d5b8fcbd2f7"}}

        // $this->assertInstanceOf(Response::class, $response);
        // $this->assertArrayHasKey('Response', $response->toArray());
        // $this->assertEquals('徐连波', $response['Response']['Name']);

        $body = $response->toArray();

        if (isset($body['Response']['Error']) && !empty($body['Response']['Error'])) {
            // 识别失败
            var_dump($body['Response']['Error']['Message']);
        } else {
            $idCard = $response['Response']['IdNum'];

            var_dump($idCard);
        }
    }

    // public function testVin()
    // {
    //     $response = $this->application->tencent->vin(__DIR__.'/stubs/chepai.png', [
    //         'Region' => 'ap-shanghai'
    //     ]);

    //     $this->assertInstanceOf(Response::class, $response);
    // }
}
