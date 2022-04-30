<?php

/*
 * This file is part of the godruoyi/ocr.
 *
 * (c) Godruoyi <gmail@godruoyi.com>
 *
 * This source file is subject to the MIT license that is bundled.
 */

namespace Godruoyi\OCR\Requests;

use Godruoyi\OCR\Support\FileConverter;
use Godruoyi\OCR\Support\TencentSignatureV3;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class TencentRequest extends Request
{
    public const VERSION = 'Godruoyi_OCR_PHP_SDK_2.0';

    public const BASEURI = 'https://ocr.tencentcloudapi.com';

    /**
     * Signature request.
     *
     * @var TencentSignatureV3
     */
    protected $signer;

    /**
     * {@inheritdoc}
     */
    protected function init()
    {
        $id = $this->app['config']->get('drivers.tencent.secret_id');
        $key = $this->app['config']->get('drivers.tencent.secret_key');

        $this->signer = new TencentSignatureV3($id, $key);
    }

    /**
     * sdk default options.
     *
     * @param string $action
     * @param string $region
     * @param string $apiVersion
     * @return array
     */
    protected function requestOptions(string $action, string $region = '', string $apiVersion = '2018-11-19'): array
    {
        $apiVersion = $apiVersion ?: '2018-11-19';

        $headers = [
            'X-TC-Action' => ucfirst($action),
            'X-TC-RequestClient' => self::VERSION,
            'X-TC-Timestamp' => time(),
            'X-TC-Version' => $apiVersion,
        ];

        if (!empty($region)) {
            $headers['X-TC-Region'] = $region;
        }

        return ['headers' => $headers];
    }

    /**
     * {@inheritdoc}
     */
    public function send($action, $images, array $options = []): ResponseInterface
    {
        $region = $options['region'] ?? $options['Region'] ?? '';
        $version = $options['version'] ?? $options['Version'] ?? '';

        return $this->http->json(
            self::BASEURI,
            $this->formatRequestBody($images, $options),
            '',
            $this->requestOptions($action, $region, $version)
        );
    }

    /**
     * {@inheritdoc}
     */
    protected function middlewares(): array
    {
        return [
            'tencent' => $this->authMiddleware(),
        ];
    }

    /**
     * Format reqyest body.
     *
     * @param mixed $images
     *
     * @return array
     */
    protected function formatRequestBody($images, array $options = [])
    {
        $images = $this->filterOneImage($images, 'Tencent ocr only one image can be identified at a time, default to array[0].');

        unset($options['region']);
        unset($options['Region']);
        unset($options['version']);
        unset($options['Version']);

        if (FileConverter::isUrl($images)) {
            return array_merge($options, ['ImageUrl' => $images]);
        }

        return array_merge($options, ['ImageBase64' => FileConverter::toBase64Encode($images)]);
    }

    /**
     * Tencent auth middleware.
     *
     * @return callable
     */
    protected function authMiddleware()
    {
        return function (callable $handler) {
            return function (RequestInterface $request, array $options) use ($handler) {
                $a = $request->withHeader('Authorization', $this->signer->authorization($request));

                return $handler($a, $options);
            };
        };
    }
}
