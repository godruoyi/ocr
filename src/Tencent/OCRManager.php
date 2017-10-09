<?php

/*
 * This file is part of the godruoyi/ocr.
 *
 * (c) godruoyi <godruoyi@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Godruoyi\OCR\Tencent;

use RuntimeException;
use Godruoyi\OCR\Support\Http;
use Godruoyi\OCR\Support\FileConverter;

/**
 * @author    godruoyi godruoyi@gmail.com>
 * @copyright 2017
 *
 * @see  https://github.com/godruoyi/ocr
 *
 * @method array namecard($images, $options = []) 名片识别
 * @method array idcard($images, $options = []) 身份证识别
 * @method array drivingLicence($images, $options = []) 行驶证驾驶证识别
 * @method array general($images, $options = []) 通用文字识别
 */
class OCRManager
{
    /**
     * Authorization instance
     *
     * @var Authorization
     */
    protected $authorization;

    const OCR_NAMECARD       = 'http://service.image.myqcloud.com/ocr/namecard';
    const OCR_IDCARD         = 'http://service.image.myqcloud.com/ocr/idcard';
    const OCR_DRIVINGLICENCE = 'http://recognition.image.myqcloud.com/ocr/drivinglicence';
    const OCR_GENERAL        = 'http://recognition.image.myqcloud.com/ocr/general';

    /**
     * Register Authorization Instance
     *
     * @param Authorization $authorization
     */
    public function __construct(Authorization $authorization)
    {
        $this->authorization = $authorization;
    }

    /**
     * OCR-名片识别
     *
     * @param  string|\SplFileInfo $images
     * @param  array $options
     *
     *         参数        是否可选     类型        描述
     *         bucket      N            string      图片空间
     *         appid       N            string      业务 id
     *
     *         ret_image   Y            int         0 不返回图片，1 返回图片
     *
     * @see https://cloud.tencent.com/document/product/460/6894
     *
     * @return array
     */
    public function namecard($images, array $options = [])
    {
        return $this->request(self::OCR_NAMECARD, $images, $options);
    }

    /**
     * OCR-身份证识别
     *
     * @param  string|\SplFileInfo $images
     * @param  array $options
     *
     *         参数        是否可选     类型        描述
     *         bucket      N            string      图片空间
     *         appid       N            string      业务 id
     *
     *         card_type   Y            int         0 为身份证有照片的一面，1为身份证有国徽的一面
     *
     * @see https://cloud.tencent.com/document/product/460/6894
     *
     * @return array
     */
    public function idcard($images, array $options = [])
    {
        return $this->request(self::OCR_IDCARD, $images, $options);
    }

    /**
     * 行驶证驾驶证识别
     *
     * @param  string|\SplFileInfo $images
     * @param  array $options
     *
     *         参数        是否可选     类型        描述
     *         bucket      N            string      图片空间
     *         appid       N            string      业务 id
     *
     *         type        Y            int         识别类型，0表示行驶证，1表示驾驶证
     *
     * @see https://cloud.tencent.com/document/product/460/6894
     *
     * @return array
     */
    public function drivingLicence($images, array $options = [])
    {
        return $this->request(self::OCR_DRIVINGLICENCE, $images, $options);
    }

    /**
     * OCR-通用印刷体识别
     *
     * @param  string|\SplFileInfo $images
     * @param  array $options
     *
     *         参数        是否可选     类型        描述
     *         bucket      Y            string      图片空间
     *         appid       Y            string      业务 id
     *
     * @see https://cloud.tencent.com/document/product/460/6894
     *
     * @return array
     */
    public function general($images, array $options = [])
    {
        return $this->request(self::OCR_DRIVINGLICENCE, $images, $options);
    }

    /**
     * Append AppId And Bucket to option if empty
     *
     * @param  array  $options
     *
     * @return array
     */
    protected function appendAppIdAndBucketIfEmpty(array $options = [])
    {
        $options['appid'] = empty($options['appid']) ? $this->authorization->getAppId() : $options['appid'];
        $options['bucket'] = empty($options['bucket']) ? $this->authorization->getBucket() : $options['bucket'];

        return $options;
    }

    /**
     * Send a Http Request
     *
     * @param  string $url
     * @param  string|\SplFileInfo $images
     * @param  array  $options
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    protected function request($url, $images, array $options = [])
    {
        $http = (new Http)->setHeaders([
            'Authorization' => $this->authorization->getAuthorization()
        ]);

        $images = is_array($images) ? $images : [$images];

        $isurl = false;
        $ismultipart = false;
        $multiparts = [];

        foreach ($images as $index => $image) {
            if (FileConverter::isUrl($image)) {
                $isurl = true;
            } else {
                $ismultipart = true;
                $multiparts['image'][] = $image;
            }
        }

        if ($isurl && $ismultipart) {
            throw new RuntimeException('Can not exist at the same time for online url and local file.');
        }

        $options = $this->appendAppIdAndBucketIfEmpty($options);

        try {
            if ($isurl) {
                $response = $http->json($url, array_merge($options, ['url_list' => $images]));
            } else {
                $response = $http->upload($url, $multiparts, $options);
            }
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            if ($e->hasResponse()) {
                $response = $e->getResponse();
            }
        }

        return $http->parseJson($response);
    }
}
