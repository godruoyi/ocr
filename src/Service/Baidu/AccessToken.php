<?php

/*
 * This file is part of the godruoyi/ocr.
 *
 * (c) godruoyi <godruoyi@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Godruoyi\OCR\Service\Baidu;

use RuntimeException;
use Godruoyi\OCR\Support\Http;
use Doctrine\Common\Cache\Cache;
use Doctrine\Common\Cache\FilesystemCache;

/**
 * @see  http://ai.baidu.com/docs#/Auth/top
 */
class AccessToken
{
    /**
     * Cache Instance
     *
     * @var \Doctrine\Common\Cache\Cache
     */
    protected $cache;

    /**
     * Baidu OCR APP_KEY
     *
     * @var string
     */
    protected $appKey;

    /**
     * Baidu OCR Secret_Key
     *
     * @var string
     */
    protected $secretKey;

    /**
     * Cache key, default [prefix.appKey]
     *
     * @var string
     */
    protected $cacheKey;

    /**
     * Http Instance
     *
     * @var \Godruoyi\OCR\Support\Http
     */
    protected $http;

    /**
     * Request URL Query Paramter name
     *
     * @var string
     */
    protected $queryName = 'access_token';

    /**
     * Default Cache Prefix
     *
     * @var string
     */
    protected $prefix = 'godruoyi.ocr.access_token';

    /**
     * Default Token success key
     *
     * @var string
     */
    protected $tokenSucessKey = 'access_token';

    const API_TOKEN_URI = 'https://aip.baidubce.com/oauth/2.0/token';

    /**
     * Initianl AccessToken
     *
     * @param string $appKey
     * @param string $secretKey
     */
    public function __construct($appKey, $secretKey, Cache $cache = null)
    {
        $this->appKey = $appKey;
        $this->secretKey = $secretKey;
        $this->cache = $cache;
    }

    /**
     * Get access token from baidu API.
     *
     * @param  boolean $forceRefresh
     *
     * @return string
     */
    public function getAccessToken($forceRefresh = false)
    {
        $cacheKey = $this->getCacheKey();
        $cached = $this->getCache()->fetch($cacheKey);

        if (empty($cached) || $forceRefresh) {
            $token = $this->getTokenFormApi();

            $this->getCache()->save($cacheKey, $token[$this->tokenSucessKey], $token['expires_in']);

            return $token[$this->tokenSucessKey];
        }

        return $cached;
    }

    /**
     * Get Token From Api.
     *
     * @throws  \Exception
     *
     * @return array
     */
    protected function getTokenFormApi()
    {
        $http = $this->getHttp();

        $token = $http->parseJson($http->post(self::API_TOKEN_URI, [
            'grant_type' => 'client_credentials',
            'client_id'  => $this->getAppKey(),
            'client_secret' => $this->getSecretKey()
        ]));

        if (empty($token[$this->tokenSucessKey])) {
            throw new RuntimeException('Request AccessToken fail. response: '.json_encode($token, JSON_UNESCAPED_UNICODE));
        }

        return $token;
    }

    /**
     * Set cache instance.
     *
     * @param \Doctrine\Common\Cache\Cache $cache
     *
     * @return AccessToken
     */
    public function setCache(Cache $cache)
    {
        $this->cache = $cache;

        return $this;
    }

    /**
     * Get Cache Instance
     *
     * @return Cache
     */
    public function getCache()
    {
        return $this->cache ?: $this->cache = new FilesystemCache(sys_get_temp_dir());
    }

    /**
     * Get Default Query Name
     *
     * @return string
     */
    public function getQueryName()
    {
        return $this->queryName;
    }

    /**
     * Set the query name.
     *
     * @param string $queryName
     *
     * @return $this
     */
    public function setQueryName($queryName)
    {
        $this->queryName = $queryName;

        return $this;
    }

    /**
     * Get Http client Instance
     *
     * @return Http
     */
    public function getHttp()
    {
        return $this->http ?: $this->http = new Http();
    }

    /**
     * Set the http instance.
     *
     * @param \Godruoyi\OCR\Support\Http $http
     *
     * @return $this
     */
    public function setHttp(Http $http)
    {
        $this->http = $http;

        return $this;
    }

    /**
     * Return the app key.
     *
     * @return string
     */
    public function getAppKey()
    {
        return $this->appKey;
    }

    /**
     * Return the secret key.
     *
     * @return string
     */
    public function getSecretKey()
    {
        return $this->secretKey;
    }

    /**
     * Set the access token prefix.
     *
     * @param string $prefix
     *
     * @return $this
     */
    public function setPrefix($prefix)
    {
        $this->prefix = $prefix;

        return $this;
    }

    /**
     * Set access token cache key.
     *
     * @param string $cacheKey
     *
     * @return $this
     */
    public function setCacheKey($cacheKey)
    {
        $this->cacheKey = $cacheKey;

        return $this;
    }

    /**
     * Get access token cache key.
     *
     * @return string $this->cacheKey
     */
    public function getCacheKey()
    {
        if (is_null($this->cacheKey)) {
            return $this->prefix.$this->appKey;
        }

        return $this->cacheKey;
    }
}
