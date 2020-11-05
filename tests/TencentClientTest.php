<?php

namespace Test;

use Godruoyi\OCR\Application;
use Godruoyi\OCR\Config;
use Godruoyi\OCR\Support\Response;
use Godruoyi\OCR\Clients\TencentClient;
use Godruoyi\OCR\Contracts\Request;
use Godruoyi\OCR\Requests\TencentRequest;

class TencentClientTest extends TestCase
{
    public function testBasic()
    {
        $tencent = $this->application->tencent;
        $request = $tencent->getRequest();

        $this->assertInstanceOf(TencentClient::class, $tencent);
        $this->assertInstanceOf(TencentRequest::class, $request);
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
        $response = $this->application->tencent->idCard(__DIR__.'/stubs/idcard_0.jpeg', [
            'Region' => 'ap-shanghai'
        ]);

        $this->assertInstanceOf(Response::class, $response);
    }
}
