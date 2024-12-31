<?php

/*
 * This file is part of the godruoyi/ocr.
 *
 * (c) Godruoyi <gmail@godruoyi.com>
 *
 * This source file is subject to the MIT license that is bundled.
 */

namespace Godruoyi\OCR\Support;

use Psr\SimpleCache\CacheInterface;
use RuntimeException;

class BaiduAccessToken
{
    const API_URL = 'https://aip.baidubce.com/oauth/2.0/token';

    const CACHE_KEY = 'ocr.cache.baidu.access_token';

    protected $secretID;

    protected $secretKey;

    protected $http;

    protected $cache;

    private $tokenKey;

    public function __construct(Http $http, CacheInterface $cache, string $secretID = null, string $secretKey = null)
    {
        $this->secretID = $secretID;
        $this->secretKey = $secretKey;
        $this->http = $http;
        $this->cache = $cache;
        $this->tokenKey = self::CACHE_KEY . '.' . $secretID;
    }

    public function getAccessToken(): string
    {
        $accessToken = $this->cache->get($this->tokenKey);

        if ($accessToken) {
            return $accessToken;
        }

        [$accessToken, $expiresIn] = $this->requestAccessToken();

        $this->cache->set($this->tokenKey, $accessToken, $expiresIn - 10);

        return $accessToken;
    }

    protected function requestAccessToken(): array
    {
        $response = $this->http->json(self::API_URL, [], [
            'grant_type' => 'client_credentials',
            'client_id' => $this->secretID,
            'client_secret' => $this->secretKey,
        ]);

        if ($response->getStatusCode() !== 200) {
            throw new RuntimeException("Can't get access token from Baidu OCR API, status code: {$response->getStatusCode()}. error: {$response->getBody()}");
        }

        $data = json_decode($response->getBody()->getContents(), true);

        return [
            $data['access_token'],
            $data['expires_in'],
        ];
    }
}
