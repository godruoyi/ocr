<?php

/*
 * This file is part of the godruoyi/ocr.
 *
 * (c) Godruoyi <gmail@godruoyi.com>
 *
 * This source file is subject to the MIT license that is bundled.
 */

namespace Godruoyi\OCR\Contracts;

use Psr\Http\Message\ResponseInterface;

interface Request
{
    /**
     * Fire a ocr http request.
     *
     * @param string $url
     * @param mixed $images
     *
     * @throws \GuzzleHttp\Exception\RequestException
     */
    public function send($url, $images, array $options = []): ResponseInterface;
}
