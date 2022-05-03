<?php

/*
 * This file is part of the godruoyi/ocr.
 *
 * (c) Godruoyi <gmail@godruoyi.com>
 *
 * This source file is subject to the MIT license that is bundled.
 */

namespace Test\Support;

use Godruoyi\OCR\Support\BaiduAccessToken;
use Godruoyi\OCR\Support\Response;
use RuntimeException;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Test\TestCase;

class BaiduAccessTokenTest extends TestCase
{
    public function testGetAccessToken()
    {
        $http = $this->mockeryHttp();
        $http->shouldReceive('json')
            ->once()
            ->andReturn(new Response(200, [], '{"access_token": "access_token1", "expires_in": 12}'));

        $http->shouldReceive('json')
            ->once()
            ->andReturn(new Response(200, [], '{"access_token": "access_token2", "expires_in": 12}'));

        $token = new BaiduAccessToken(
            $http,
            new FilesystemAdapter(time() . '_' . uniqid()),
            'secretID',
            'secretKey'
        );

        $this->assertSame('access_token1', $token->getAccessToken());
        $this->assertSame('access_token1', $token->getAccessToken());

        sleep(3);

        $this->assertSame('access_token2', $token->getAccessToken());
    }

    public function testGetAccessTokenFail()
    {
        $http = $this->mockeryHttp();
        $http->shouldReceive('json')
            ->once()
            ->andReturn(new Response(400, [], '{"error_code": 40001, "error_msg": "invalid parameter"}'));

        $token = new BaiduAccessToken(
            $http,
            new FilesystemAdapter(time() . '_' . uniqid()),
            'secretID',
            'secretKey'
        );

        $this->expectException(RuntimeException::class);

        $token->getAccessToken();
    }
}
