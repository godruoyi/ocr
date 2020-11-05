<?php

/*
 * This file is part of the godruoyi/ocr.
 *
 * (c) Godruoyi <gmail@godruoyi.com>
 *
 * This source file is subject to the MIT license that is bundled.
 */

namespace Godruoyi\OCR\Requests;

use Godruoyi\OCR\Support\BaiduSampleSigner;
use Godruoyi\OCR\Support\FileConverter;
use Godruoyi\OCR\Support\Response;
use GuzzleHttp\Middleware;
use Psr\Http\Message\RequestInterface;

class BaiduRequest extends Request
{
    /**
     * Specified http base uri.
     *
     * @var string
     */
    const BASEURI = 'https://aip.baidubce.com/rest/2.0/ocr/v1/';

    /**
     * {@inheritdoc}
     */
    protected function middlewares(): array
    {
        return [
            'baidu' => $this->requestMiddleware(),
        ];
    }

    /**
     * {@inheritdoc}
     */
    protected function init()
    {
        $accessKeyId = $this->app['config']->get('drivers.baidu.access_key');
        $secretAccessKey = $this->app['config']->get('drivers.baidu.secret_key');

        $this->signer = new BaiduSampleSigner($accessKeyId, $secretAccessKey);
    }

    /**
     * {@inheritdoc}
     */
    public function request($url, $images, array $options = []): Response
    {
        return $this->http->post($url, $this->mergeOptions($images, $options), [
            'base_uri' => self::BASEURI,
        ]);
    }

    /**
     * @param mixed $images
     * @param array $options
     *
     * @return array
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

    /**
     * Register request middleware.
     */
    protected function requestMiddleware()
    {
        return function (callable $handler) {
            return function (RequestInterface $request, array $options) use ($handler) {
                $httpMethod = $request->getMethod();
                $path = $request->getUri()->getPath();
                $host = current($request->getHeader('Host'));

                $authorization = $this->signer->sign($httpMethod, $path, compact('host'));
                $request = $request->withHeader('Authorization', $authorization);

                return $handler($request, $options);
            };
        };
    }
}
