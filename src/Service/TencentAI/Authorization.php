<?php

namespace Godruoyi\OCR\Service\TencentAI;

/**
 * @see https://ai.qq.com/doc/auth.shtml
 * @author leohu <alpha1130@gmail.com>
 *
 */
class Authorization
{

    /**
     * Tencent AI appid
     *
     * @var string
     */
    protected $appId = '';

    /**
     * Tencent AI appkey
     *
     * @var string
     */
    protected $appKey = '';

    /**
     * Authorization constructor.
     *
     * @param $appId
     * @param $appKey
     */
    public function __construct($appId, $appKey)
    {
        $this->appId = $appId;
        $this->appKey = $appKey;
    }

    /**
     * append signature to params
     *
     * @param array $params
     * @param $timestamp
     * @param $noncestr
     *
     * @return array
     */
    public function appendSignature(array $params = [], $timestamp = '', $noncestr = '')
    {
        $params += [
            'app_id' => $this->appId,
            'time_stamp' => $timestamp ? : time(),
            'nonce_str' => $noncestr ? : md5(uniqid())
        ];

        if (isset($params['app_key'])) {
            unset($params['app_key']);
        }

        ksort($params);
        $params['sign'] = strtoupper(md5(http_build_query($params + ['app_key' => $this->appKey])));

        return $params;
    }
}
