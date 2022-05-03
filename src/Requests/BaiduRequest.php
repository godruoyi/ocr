<?php

/*
 * This file is part of the godruoyi/ocr.
 *
 * (c) Godruoyi <gmail@godruoyi.com>
 *
 * This source file is subject to the MIT license that is bundled.
 */

namespace Godruoyi\OCR\Requests;

use Godruoyi\OCR\Support\BaiduAccessToken;
use Godruoyi\OCR\Support\FileConverter;
use Psr\Http\Message\ResponseInterface;

class BaiduRequest extends Request
{
    /**
     * Specified http base uri.
     *
     * @var string
     */
    public const BASEURL = 'https://aip.baidubce.com/rest/2.0/ocr/v1/';

    /**
     * @var BaiduAccessToken
     */
    protected $accessToken;

    /**
     * {@inheritdoc}
     */
    protected function init()
    {
        $accessKeyId = $this->app['config']->get('drivers.baidu.access_key');
        $secretAccessKey = $this->app['config']->get('drivers.baidu.secret_key');

        $this->accessToken = new BaiduAccessToken(
            $this->app['http'],
            $this->app['cache'],
            $accessKeyId,
            $secretAccessKey
        );
    }

    /**
     * {@inheritdoc}
     */
    public function send($url, $images, array $options = []): ResponseInterface
    {
        $url = $url . '?access_token=' . $this->accessToken->getAccessToken();
        return $this->http->post($url, $this->mergeOptions($images, $options), [
            'base_uri' => self::BASEURL,
        ]);
    }

    /**
     * @param mixed $images
     */
    public function mergeOptions($images, array $options): array
    {
        $images = $this->filterOneImage($images, 'Baidu ocr only one image can be identified at a time, default to array[0].');
        $url2base64 = $options['_urlauto2base64'] ?? false;

        if ($url2base64 && FileConverter::isUrl($images)) {
            $options['image'] = FileConverter::toBase64Encode($images);
        } else {
            if (FileConverter::isUrl($images)) {
                $options['url'] = $images;
            } else {
                $options['image'] = FileConverter::toBase64Encode($images);
            }
        }

        return $options;
    }

    public function setAccessToken(BaiduAccessToken $accessToken)
    {
        $this->accessToken = $accessToken;
    }

    public function getAccessToken(): BaiduAccessToken
    {
        return $this->accessToken;
    }
}
