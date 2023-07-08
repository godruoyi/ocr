<?php

/*
 * This file is part of the godruoyi/ocr.
 *
 * (c) Godruoyi <gmail@godruoyi.com>
 *
 * This source file is subject to the MIT license that is bundled.
 */

namespace Test\Custom;

use Godruoyi\OCR\Contracts\Client;
use Godruoyi\OCR\Support\Response;
use Psr\Http\Message\ResponseInterface;

class HuaweiClient implements Client
{
    public function idcard($url, $images, array $options = []): ResponseInterface
    {
        // 做你自己的业务逻辑

        return $this->request($url, $images, $options);
    }

    /**
     * Fire a ocr http request.
     *
     * @param  string  $url
     * @param  mixed  $images
     */
    public function request($url, $images, array $options = []): ResponseInterface
    {
        // mock response, change to your own response
        return new Response(200, [], 'OK');
    }
}
