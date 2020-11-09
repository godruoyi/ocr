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

interface Client
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
     * @return array
     */
    public function request($url, $images, array $options = []): Response;
}
