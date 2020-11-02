<?php

namespace Godruoyi\OCR\Contracts;

use Godruoyi\OCR\Support\Response;

interface Request
{
    /**
     * Fire a ocr http request.
     *
     * @param  string $url
     * @param  mixed $images
     * @param  array  $options
     *
     * @throws \GuzzleHttp\Exception\RequestException
     *
     * @return array
     */
    public function request($url, $images, array $options = []) : Response;
}
