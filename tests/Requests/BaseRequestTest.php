<?php

/*
 * This file is part of the godruoyi/ocr.
 *
 * (c) Godruoyi <gmail@godruoyi.com>
 *
 * This source file is subject to the MIT license that is bundled.
 */

namespace Test\Requests;

use Godruoyi\Container\ContainerInterface;
use Godruoyi\OCR\Requests\BaiduRequest;
use Godruoyi\OCR\Support\BaiduAccessToken;
use Godruoyi\OCR\Support\FileConverter;
use Godruoyi\OCR\Support\Response;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\Cache\Psr16Cache;
use Test\TestCase;

class BaseRequestTest extends TestCase
{
    public function test_url_auto2_base64()
    {
        $http = $this->mockeryHttp();

        // access token request
        $http->shouldReceive('json')
            ->once()
            ->andReturn(new Response(200, [], '{"access_token": "access_token", "expires_in": 7200}'));

        // auto get image content request.
        $http->shouldReceive('get')
            ->andReturn(new Response(200, [], 'image_content'));

        // fire ocr request
        $http->shouldReceive('post')
            ->once()
            ->withArgs(function ($url, $options) {
                return $url == 'url?access_token=access_token' && $options['image'] == base64_encode('image_content');
            })
            ->andReturn(new Response(200, [], 'OK'));

        // set auto get image content request use this http client.
        FileConverter::setHttp($http);

        $accessToken = new BaiduAccessToken(
            $http,
            new Psr16Cache(new FilesystemAdapter(time())), // set random cache dir
            'access_key',
            'secret_key'
        );

        $request = new BaiduRequest($http, $this->application->getContainer());
        $request->setAccessToken($accessToken);

        $response = $request->send('url', 'https://example.com/image.jpg', [
            '_urlauto2base64' => true,
        ]);

        $this->assertSame('OK', $response->getBody()->getContents());
    }

    public function test_support_online_inamge()
    {
        $http = $this->mockeryHttp();

        // access token request
        $http->shouldReceive('json')
            ->once()
            ->andReturn(new Response(200, [], '{"access_token": "access_token", "expires_in": 7200}'));

        // fire ocr request
        $http->shouldReceive('post')
            ->once()
            ->withArgs(function ($url, $options) {
                return $url == 'url?access_token=access_token' && $options['url'] == 'https://example.com/image.jpg';
            })
            ->andReturn(new Response(200, [], 'OK'));

        $accessToken = new BaiduAccessToken(
            $http,
            new Psr16Cache(new FilesystemAdapter(time().'_'.uniqid())), // set random cache dir
            'access_key',
            'secret_key'
        );

        $request = new BaiduRequest($http, $this->application->getContainer());
        $request->setAccessToken($accessToken);
        $this->assertInstanceOf(BaiduAccessToken::class, $request->getAccessToken());

        $response = $request->send('url', 'https://example.com/image.jpg');

        $this->assertSame('OK', $response->getBody()->getContents());

        $this->assertInstanceOf(ContainerInterface::class, $request->getApp());
    }
}
