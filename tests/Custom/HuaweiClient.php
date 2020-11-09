<?php

/*
 * This file is part of the godruoyi/ocr.
 *
 * (c) Godruoyi <gmail@godruoyi.com>
 *
 * This source file is subject to the MIT license that is bundled.
 */

namespace Test;

use Godruoyi\OCR\Contracts\Client;
use Godruoyi\OCR\Support\Response;

class HuaweiClient implements Client
{
    public function idcard($url, $images, array $options = [])
    {
        // 做你自己的业务逻辑

        return $this->request($url, $images, $options);
    }

    /**
     * Fire a ocr http request.
     *
     * @param string $url
     * @param mixed  $images
     * @param array  $options
     *
     * @throws \GuzzleHttp\Exception\RequestException
     *
     * @return array
     */
    public function request($url, $images, array $options = []): Response
    {
        $psrResponse = (new Http())->post();

        return Response::createFromGuzzleHttpResponse($psrResponse);
    }
}
