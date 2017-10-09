<?php

/*
 * This file is part of the godruoyi/ocr.
 *
 * (c) godruoyi <godruoyi@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Godruoyi\OCR;

use Godruoyi\OCR\Support\Http;

abstract class AbstractAPI
{
    /**
     * Http Client Instance
     *
     * @var \Godruoyi\OCR\Support\Http
     */
    protected $httpClient;

    /**
     * Set Http Client
     *
     * @param Http $httpClient
     */
    public function setHttpClient(Http $httpClient)
    {
        $this->httpClient = $httpClient;

        return $this;
    }

    /**
     * Get Http Client Instance
     *
     * @return Http
     */
    public function getHttpClient()
    {
        if (is_null($this->httpClient)) {
            $this->httpClient = new Http();
        }

        return $this->httpClient;
    }

    /**
     * @param \Psr\Http\Message\ResponseInterface|string $body
     *
     * @return mixed
     *
     * @throws \Godruoyi\OCR\Exceptions\HttpException
     */
    public function toArray($body)
    {
        return $this->getHttpClient()->parseJson($body);
    }
}
