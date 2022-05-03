<?php

/*
 * This file is part of the godruoyi/ocr.
 *
 * (c) Godruoyi <gmail@godruoyi.com>
 *
 * This source file is subject to the MIT license that is bundled.
 */

namespace Test\Clients;

use Godruoyi\OCR\Support\Response;
use Test\TestCase;

class RealRequestClientTest extends TestCase
{
    public function testFireRealAliyunRequest()
    {
        $d = $this->application->aliyun;
        $response = $d->ugc(__DIR__ . '/../stubs/common.png', []);

        $this->assertInstanceOf(Response::class, $response);

        // If we get 403 error, it can be only that our quota is exhausted.
        if ($response->getStatusCode() == 403) {
            $this->assertSame("Api Market Subscription quota exhausted", $response->getHeader('X-Ca-Error-Message'));
            return;
        }

        $this->assertSame(200, $response->getStatusCode());

        $json = json_decode($response->getBody()->getContents(), true);
        $this->assertSame(JSON_ERROR_NONE, json_last_error());
        $this->assertIsArray($json);
    }

    public function testFireRealBaiduRequest()
    {
        $d = $this->application->baidu;
        $response = $d->businessCard(__DIR__ . '/../stubs/common.png', []);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertSame(200, $response->getStatusCode());

        $this->assertNotEmpty($response->toArray());
    }

    public function testFireRealTencentRequest()
    {
        $d = $this->application->tencent;
        $response = $d->generalBasic(__DIR__ . '/../stubs/common.png', [
            'Region' => 'ap-guangzhou',
        ]);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertSame(200, $response->getStatusCode());

        $this->assertNotEmpty($response->toArray());
    }
}
