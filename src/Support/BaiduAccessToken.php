<?php

namespace Godruoyi\OCR\Support;

use GuzzleHttp\Exception\RequestException;
use Psr\Cache\CacheItemPoolInterface;

class BaiduAccessToken
{
    const API_URL = 'https://aip.baidubce.com/oauth/2.0/token';

    const CACHE_KEY = 'ocr.cache.baidu.access_token';

    protected $secretID;

    protected $secretKey;

    protected $http;

    protected $cache;

    public function __construct(Http $http, CacheItemPoolInterface $cache, string $secretID, string $secretKey)
    {
        $this->secretID = $secretID;
        $this->secretKey = $secretKey;
        $this->http = $http;
        $this->cache = $cache;
    }

    public function getAccessToken(): string
    {
        $cacheItem = $this->cache->getItem(self::CACHE_KEY);

        if ($cacheItem->isHit()) {
            return $cacheItem->get();
        }

        [$accessToken, $expiresIn] = $this->requestAccessToken();

        $cacheItem->set($accessToken)->expiresAfter($expiresIn - 10);
        $this->cache->save($cacheItem);

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
            throw new RequestException($response->getReasonPhrase(), null, $response);
        }

        $data = json_decode($response->getBody()->getContents(), true);

        return [
            $data['access_token'],
            $data['expires_in'],
        ];
    }
}