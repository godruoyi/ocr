<?php

/*
 * This file is part of the godruoyi/ocr.
 *
 * (c) Godruoyi <gmail@godruoyi.com>
 *
 * This source file is subject to the MIT license that is bundled.
 */

namespace Godruoyi\OCR\Support;

use InvalidArgumentException;
use Psr\Http\Message\RequestInterface;

class TencentSignatureV3
{
    public const TC3_ALGORITHM = 'TC3-HMAC-SHA256';

    public const TC3_REQUEST = 'tc3_request';

    /**
     * @var string
     */
    protected $secretId;

    /**
     * @var string
     */
    protected $secretKey;

    /**
     * The request header that participates in the signature.
     *
     * @var array
     */
    protected $signatureHeaders = [
        'Content-Type', 'Host',
    ];

    /**
     * Registe config.
     *
     * @param array $configs
     */
    public function __construct(string $secretId, string $secretKey)
    {
        $this->secretId = $secretId;
        $this->secretKey = $secretKey;
    }

    /**
     * @return string
     */
    public function hashedRequestPayload(string $body)
    {
        return hash('SHA256', $body);
    }

    /**
     * Canonical Request.
     *
     * @return string
     */
    public function canonicalRequest(RequestInterface $request)
    {
        $httpRequestMethod = strtoupper($request->getMethod());
        $canonicalURI = '/';
        $canonicalQueryString = ''; // $request->getUri()->getQuery();

        $signatureHeaders = [];
        foreach ($this->signatureHeaders as $h) {
            $signatureHeaders[$h] = current($request->getHeader($h));
        }

        $canonicalHeaders = Encoder::getCanonicalHeaders($signatureHeaders);
        $signedHeaders = $this->getSignatureHeadersToString();
        $hashedRequestPayload = $this->hashedRequestPayload($request->getBody()->getContents());

        return $httpRequestMethod . "\n" .
            $canonicalURI . "\n" .
            $canonicalQueryString . "\n" .
            $canonicalHeaders . "\n\n" .
            $signedHeaders . "\n" .
            $hashedRequestPayload;
    }

    /**
     * Get Athorization.
     *
     * @return string
     */
    public function authorization(RequestInterface $request)
    {
        $xTcTimestamp = current($request->getHeader('X-TC-Timestamp'));
        $host = current($request->getHeader('Host'));

        if (empty($xTcTimestamp) || empty($host)) {
            throw new InvalidArgumentException('Request header Host or X-TC-Timestamp is empty, please check.');
        }

        $date = gmdate('Y-m-d', $xTcTimestamp);
        $service = explode('.', $host)[0];
        $credentialScope = sprintf('%s/%s/%s', $date, $service, self::TC3_REQUEST);
        $str2sign = sprintf("%s\n%s\n%s\n%s", self::TC3_ALGORITHM, $xTcTimestamp, $credentialScope, hash('SHA256', $this->canonicalRequest($request)));

        $dateKey = hash_hmac('SHA256', $date, 'TC3' . $this->secretKey, true);
        $serviceKey = hash_hmac('SHA256', $service, $dateKey, true);
        $reqKey = hash_hmac('SHA256', self::TC3_REQUEST, $serviceKey, true);

        $signature = hash_hmac('SHA256', $str2sign, $reqKey);

        return sprintf(
            '%s Credential=%s/%s, SignedHeaders=%s, Signature=%s',
            self::TC3_ALGORITHM,
            $this->secretId,
            $credentialScope,
            $this->getSignatureHeadersToString(),
            $signature
        );
    }

    /**
     * Get Signature Headers.
     *
     * @return string
     */
    public function getSignatureHeadersToString()
    {
        return join(';', array_map(function ($h) {
            return strtolower($h);
        }, $this->signatureHeaders));
    }
}
