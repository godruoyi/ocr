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
use Doctrine\Common\Cache\Cache;
use Godruoyi\OCR\Support\FileConverter;
use Godruoyi\OCR\Support\Http;

/**
 * @author    godruoyi godruoyi@gmail.com>
 * @copyright 2017
 *
 * @see  http://ai.baidu.com/docs#/OCR-API/top
 * @see  https://github.com/godruoyi/ocr
 *
 * @method array generalBasic($images, $options = []) 通用文字识别
 * @method array accurateBasic($images, $options = []) 通用文字识别（高精度版）
 * @method array general($images, $options = []) 通用文字识别（含位置信息版）
 * @method array accurate($images, $options = []) 通用文字识别（含位置高精度版）
 * @method array generalEnhanced($images, $options = []) 通用文字识别（含生僻字版）
 * @method array webimage($images, $options = []) 网络图片文字识别
 * @method array idcard($images, $options = []) 身份证识别
 * @method array bankcard($images, $options = []) 银行卡识别
 * @method array drivingLicense($images, $options = []) 驾驶证识别
 * @method array vehicleLicense($images, $options = []) 行驶证识别
 * @method array licensePlate($images, $options = []) 车牌识别
 * @method array businessLicense($images, $options = []) 营业执照识别
 * @method array tableWorld($images, $options = []) 表格文字识别
 * @method array receipt($images, $options = []) 通用票据识别
 */
class OCRManager
{
    /**
     * AccessToken Instance
     *
     * @var AccessToken
     */
    protected $accessToken;

    /**
     * OCR API Whether to support url
     *
     * @var boolean
     */
    protected $supportUrl = true;

    const GENERAL_BASIC    = 'https://aip.baidubce.com/rest/2.0/ocr/v1/general_basic';
    const ACCURATE_BASIC   = 'https://aip.baidubce.com/rest/2.0/ocr/v1/accurate_basic';
    const GENERAL          = 'https://aip.baidubce.com/rest/2.0/ocr/v1/general';
    const ACCURATE         = 'https://aip.baidubce.com/rest/2.0/ocr/v1/accurate';
    const GENERAL_ENHANCED = 'https://aip.baidubce.com/rest/2.0/ocr/v1/general_enhanced';
    const WEBIMAGE         = 'https://aip.baidubce.com/rest/2.0/ocr/v1/webimage';
    const IDCARD           = 'https://aip.baidubce.com/rest/2.0/ocr/v1/idcard';
    const BANKCARD         = 'https://aip.baidubce.com/rest/2.0/ocr/v1/bankcard';
    const DRIVING_LICENSE  = 'https://aip.baidubce.com/rest/2.0/ocr/v1/driving_license';
    const VEHICLE_LICENSE  = 'https://aip.baidubce.com/rest/2.0/ocr/v1/vehicle_license';
    const LICENSE_PLATE    = 'https://aip.baidubce.com/rest/2.0/ocr/v1/license_plate';
    const BUSINESS_LICENSE = 'https://aip.baidubce.com/rest/2.0/ocr/v1/business_license';
    const RECEIPT          = 'https://aip.baidubce.com/rest/2.0/ocr/v1/receipt';

    /**
     * Register AccessToken
     *
     * @param AccessToken $accessToken
     */
    public function __construct(AccessToken $accessToken)
    {
        $this->accessToken = $accessToken;
    }

    /**
     * 通用文字识别
     *
     * @param  string|\SplFileInfo $images
     * @param  array $options
     *
     *         参数              是否可选     类型        可选范围/说明
     *         language_type      N        string      - CHN_ENG：中英文混合；默认为CHN_ENG
     *                                                 - ENG：英文；
     *                                                 - POR：葡萄牙语；
     *                                                 - FRE：法语；
     *                                                 - GER：德语；
     *                                                 - ITA：意大利语；
     *                                                 - SPA：西班牙语；
     *                                                 - RUS：俄语；
     *                                                 - JAP：日语；
     *                                                 - KOR：韩语
     *         detect_direction   N       boolean      true/false 是否检测图像朝向，默认不检测
     *         detect_language    N       boolean      true/false 是否检测语言，默认不检测,支持（中文、英语、日语、韩语）
     *         probability        N       string       是否返回识别结果中每一行的置信度
     *
     * @throws \RuntimeException
     *
     *
     * @return array
     */
    public function generalBasic($images, array $options = [])
    {
        $this->supportUrl = true;

        return $this->request(self::GENERAL_BASIC, $this->buildRequestParam($images, $options));
    }

    /**
     * 通用文字识别（高精度版）不支持在线地址
     *
     * @param  string|\SplFileInfo $images
     * @param  array $options
     *
     *         参数              是否可选     类型        可选范围/说明
     *
     *         detect_direction   N       boolean      true/false 是否检测图像朝向，默认不检测
     *         probability        N       string       是否返回识别结果中每一行的置信度
     *
     * @throws \RuntimeException
     *
     * @return array
     */
    public function accurateBasic($images, array $options = [])
    {
        $this->supportUrl = false;

        return $this->request(self::ACCURATE_BASIC, $this->buildRequestParam($images, $options));
    }

    /**
     * 通用文字识别（含位置信息版）
     *
     * @param  string|\SplFileInfo $images
     * @param  array $options
     *
     *         参数                     是否可选     类型        可选范围/说明
     *         recognize_granularity     N       string       big/small, 是否定位单字符位置，
     *                                                        big：不定位单字符位置，默认值；
     *                                                        small：定位单字符位置
     *         language_type             N       string       - CHN_ENG：中英文混合；默认为CHN_ENG
     *                                                        - ENG：英文；
     *                                                        - POR：葡萄牙语；
     *                                                        - FRE：法语；
     *                                                        - GER：德语；
     *                                                        - ITA：意大利语；
     *                                                        - SPA：西班牙语；
     *                                                        - RUS：俄语；
     *                                                        - JAP：日语；
     *                                                        - KOR：韩语
     *         detect_direction          N       boolean      true/false 是否检测图像朝向，默认不检测
     *         detect_language           N       boolean      true/false 是否检测语言，默认不检测，支持（中文、英语、日语、韩语）
     *         vertexes_location         N       boolean      true/false 是否返回文字外接多边形顶点位置，不支持单字位置。默认为false
     *         probability               N       boolean      是否返回识别结果中每一行的置信度
     *
     * @throws \RuntimeException
     *
     * @return array
     */
    public function general($images, array $options = [])
    {
        $this->supportUrl = true;

        return $this->request(self::GENERAL, $this->buildRequestParam($images, $options));
    }

    /**
     * 通用文字识别（含位置高精度版）不支持在线地址
     *
     * @param  string|\SplFileInfo $images
     * @param  array $options
     *
     *         参数                     是否可选     类型        可选范围/说明
     *         recognize_granularity     N       string       big/small, 是否定位单字符位置，
     *                                                        big：不定位单字符位置，默认值；
     *                                                        small：定位单字符位置
     *         detect_direction          N       boolean      true/false 是否检测图像朝向，默认不检测
     *         vertexes_location         N       boolean      true/false 是否返回文字外接多边形顶点位置，不支持单字位置。默认为false
     *         probability               N       boolean      是否返回识别结果中每一行的置信度
     *
     * @throws \RuntimeException
     *
     * @return array
     */
    public function accurate($images, array $options = [])
    {
        $this->supportUrl = false;

        return $this->request(self::ACCURATE, $this->buildRequestParam($images, $options));
    }

    /**
     * 通用文字识别（含生僻字版）
     *
     * @param  string|\SplFileInfo $images
     * @param  array $options
     *
     *         参数                     是否可选     类型        可选范围/说明
     *         language_type             N       string       - CHN_ENG：中英文混合；默认为CHN_ENG
     *                                                        - ENG：英文；
     *                                                        - POR：葡萄牙语；
     *                                                        - FRE：法语；
     *                                                        - GER：德语；
     *                                                        - ITA：意大利语；
     *                                                        - SPA：西班牙语；
     *                                                        - RUS：俄语；
     *                                                        - JAP：日语；
     *                                                        - KOR：韩语
     *         detect_direction          N       boolean      true/false 是否检测图像朝向，默认不检测
     *         vertexes_location         N       boolean      true/false 是否返回文字外接多边形顶点位置，不支持单字位置。默认为false
     *         probability               N       boolean      是否返回识别结果中每一行的置信度
     *
     * @throws \RuntimeException
     *
     * @return array
     */
    public function generalEnhanced($images, array $options = [])
    {
        $this->supportUrl = true;

        return $this->request(self::GENERAL_ENHANCED, $this->buildRequestParam($images, $options));
    }

    /**
     * 网络图片文字识别
     *
     * @param  string|\SplFileInfo $images
     * @param  array $options
     *
     *         参数                     是否可选     类型        可选范围/说明
     *         detect_direction          N       boolean      true/false 是否检测图像朝向，默认不检测
     *         detect_language           N       boolean      true/false 是否检测语言，默认不检测，支持（中文、英语、日语、韩语）
     *
     * @throws \RuntimeException
     *
     * @return array
     */
    public function webimage($images, array $options = [])
    {
        $this->supportUrl = true;

        return $this->request(self::WEBIMAGE, $this->buildRequestParam($images, $options));
    }

    /**
     * 身份证识别（不支持在线地址）
     *
     * @param  string|\SplFileInfo $images
     * @param  array $options
     *
     *         参数                     是否可选     类型        可选范围/说明
     *         detect_direction          N       boolean      true/false 是否检测图像朝向，默认不检测
     *         id_card_side              Y       string       front、back，front：身份证正面；back：身份证背面
     *         detect_risk               N       boolan       true/false 是否开启身份证风险类型功能，默认false
     *
     * @throws \RuntimeException
     *
     * @return array
     */
    public function idcard($images, array $options = [])
    {
        $this->supportUrl = false;

        return $this->request(self::IDCARD, $this->buildRequestParam($images, $options));
    }

    /**
     * 银行卡识别（不支持在线地址）
     *
     * @param  string|\SplFileInfo $images
     * @param  array $options
     *         null
     *
     * @throws \RuntimeException
     *
     * @return array
     */
    public function bankcard($images, array $options = [])
    {
        $this->supportUrl = false;

        return $this->request(self::BANKCARD, $this->buildRequestParam($images, $options));
    }

    /**
     * 驾驶证识别（不支持在线地址）
     *
     * @param  string|\SplFileInfo $images
     * @param  array $options
     *
     *         参数                     是否可选     类型        可选范围/说明
     *         detect_direction          N       boolean      true/false 是否检测图像朝向，默认不检测
     *
     * @throws \RuntimeException
     *
     * @return array
     */
    public function drivingLicense($images, array $options = [])
    {
        $this->supportUrl = false;

        return $this->request(self::DRIVING_LICENSE, $this->buildRequestParam($images, $options));
    }

    /**
     * 行驶证识别（不支持在线地址）
     *
     * @param  string|\SplFileInfo $images
     * @param  array $options
     *
     *         参数                     是否可选     类型        可选范围/说明
     *         detect_direction          N       boolean      true/false 是否检测图像朝向，默认不检测
     *         accuracy                  N       string       normal，缺省,  normal 使用快速服务，1200ms左右时延；
     *                                                        缺省或其它值使用高精度服务，1600ms左右时延
     *
     * @throws \RuntimeException
     *
     * @return array
     */
    public function vehicleLicense($images, array $options = [])
    {
        $this->supportUrl = false;

        return $this->request(self::VEHICLE_LICENSE, $this->buildRequestParam($images, $options));
    }

    /**
     * 车牌识别（不支持在线地址）
     *
     * @param  string|\SplFileInfo $images
     * @param  array $options
     *
     *         参数                     是否可选     类型        可选范围/说明
     *         multi_detect              N            boolean      是否检测多张车牌，默认为false，
     *
     * @throws \RuntimeException
     *
     * @return array
     */
    public function licensePlate($images, array $options = [])
    {
        $this->supportUrl = false;

        return $this->request(self::LICENSE_PLATE, $this->buildRequestParam($images, $options));
    }

    /**
     * 营业执照识别（不支持在线地址）
     *
     * @param  string|\SplFileInfo $images
     * @param  array $options
     *         null
     *
     * @throws \RuntimeException
     *
     * @return array
     */
    public function businessLicense($images, array $options = [])
    {
        $this->supportUrl = false;

        return $this->request(self::BUSINESS_LICENSE, $this->buildRequestParam($images, $options));
    }

    /**
     * 表格文字识别
     *
     * @param  string|\SplFileInfo $images
     * @param  array $options
     *         null
     *
     * @throws \RuntimeException
     *
     * @return array
     */
    public function tableWorld($images, array $options = [])
    {
        //@Todo
    }

    /**
     * 通用票据识别（不支持在线地址）
     *
     * @param  string|\SplFileInfo $images
     * @param  array $options
     *
     *         参数                     是否可选     类型        可选范围/说明
     *         recognize_granularity     N       string       big/small, 是否定位单字符位置，
     *                                                        big：不定位单字符位置，默认值；
     *                                                        small：定位单字符位置
     *         probability               N       boolean      是否返回识别结果中每一行的置信度
     *         accuracy                  N       string       normal,缺省   normal 使用快速服务;缺省或其它值使用高精度服务
     *         detect_direction          N       boolean      true/false 是否检测图像朝向，默认不检测
     *
     * @throws \RuntimeException
     *
     * @return array
     */
    public function receipt($images, array $options = [])
    {
        $this->supportUrl = false;

        return $this->request(self::RECEIPT, $this->buildRequestParam($images, $options));
    }

    /**
     * Append access_token to this url
     *
     * @param  string $url
     *
     * @return string
     */
    protected function request($url, array $options = [])
    {
        $httpClient = new Http;

        try {
            $response = $httpClient->request('POST', $url, [
                'form_params' => $options,
                'query' => [$this->accessToken->getQueryName() => $this->accessToken->getAccessToken(true)]
            ]);
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            if ($e->hasResponse()) {
                $response = $e->getResponse();
            } else {
                throw $e;
            }
        }


        return $httpClient->parseJson($response);
    }

    /**
     * Build Request Param
     *
     * @param  string\SplFileInfo $images
     * @param  array  $options
     *
     * @return
     */
    protected function buildRequestParam($images, $options = [])
    {
        //Baidu OCR不支持多个url或图片，只支持一次识别一张
        if (is_array($images) && ! empty($images[0])) {
            $images = $images[0];
        }

        // if (! $this->supportUrl && FileConverter::isUrl($images)) {
        //     throw new RuntimeException('current method not support online picture.');
        // }

        if ($this->supportUrl && FileConverter::isUrl($images)) {
            $options['url'] = $images;
        } else {
            $options['image'] = FileConverter::toBase64Encode($images);
        }

        return $options;
    }
}
