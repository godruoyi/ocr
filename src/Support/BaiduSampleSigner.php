<?php

/*
 * This file is part of the godruoyi/ocr.
 *
 * (c) Godruoyi <gmail@godruoyi.com>
 *
 * This source file is subject to the MIT license that is bundled.
 */

namespace Godruoyi\OCR\Support;

class BaiduSampleSigner
{
    public const BCE_AUTH_VERSION = 'bce-auth-v1';

    public const BCE_PREFIX = 'x-bce-';

    public const EXPIRATION_IN_SECONDS = 'expirationInSeconds';

    public const HEADERS_TO_SIGN = 'headersToSign';

    public const TIMESTAMP = 'timestamp';

    public const DEFAULT_EXPIRATION_IN_SECONDS = 1800;

    public const MIN_EXPIRATION_IN_SECONDS = 300;

    public const MAX_EXPIRATION_IN_SECONDS = 129600;

    protected $accessKeyId;

    protected $secretAccessKey;

    public function __construct($accessKeyId, $secretAccessKey)
    {
        $this->accessKeyId = $accessKeyId;
        $this->secretAccessKey = $secretAccessKey;
    }

    /**
     * Get sign to header.
     *
     * @return array
     */
    public function getAuthorizationHeader()
    {
        return ['Authorization' => $this->sign(...func_get_args())];
    }

    /**
     * 加密.
     *
     * @param string $httpMethod request method
     * @param string $path       request uri
     * @param array  $params     request params
     * @param array  $headers    sign headers
     *
     * @return string
     */
    public function sign(
        string $httpMethod,
        string $path,
        array $headers = [],
        array $querys = [],
        array $options = []
    ) {
        $authString = $this->getAuthString($options);
        $signingKey = hash_hmac('sha256', $authString, $this->secretAccessKey);

        $canonicalURI = Encoder::getCanonicalURIPath($path);
        $canonicalQueryString = Encoder::getCanonicalQueryString($querys);
        $canonicalHeader = Encoder::getCanonicalHeaders($headers);

        // 组成标准请求串
        $canonicalRequest = "$httpMethod\n$canonicalURI\n"
            ."$canonicalQueryString\n$canonicalHeader";

        // 使用signKey和标准请求串完成签名
        $signature = hash_hmac('sha256', $canonicalRequest, $signingKey);

        $signedHeaders = '';
        $headerKeys = array_keys($headers);
        sort($headerKeys);

        if (!empty($headers)) {
            $signedHeaders = strtolower(trim(implode(';', $headerKeys)));
        }

        // 组成最终签名串
        $authorizationHeader = "$authString/$signedHeaders/$signature";

        return $authorizationHeader;
    }

    /**
     * Get auth string.
     *
     * @return string
     */
    public function getAuthString(array $options = [])
    {
        $timestamp = new \DateTime();
        $timestamp->setTimezone(new \DateTimeZone('UTC'));

        $expirationInSeconds = !isset($options[self::EXPIRATION_IN_SECONDS])
            ? self::DEFAULT_EXPIRATION_IN_SECONDS
            : $options[self::EXPIRATION_IN_SECONDS];

        return self::BCE_AUTH_VERSION.'/'.$this->accessKeyId.'/'
            .$timestamp->format("Y-m-d\TH:i:s\Z").'/'.$expirationInSeconds;
    }
}
