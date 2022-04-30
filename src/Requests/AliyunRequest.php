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
use GuzzleHttp\Middleware;
use InvalidArgumentException;
use Psr\Http\Message\ResponseInterface;

class AliyunRequest extends Request
{
    /**
     * 阿里云请求格式，不同的接口正式请求时格式不一致.
     *
     * @var string
     */
    protected $requestFormats = ['basic', 'inputs', 'imgorurl'];

    /**
     * {@inheritdoc}
     */
    public function send($url, $images, array $options = []): ResponseInterface
    {
        return $this->http->json($url, $this->mergeOptions($images, $options));
    }

    /**
     * @param mixed $images
     */
    protected function mergeOptions($images, array $options): array
    {
        $images = $this->filterOneImage($images, 'Aliyun ocr only one image can be identified at a time, default to array[0].');
        $format = $options['_format'] ?? 'inputs';

        if (!in_array($format, $this->requestFormats, true)) {
            throw new InvalidArgumentException(sprintf('Unallowed format type, only [%s]', join($this->requestFormats, ',')));
        }

        $format = 'format' . ucfirst($format);

        return $this->{$format}($images, $options);
    }

    /**
     * Basic request format.
     *
     * @param mixed $images
     */
    protected function formatBasic($images, array $options): array
    {
        // If gieved image is not url, try get image content to base64 encode.
        // Be careful, some methods do not support online images.
        if (!FileConverter::isUrl($images)) {
            $images = FileConverter::toBase64Encode($images);
        }

        return [
            'image' => $images,
            'configure' => json_encode($options, JSON_UNESCAPED_UNICODE),
        ];
    }

    /**
     * Use inputs warp request data.
     *
     * @param mixed $images
     */
    protected function formatInputs($images, array $options): array
    {
        if (!FileConverter::isUrl($images)) {
            $images = FileConverter::toBase64Encode($images);
        }

        return [
            'inputs' => [
                [
                    'image' => [
                        'dataType' => 50,
                        'dataValue' => $images,
                    ],
                    'configure' => [
                        'dataType' => 50,
                        'dataValue' => json_encode($options, JSON_UNESCAPED_UNICODE),
                    ],
                ],
            ],
        ];
    }

    /**
     * support online image.
     *
     * @param mixed $images
     *
     * @return array
     */
    protected function formatImgorurl($images, array $options)
    {
        $datas = [];

        if (!FileConverter::isUrl($images)) {
            $datas['img'] = FileConverter::toBase64Encode($images);
        } else {
            $datas['url'] = $images;
        }

        return array_merge($options, $datas);
    }

    /**
     * Middlewares.
     * [
     *     'aliyun' => callable
     * ].
     */
    protected function middlewares(): array
    {
        return [
            'aliyun' => $this->authRequest(),
        ];
    }

    /**
     * Auth request.
     *
     * @return mixed
     */
    protected function authRequest()
    {
        return Middleware::mapRequest(function ($request) {
            return $this->canUseSignatureWay()
                ? $this->signatureRequestUseAppSecret($request)
                : $this->signatureRequestUseAppCode($request);
        });
    }

    /**
     * AppKey And AppSecret signature.
     *
     * @param mixed $request
     *
     * @return mixed
     */
    protected function signatureRequestUseAppSecret($request)
    {
        // @todo wait a pr.
        throw new \Exception('Aliyun AppKey and AppSecret has not be completed');
    }

    /**
     * Signature Request Use AppCode.
     *
     * @param mixed $request
     *
     * @return mixed
     */
    public function signatureRequestUseAppCode($request)
    {
        $appcode = $this->app['config']->get('drivers.aliyun.appcode');

        return $request->withHeader('Authorization', 'APPCODE ' . $appcode);
    }

    protected function canUseSignatureWay(): bool
    {
        $id = $this->app['config']->get('drivers.aliyun.secret_id');
        $key = $this->app['config']->get('drivers.aliyun.secret_key');

        return !empty($id) && !empty($key);
    }
}
