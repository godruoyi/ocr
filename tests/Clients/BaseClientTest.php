<?php

namespace Test\Clients;

use BadMethodCallException;
use Godruoyi\OCR\Support\Response;
use Test\TestCase;

class BaseClientTest extends TestCase
{
    public function testExtend()
    {
        $this->application->aliyun->extend('hello', function ($request, $a, $b) {
            return new Response(200, [], $a + $b);
        });

        $response = $this->application->aliyun->hello(1, 2);

        $this->assertEquals(3, $response->getBody()->getContents());
    }

    public function testCallNotExistsMethod()
    {
        $this->expectException(BadMethodCallException::class);

        $this->application->aliyun->hello(1, 2);
    }
}
