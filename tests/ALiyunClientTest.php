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
use Godruoyi\OCR\Clients\AliyunClient;
use Godruoyi\OCR\Contracts\Request;
use Godruoyi\OCR\Support\Response;

class ALiyunClientTest extends TestCase
{
    public function testClient()
    {
        $c = $this->application->aliyun;

        $this->assertInstanceOf(AliyunClient::class, $c);
    }

    // public function testIdcard()
    // {
    //     $response = $this->application->aliyun->idcard([__DIR__.'/stubs/idcard_0.jpeg', __DIR__.'/stubs/idcard_0.jpeg']);

    //     $this->assertInstanceOf(Response::class, $response);
    //     $this->assertArrayHasKey('outputs', $response->toArray());

    //     $response = $this->application->aliyun->idcard(__DIR__.'/stubs/idcard_1.jpeg', ['side' => 'back']);
    //     $this->assertArrayHasKey('outputs', $response->toArray());
    // }

    public function testCustomMethod()
    {
        $this->application->aliyun->extend('aaa', function (Request $request, $a, $b) {
            // return $request->post(....); will auto return instance of \Godruoyi\OCR\Support\Response

            // test
            return new Response(200, [], $a.$b);
        });
        $response = $this->application->aliyun->aaa(1111, 222);
        $this->assertSame('1111222', (string) $response->getBody());

        // 2. you can custom return format.
        $response = $this->application->aliyun->extend('ccc', function (Request $request, $a, $b) {
            return $a.$b;
        })->ccc('a', 'b');
        $this->assertSame('ab', $response);

        // 3. test call unexists method.
        $this->expectException(\BadMethodCallException::class);
        $this->expectExceptionMessage(sprintf(
            'Method %s::%s does not exist.',
            AliyunClient::class,
            'ddd'
        ));
        $this->application->aliyun->ddd();
    }

    // public function testGeneral()
    // {
    //     $response = $this->application->aliyun->general(__DIR__.'/stubs/common.png');

    //     $this->assertInstanceOf(Response::class, $response);
    //     $this->assertArrayHasKey('outputs', $response->toArray());
    // }

    // public function testVehiclePlate()
    // {
    //     $response = $this->application->aliyun->vehiclePlate(__DIR__.'/stubs/chepai.png');

    //     $this->assertInstanceOf(Response::class, $response);
    //     $this->assertArrayHasKey('outputs', $response->toArray());
    // }

    // public function testTableParse()
    // {
    //     // support online
    //     $response = $this->application->aliyun->tableParse('https://media.prod.mdn.mozit.cloud/attachments/2017/01/23/14583/5bad217718ecd469850752f2d97b1137/numbers-table.png');

    //     $this->assertInstanceOf(Response::class, $response);
    //     $this->assertArrayHasKey('outputs', $response->toArray());

    //     var_dump($response->toArray());
    // }

    // public function testGeneralAdvanced()
    // {
    //     $response = $this->application->aliyun->generalAdvanced(__DIR__.'/stubs/common.png');

    //     $this->assertInstanceOf(Response::class, $response);
    //     $this->assertArrayHasKey('sid', $response->toArray());

    //     $response = $this->application->aliyun->generalAdvanced('https://img.alicdn.com/tfs/TB1de9cvHuWBuNjSszgXXb8jVXa-900-2767.jpg');
    //     $this->assertInstanceOf(Response::class, $response);
    //     $this->assertArrayHasKey('sid', $response->toArray());
    // }

    // public function testInvoice()
    // {
    //     $response = $this->application->aliyun->generalAdvanced('https://gw.alipayobjects.com/os/f/cms/images/jfjlq6o1/69a2ef99-c121-4fc8-a358-c1521f1696a8_w1157_h748.png');

    //     $this->assertInstanceOf(Response::class, $response);
    //     $this->assertArrayHasKey('sid', $response->toArray());
    // }

    public function testManyImage()
    {
        $response = $this->application->aliyun->general([__DIR__.'/stubs/common.png', __DIR__.'/stubs/common.png', __DIR__.'/stubs/common.png']);
    }
}
