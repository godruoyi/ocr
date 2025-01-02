<?php

/*
 * This file is part of the godruoyi/ocr.
 *
 * (c) Godruoyi <gmail@godruoyi.com>
 *
 * This source file is subject to the MIT license that is bundled.
 */

namespace Test\Requests;

use Exception;
use Godruoyi\OCR\Application;
use Godruoyi\OCR\Requests\AliyunRequest;
use Godruoyi\OCR\Support\Response;
use InvalidArgumentException;
use Psr\Http\Message\ResponseInterface;
use Test\TestCase;

class AliyunRequestTest extends TestCase
{
    /**
     * @throws Exception
     */
    public function test_basic_send()
    {
        $app = new Application([
            'drivers' => [
                'aliyun' => [
                    'appcode' => 'appcode',
                ],
            ],
        ]);

        $http = $this->mockHttpWithResponse($this->createSuccessResponse());
        $request = new AliyunRequest($http, $app->getContainer());

        $response = $request->send('url', __DIR__.'/../stubs/common.png');

        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertSame('OK', $response->getBody()->getContents());
    }

    public function test_basic_send_enable_log()
    {
        $app = new Application([
            'drivers' => [
                'aliyun' => [
                    'appcode' => 'appcode',
                ],
            ],

            'log' => [
                'enable' => true,
                'default' => 'syslog',
            ],
        ]);

        $http = $this->mockHttpWithResponse($this->createSuccessResponse());

        $request = new AliyunRequest($http, $app->getContainer());
        $response = $request->send('url', __DIR__.'/../stubs/common.png');

        $this->assertInstanceOf(ResponseInterface::class, $response);
        $response->getBody()->rewind();
        $this->assertSame('OK', $response->getBody()->getContents());
    }

    public function test_can_not_use_secret_id_and_secret()
    {
        $app = new Application([
            'drivers' => [
                'aliyun' => [
                    'appcode' => 'appcode',
                    'secret_id' => 'not_empty',
                    'secret_key' => 'not_empty',
                ],
            ],
        ]);

        $http = $this->mockHttpWithResponse($this->createSuccessResponse());

        $request = new AliyunRequest($http, $app->getContainer());

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Aliyun AppKey and AppSecret has not be completed');

        $request->send('url', __DIR__.'/../stubs/common.png');
    }

    public function test_use_app_code()
    {
        $app = new Application([
            'drivers' => [
                'aliyun' => [
                    'appcode' => 'appcode',
                ],
            ],
        ]);

        $histories = [];
        $request = new AliyunRequest($app->getContainer()['http'], $app->getContainer());
        $this->mockHttpWithResponseAndHistory($request->getHttp(), $this->createSuccessResponse(), $histories);

        $response = $request->send('url', __DIR__.'/../stubs/common.png');

        $this->assertSame('APPCODE appcode', $histories[0]['request']->getHeaderLine('Authorization'));
        $this->assertSame('OK', $response->getBody()->getContents());
    }

    public function test_invalid_images_should_throw_a_error()
    {
        $this->expectException(InvalidArgumentException::class);

        $request = new AliyunRequest($this->application->getContainer()['http'], $this->application->getContainer());
        $request->send('url', []);
    }

    public function test_invalid_format_type()
    {
        $this->expectException(InvalidArgumentException::class);

        $request = new AliyunRequest($this->application->getContainer()['http'], $this->application->getContainer());
        $request->send('url', [], ['_format' => 'invalid']);
    }

    public function test_format_inputs_is_url_image()
    {
        $http = $this->mockeryHttp();
        $http->shouldReceive('json')
            ->withArgs(function ($url, $options) {
                return $url == 'url' && $options['inputs'][0]['image']['dataValue'] == 'https://example.com';
            })
            ->andReturn(new Response(200, [], 'OK'));

        $request = new AliyunRequest($http, $this->application->getContainer());
        $response = $request->send('url', 'https://example.com');

        $this->assertSame('OK', $response->getBody()->getContents());
    }

    public function test_format_inputs()
    {
        $http = $this->mockeryHttp();
        $http->shouldReceive('json')
            ->withArgs(function ($url, $options) {
                $image = $options['inputs'][0]['image']['dataValue'];

                return $url == 'url' && is_string($image) && ! empty($image);
            })
            ->andReturn(new Response(200, [], 'OK'));

        $request = new AliyunRequest($http, $this->application->getContainer());
        $response = $request->send('url', [__DIR__.'/../stubs/common.png']);

        $this->assertSame('OK', $response->getBody()->getContents());
    }

    public function test_format_basic()
    {
        $http = $this->mockeryHttp();
        $http->shouldReceive('json')
            ->withArgs(function ($url, $options) {
                $image = $options['image'];

                return $url == 'url' && is_string($image) && ! empty($image);
            })
            ->andReturn(new Response(200, [], 'OK'));

        $request = new AliyunRequest($http, $this->application->getContainer());
        $response = $request->send('url', [__DIR__.'/../stubs/common.png'], ['_format' => 'basic']);

        $this->assertSame('OK', $response->getBody()->getContents());
    }

    public function test_format_basic_is_url_image()
    {
        $http = $this->mockeryHttp();
        $http->shouldReceive('json')
            ->withArgs(function ($url, $options) {
                return $url == 'url' && $options['image'] == 'https://example.com';
            })
            ->andReturn(new Response(200, [], 'OK'));

        $request = new AliyunRequest($http, $this->application->getContainer());
        $response = $request->send('url', ['https://example.com'], ['_format' => 'basic']);

        $this->assertSame('OK', $response->getBody()->getContents());
    }

    public function test_format_img_or_url()
    {
        $http = $this->mockeryHttp();
        $http->shouldReceive('json')
            ->withArgs(function ($url, $options) {
                return $url == 'url' && $options['url'] == 'https://example.com';
            })
            ->once()
            ->andReturn(new Response(200, [], 'OK'));

        $http->shouldReceive('json')
            ->withArgs(function ($url, $options) {
                return $url == 'url' && is_string($options['img']) && ! empty($options['img']);
            })
            ->once()
            ->andReturn(new Response(200, [], 'OK'));

        $request = new AliyunRequest($http, $this->application->getContainer());

        $response = $request->send('url', ['https://example.com'], ['_format' => 'imgorurl']);
        $this->assertSame('OK', $response->getBody()->getContents());

        $response = $request->send('url', [__DIR__.'/../stubs/common.png'], ['_format' => 'imgorurl']);
        $this->assertSame('OK', $response->getBody()->getContents());
    }
}
