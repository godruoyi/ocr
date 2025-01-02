<?php

/*
 * This file is part of the godruoyi/ocr.
 *
 * (c) Godruoyi <gmail@godruoyi.com>
 *
 * This source file is subject to the MIT license that is bundled.
 */

namespace Test\Clients;

use BadMethodCallException;
use Godruoyi\OCR\Support\Response;
use Test\TestCase;

class BaseClientTest extends TestCase
{
    public function test_extend()
    {
        $this->application->aliyun->extend('hello', function ($request, $a, $b) {
            return new Response(200, [], $a + $b);
        });

        $response = $this->application->aliyun->hello(1, 2);

        $this->assertEquals('3', $response->getBody()->getContents());
    }

    public function test_call_not_exists_method()
    {
        $this->expectException(BadMethodCallException::class);

        $this->application->aliyun->hello(1, 2);
    }
}
