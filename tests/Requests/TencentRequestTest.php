<?php

/*
 * This file is part of the godruoyi/ocr.
 *
 * (c) Godruoyi <gmail@godruoyi.com>
 *
 * This source file is subject to the MIT license that is bundled.
 */

namespace Test\Requests;

use Godruoyi\OCR\Requests\TencentRequest;
use Godruoyi\OCR\Support\Response;
use Test\TestCase;

class TencentRequestTest extends TestCase
{
    public function testSupportOnlineImages()
    {
        $http = $this->mockeryHttp();

        // fire ocr request
        $http->shouldReceive('json')
            ->once()
            ->withArgs(function ($url, $body, $query, $options) {
                return $options['headers']['X-TC-Action'] = 'action'
                    && $body['ImageUrl'] = 'https://example.com/image.jpg';
            })
            ->andReturn(new Response(200, [], 'OK'));

        $request = new TencentRequest($http, $this->application->getContainer());

        $response = $request->send('action', 'https://example.com/image.jpg');

        $this->assertSame('OK', $response->getBody()->getContents());
    }
}
