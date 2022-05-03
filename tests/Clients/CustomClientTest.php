<?php

/*
 * This file is part of the godruoyi/ocr.
 *
 * (c) Godruoyi <gmail@godruoyi.com>
 *
 * This source file is subject to the MIT license that is bundled.
 */

namespace Test\Clients;

use Test\Custom\HuaweiClient;
use Test\TestCase;

class CustomClientTest extends TestCase
{
    public function testCustom()
    {
        $this->application->extend('huawei', function ($container) {
            return $container->make(HuaweiClient::class);
        });

        // Godruoyi\OCR\Support\Response
        $response = $this->application->huawei->idcard('', []);

        $this->assertSame('OK', $response->getBody()->getContents());
    }
}
