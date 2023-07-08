<?php

/*
 * This file is part of the godruoyi/ocr.
 *
 * (c) Godruoyi <gmail@godruoyi.com>
 *
 * This source file is subject to the MIT license that is bundled.
 */

namespace Test\Clients;

use Godruoyi\OCR\Clients\AliyunClient;
use Godruoyi\OCR\Requests\AliyunRequest;
use Godruoyi\OCR\Support\Http;
use Godruoyi\OCR\Support\Response;
use Mockery;
use Psr\Http\Message\ResponseInterface;
use Test\TestCase;

class AliyunClientTest extends TestCase
{
    public function testClient()
    {
        $c = $this->application->aliyun;

        $this->assertInstanceOf(AliyunClient::class, $c);
    }

    public function testBasic()
    {
        $methods = [
            'idcard',
            'vehicle',
            'driverLicense',
            'businessLicense',
            'bankCard',
            'businessCard',
            'passport',
            'tableParse',
            'vin',
            'trainTicket',
            'vehiclePlate',
            'general',
            'generalAdvanced',
            'invoice',
            'houseCert',
            'document',
            'ugc',
            'custom',
            'ecommerce',
        ];

        $times = count($methods);
        $app = $this->application->getContainer();
        $app->bind(AliyunRequest::class, function () use ($times) {
            $mockRequest = Mockery::mock('Request, '.AliyunRequest::class);
            $mockRequest->shouldReceive('send')
                ->times($times)
                ->andReturn(new Response(200, [], 'SUCCESS'));

            return $mockRequest;
        });

        foreach ($methods as $method) {
            $response = $this->application->aliyun->$method('img', ['a' => 1]);
            $this->assertInstanceOf(ResponseInterface::class, $response);
            $response->getBody()->rewind();

            $this->assertSame('SUCCESS', $response->getBody()->getContents());
        }
    }

    public function testManyImage()
    {
        $app = $this->application->getContainer();

        $app->bind(AliyunRequest::class, function ($app) {
            $http = \Mockery::mock('http, '.Http::class);
            $http->shouldReceive('middlewares')->andReturn(null);
            $http->shouldReceive('json')
                ->once()
                ->with(\Mockery::andAnyOthers(), \Mockery::on(function ($data) {
                    return is_array($data)
                        && array_key_exists('image', $data)
                        && array_key_exists('configure', $data);
                }))
                ->andReturn(new \GuzzleHttp\Psr7\Response(200, [], 'OK'));

            return new AliyunRequest($http, $app);
        });

        $response = $this->application->aliyun->general([__DIR__.'/../stubs/common.png', __DIR__.'/stubs/common2.png', __DIR__.'/stubs/common2.png']);

        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertSame('OK', $response->getBody()->getContents());
    }
}
