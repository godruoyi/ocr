<?php

/*
 * This file is part of the godruoyi/ocr.
 *
 * (c) Godruoyi <gmail@godruoyi.com>
 *
 * This source file is subject to the MIT license that is bundled.
 */

namespace Godruoyi\OCR\Support;

use GuzzleHttp\Client as HttpClient;
use Psr\Http\Message\ResponseInterface;

class Http
{
    /**
     * GuzzleHttp\Client Instance.
     *
     * @var \GuzzleHttp\Client
     */
    protected $client;

    /**
     * GuzzleHttp\Client Heades.
     *
     * @var array
     */
    protected $headers = [];

    /**
     * Http request middlewares.
     *
     * @var array
     */
    protected $middlewares = [];

    /**
     * Callback for http handler.
     *
     * @var callable
     */
    protected $handlerFun = [];

    /**
     * Guzzle client default settings.
     *
     * @var array
     */
    protected static $defaults = [
        'curl' => [
            CURLOPT_IPRESOLVE => CURL_IPRESOLVE_V4,
        ],

        'verify' => false,
    ];

    /**
     * Set Http Client Headers.
     */
    public function setHeaders(array $headers = [])
    {
        $this->headers = array_merge($this->headers, $headers);
    }

    /**
     * Send A Http Get Request.
     *
     * @param string $url
     * @param array $params
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function get($url, $params = [], array $options = [])
    {
        return $this->request('GET', $url, array_merge(['query' => $params], $options));
    }

    /**
     * Send A Http POST Request.
     *
     * @param string $url
     * @param array $params
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function post($url, $params = [], array $options = [])
    {
        $key = is_array($params) ? 'form_params' : 'body';

        return $this->request('POST', $url, array_merge([$key => $params], $options));
    }

    /**
     * Send A Http Request For GuzzleHttp Http Client.
     *
     * @param string $method
     * @param string $url
     * @param array $options
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function request($method, $url, $options = [])
    {
        $method = strtoupper($method);

        $options = array_merge_recursive(self::$defaults, ['headers' => $this->headers], $options);
        $handler = \GuzzleHttp\HandlerStack::create();

        foreach ($this->handlerFun as $fn) {
            is_callable($fn) && $fn($handler);
        }

        foreach ($this->middlewares as $m) {
            $handler->push($m['middleware'], $m['name']);
        }

        $options['handler'] = $handler;

        return $this->getClient()->request($method, $url, $options);
    }

    /**
     * JSON request.
     *
     * @param string $url
     * @param string|array $data
     * @param array $queries
     * @param int $encodeOption
     *
     * @return ResponseInterface
     *
     * @throws HttpException
     */
    public function json($url, $data = [], $queries = [], array $options = [], $encodeOption = JSON_UNESCAPED_UNICODE)
    {
        is_array($data) && $data = json_encode($data, $encodeOption);

        $this->setHeaders(['Content-Type' => 'application/json']);

        return $this->request('POST', $url, array_merge([
            'query' => $queries,
            'body' => $data,
        ], $options));
    }

    /**
     * Set http middleware.
     *
     * @return mixed
     */
    public function middlewares(callable $middleware, string $name = null)
    {
        $this->middlewares[] = compact('middleware', 'name');
    }

    /**
     * custom Http handler.
     *
     * @return mixed
     */
    public function customHttpHandler(callable $fu)
    {
        $this->handlerFun[] = $fu;

        return $this;
    }

    /**
     * Get GuzzleHttp\Client instance.
     *
     * @return \GuzzleHttp\Client
     */
    public function getClient()
    {
        if (empty($this->client) || !($this->client instanceof HttpClient)) {
            $this->client = new HttpClient();
        }

        return $this->client;
    }
}
