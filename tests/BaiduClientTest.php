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
use Godruoyi\OCR\Clients\BaiduClient;
use Godruoyi\OCR\Support\Response;

class BaiduClientTest extends TestCase
{
    public function testBasic()
    {
        $baidu = $this->application->baidu;

        $this->assertInstanceOf(BaiduClient::class, $baidu);
    }

    // public function testGeneralBasic()
    // {
    //     $response = $this->application->baidu->generalBasic(__DIR__.'/stubs/common.png', [
    //         'language_type' => 'ENG',
    //     ]);

    //     $this->assertInstanceOf(Response::class, $response);
    // }

    // public function testGeneralBasicOnline()
    // {
    //     $response = $this->application->baidu->generalBasic('https://img.alicdn.com/tfs/TB1de9cvHuWBuNjSszgXXb8jVXa-900-2767.jpg');

    //     $this->assertInstanceOf(Response::class, $response);
    // }

    public function testQrcode()
    {
        $response = $this->application->baidu->qrcode('https://images.godruoyi.com/images/comments/2020/11/03/cc70a95e26261895aa523cc7a1fb3781.png');

        $this->assertInstanceOf(Response::class, $response);
    }

    // public function testVehicleCertificate()
    // {
    //     $response = $this->application->baidu->vehicleCertificate('http://5b0988e595225.cdn.sohucs.com/images/20180327/0648143f10ac40a29816ebc59b4187fc.jpeg');

    //     $this->assertInstanceOf(Response::class, $response);
    // }

    // public function testLottery()
    // {
    //     $response = $this->application->baidu->lottery('http://www.xinhuanet.com/caipiao/2019-04/11/1124352053_15549454512901n.jpg');

    //     $this->assertInstanceOf(Response::class, $response);
    // }
}
