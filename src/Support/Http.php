<?php

/*
 * This file is part of the godruoyi/ocr.
 *
 * (c) godruoyi <godruoyi@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Godruoyi\OCR\Support;

use GuzzleHttp\HandlerStack;
use Godruoyi\OCR\Support\Log;
use GuzzleHttp\Client as HttpClient;
use Psr\Http\Message\ResponseInterface;
use Godruoyi\OCR\Exceptions\HttpException;

class Http
{
    /**
     * GuzzleHttp\Client Instance
     *
     * @var \GuzzleHttp\Client
     */
    protected $client;

    /**
     * Guzzle client default settings.
     *
     * @var array
     */
    protected static $defaults = [
        'curl' => [
            CURLOPT_IPRESOLVE => CURL_IPRESOLVE_V4,
        ],
    ];

    /**
     * Send A Http Get Request
     *
     * @param  string $url
     * @param  array  $options
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function get($url, $params = [])
    {
        return $this->request('GET', $url, ['query' => $params]);
    }

    /**
     * Send A Http POST Request
     *
     * @param  string $url
     * @param  array  $params
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function post($url, $params = [])
    {
        $key = is_array($params) ? 'form_params' : 'body';

        return $this->request('POST', $url, [$key => $params]);
    }

    /**
     * Set Request Header
     *
     * @param array $headers
     *
     * @return  this
     */
    public function setHeaders(array $headers = [])
    {
        self::$defaults = array_merge(self::$defaults, ['headers' => $headers]);

        return $this;
    }

    /**
     * Send A Http Request For GuzzleHttp Http Client
     *
     * @param  string $method
     * @param  string $url
     * @param  array  $options
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function request($method, $url, $options = [])
    {
        $method = strtoupper($method);

        $options = array_merge(self::$defaults, $options);

        Log::debug('Http Request Origin:', compact('url', 'method', 'options'));

        $response = $this->getClient()->request($method, $url, $options);

        Log::debug('API response:', [
            'Status' => $response->getStatusCode(),
            'Reason' => $response->getReasonPhrase(),
            'Headers' => $response->getHeaders(),
            'Body' => strval($response->getBody()),
        ]);

        return $response;
    }

    /**
     * Get GuzzleHttp\Client instance.
     *
     * @return \GuzzleHttp\Client
     */
    public function getClient()
    {
        if (!($this->client instanceof HttpClient)) {
            $this->client = new HttpClient();
        }

        return $this->client;
    }

    /**
     * @param \Psr\Http\Message\ResponseInterface|string $body
     *
     * @return array
     *
     * @throws \Godruoyi\OCR\Exceptions\HttpException
     */
    public function parseJson($body)
    {
        if ($body instanceof ResponseInterface) {
            $body = $body->getBody();
        }

        if (empty($body)) {
            return false;
        }

        $contents = json_decode($body, true);

        Log::debug('API response decoded:', compact('contents'));

        if (JSON_ERROR_NONE !== json_last_error()) {
            throw new HttpException('Failed to parse JSON: '.json_last_error_msg());
        }

        return $contents;
    }
}
