<?php

/*
 * This file is part of the godruoyi/ocr.
 *
 * (c) godruoyi <godruoyi@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Godruoyi\OCR\Services;

use Godruoyi\OCR\Support\Http;

abstract class AbstractService
{
    protected $httpClient;

    protected $middlewares = [];

    /**
     * Compile filepath to base 64 encode
     *
     * @param  Resource|string|SplFileInfo $content
     *
     * @return string
     */
    public function fileToBase64Encode($content)
    {
        //@todo SplFileInfo

        if (is_resource($content)) {
            $content = stream_get_contents($content);
        } elseif (is_file($content) && file_exists($content)) {
            $content = file_get_contents($content);
        } elseif (is_string($content)) {
            $content = (string) $content;
        }

        return base64_encode($content);
    }

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
