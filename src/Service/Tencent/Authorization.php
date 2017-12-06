<?php

namespace Godruoyi\OCR\Service\Tencent;

/**
 * @see https://cloud.tencent.com/document/product/460/6968
 *
 */
class Authorization
{
    /**
     * AppId
     *
     * @var string
     */
    protected $appId;

    /**
     * Secret ID
     *
     * @var string
     */
    protected $secretId;

    /**
     * Bucket
     *
     * @var string
     */
    protected $bucket;

    /**
     * Secret Key
     *
     * @var string
     */
    protected $secretKey;

    /**
     * Initial Authorization Instance
     *
     * @param string $appId
     * @param string $secretId
     * @param string $secretKey
     * @param string $bucket
     */
    public function __construct($appId, $secretId, $secretKey, $bucket)
    {
        $this->appId = $appId;
        $this->secretId = $secretId;
        $this->bucket = $bucket;
        $this->secretKey = $secretKey;
    }

    /**
     * Get Authorization
     *
     * @return string
     */
    public function getAuthorization()
    {
        return $this->signature();
    }

    /**
     * Get Bucket
     *
     * @return string
     */
    public function getAppId()
    {
        return $this->appId;
    }

    /**
     * Get Bucket
     *
     * @return string
     */
    public function getSecretId()
    {
        return $this->secretId;
    }

    /**
     * Get Bucket
     *
     * @return string
     */
    public function getSecretKey()
    {
        return $this->secretKey;
    }

    /**
     * Get Bucket
     *
     * @return string
     */
    public function getBucket()
    {
        return $this->bucket;
    }

    /**
     * Tencent Authorization signature
     *
     * @return string
     */
    protected function signature()
    {
        $signatureKey = $this->buildSignatureKey();

        $sing = hash_hmac('SHA1', $signatureKey, $this->secretKey, true);

        return base64_encode($sing . $signatureKey);
    }

    /**
     * Build signature Key
     *
     * @return string
     */
    protected function buildSignatureKey()
    {
        $signatures = [
            'a' => $this->appId,
            'b' => $this->bucket,
            'k' => $this->secretId,
            'e' => time() + 2592000,
            't' => time(),
            'r' => rand(),
            'u' => '0',
            'f' => ''
        ];

        return http_build_query($signatures);
    }
}
