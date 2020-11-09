<?php

/*
 * This file is part of the godruoyi/ocr.
 *
 * (c) Godruoyi <gmail@godruoyi.com>
 *
 * This source file is subject to the MIT license that is bundled.
 */

namespace Godruoyi\OCR\Contracts;

use Godruoyi\OCR\Support\Response;

interface Request
{
    /**
     * Fire a ocr http request.
     *
     * @param string $url
     * @param mixed  $images
     * @param array  $options
     *
     * @throws \GuzzleHttp\Exception\RequestException
     *
     * @return \Godruoyi\OCR\Support\Response
     */
    public function send($url, $images, array $options = []): Response;
}
