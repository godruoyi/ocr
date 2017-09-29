<?php

/*
 * This file is part of the godruoyi/ocr.
 *
 * (c) godruoyi <godruoyi@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Godruoyi\OCR\Services\Baidu;

use Doctrine\Common\Cache\Cache;
use Godruoyi\OCR\Services\AbstractService;

/**
 * @author    godruoyi godruoyi@gmail.com>
 * @copyright 2017
 *
 * @see  http://ai.baidu.com/docs#/OCR-API/top
 * @see  https://github.com/godruoyi/ocr
 *
 * @method generalBasic($imageOrUrl, $options = []) 通用文字识别
 * @method accurateBasic($imageOrUrl, $options = []) 通用文字识别（高精度版）
 * @method general($imageOrUrl, $options = []) 通用文字识别（含位置信息版）
 * @method accurate($imageOrUrl, $options = []) 通用文字识别（含位置高精度版）
 * @method generalEnhanced($imageOrUrl, $options = []) 通用文字识别（含生僻字版）
 * @method webimage($imageOrUrl, $options = []) 网络图片文字识别
 * @method idcard($imageOrUrl, $options = []) 身份证识别
 * @method bankcard($imageOrUrl, $options = []) 银行卡识别
 * @method drivingLicense($imageOrUrl, $options = []) 驾驶证识别
 * @method vehicleLicense($imageOrUrl, $options = []) 行驶证识别
 * @method licensePlate($imageOrUrl, $options = []) 车牌识别
 * @method businessLicense($imageOrUrl, $options = []) 营业执照识别
 * @method tableWorld($imageOrUrl, $options = []) 表格文字识别
 * @method receipt($imageOrUrl, $options = []) 通用票据识别
 */
class BaiduOcrManager extends AbstractService
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
     * @param  string|\SplFileInfo $imageOrUrl
     * @param  array $options
     *
     *         参数              是否可选     类型        可选范围/说明
     *
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
     * @return array
     */
    public function generalBasic($imageOrUrl, $options = [])
    {
        $this->supportUrl = true;

        $url = $this->appendAccessToken(self::GENERAL_BASIC);

        return $this->toArray($this->getHttpClient()->post($url, $this->buildRequestParam($imageOrUrl, $options)));
    }

    /**
     * 通用文字识别（高精度版）
     *
     * @param  string|\SplFileInfo $imageOrUrl
     * @param  array $options
     *
     *         参数              是否可选     类型        可选范围/说明
     *
     *         detect_direction   N       boolean      true/false 是否检测图像朝向，默认不检测
     *         probability        N       string       是否返回识别结果中每一行的置信度
     *
     * @return array
     */
    public function accurateBasic($imageOrUrl, $options = [])
    {
        $this->supportUrl = false;

        $url = $this->appendAccessToken(self::ACCURATE_BASIC);

        return $this->toArray($this->getHttpClient()->post($url, $this->buildRequestParam($imageOrUrl, $options)));
    }

    /**
     * 通用文字识别（含位置信息版）
     *
     * @param  string|\SplFileInfo $imageOrUrl
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
     * @return array
     */
    public function general($imageOrUrl, $options = [])
    {
        $this->supportUrl = true;

        $url = $this->appendAccessToken(self::ACCURATE_BASIC);

        return $this->toArray($this->getHttpClient()->post($url, $this->buildRequestParam($imageOrUrl, $options)));
    }

    /**
     * 通用文字识别（含位置高精度版）
     *
     * @param  string|\SplFileInfo $imageOrUrl
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
     * @return array
     */
    public function accurate($imageOrUrl, $options = [])
    {
        $this->supportUrl = false;

        $url = $this->appendAccessToken(self::ACCURATE);

        return $this->toArray($this->getHttpClient()->post($url, $this->buildRequestParam($imageOrUrl, $options)));
    }

    /**
     * 通用文字识别（含生僻字版）
     *
     * @param  string|\SplFileInfo $imageOrUrl
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
     * @return array
     */
    public function generalEnhanced($imageOrUrl, $options = [])
    {
        $this->supportUrl = true;

        $url = $this->appendAccessToken(self::GENERAL_ENHANCED);

        return $this->toArray($this->getHttpClient()->post($url, $this->buildRequestParam($imageOrUrl, $options)));
    }

    /**
     * 网络图片文字识别
     *
     * @param  string|\SplFileInfo $imageOrUrl
     * @param  array $options
     *
     *         参数                     是否可选     类型        可选范围/说明
     *         detect_direction          N       boolean      true/false 是否检测图像朝向，默认不检测
     *         detect_language           N       boolean      true/false 是否检测语言，默认不检测，支持（中文、英语、日语、韩语）
     *
     * @return array
     */
    public function webimage($imageOrUrl, $options = [])
    {
        $this->supportUrl = true;

        $url = $this->appendAccessToken(self::WEBIMAGE);

        return $this->toArray($this->getHttpClient()->post($url, $this->buildRequestParam($imageOrUrl, $options)));
    }

    /**
     * 身份证识别
     *
     * @param  string|\SplFileInfo $imageOrUrl
     * @param  array $options
     *
     *          参数                     是否可选     类型        可选范围/说明
     *         detect_direction          N       boolean      true/false 是否检测图像朝向，默认不检测
     *         id_card_side              Y       string       front、back，front：身份证正面；back：身份证背面
     *         detect_risk               N       boolan       true/false 是否开启身份证风险类型功能，默认false
     *
     * @return array
     */
    public function idcard($imageOrUrl, $options = [])
    {
        $this->supportUrl = false;

        $url = $this->appendAccessToken(self::IDCARD);

        return $this->toArray($this->getHttpClient()->post($url, $this->buildRequestParam($imageOrUrl, $options)));
    }

    /**
     * 银行卡识别
     *
     * @param  string|\SplFileInfo $imageOrUrl
     * @param  array $options
     *         null
     *
     * @return array
     */
    public function bankcard($imageOrUrl, $options = [])
    {
        $this->supportUrl = false;

        $url = $this->appendAccessToken(self::BANKCARD);

        return $this->toArray($this->getHttpClient()->post($url, $this->buildRequestParam($imageOrUrl, $options)));
    }

    /**
     * 驾驶证识别
     *
     * @param  string|\SplFileInfo $imageOrUrl
     * @param  array $options
     *
     *         参数                     是否可选     类型        可选范围/说明
     *         detect_direction          N       boolean      true/false 是否检测图像朝向，默认不检测
     *
     * @return array
     */
    public function drivingLicense($imageOrUrl, $options = [])
    {
        $this->supportUrl = false;

        $url = $this->appendAccessToken(self::DRIVING_LICENSE);

        return $this->toArray($this->getHttpClient()->post($url, $this->buildRequestParam($imageOrUrl, $options)));
    }

    /**
     * 行驶证识别
     *
     * @param  string|\SplFileInfo $imageOrUrl
     * @param  array $options
     *
     *         参数                     是否可选     类型        可选范围/说明
     *         detect_direction          N       boolean      true/false 是否检测图像朝向，默认不检测
     *         accuracy                  N       string       normal，缺省,  normal 使用快速服务，1200ms左右时延；
     *                                                        缺省或其它值使用高精度服务，1600ms左右时延
     *
     * @return array
     */
    public function vehicleLicense($imageOrUrl, $options = [])
    {
        $this->supportUrl = false;

        $url = $this->appendAccessToken(self::VEHICLE_LICENSE);

        return $this->toArray($this->getHttpClient()->post($url, $this->buildRequestParam($imageOrUrl, $options)));
    }

    /**
     * 车牌识别
     *
     * @param  string|\SplFileInfo $imageOrUrl
     * @param  array $options
     *
     *         参数                     是否可选     类型        可选范围/说明
     *         multi_detect              N            boolean      是否检测多张车牌，默认为false，
     *
     * @return array
     */
    public function licensePlate($imageOrUrl, $options = [])
    {
        $this->supportUrl = false;

        $url = $this->appendAccessToken(self::LICENSE_PLATE);

        return $this->toArray($this->getHttpClient()->post($url, $this->buildRequestParam($imageOrUrl, $options)));
    }

    /**
     * 营业执照识别
     *
     * @param  string|\SplFileInfo $imageOrUrl
     * @param  array $options
     *         null
     *
     * @return array
     */
    public function businessLicense($imageOrUrl, $options = [])
    {
        $this->supportUrl = false;

        $url = $this->appendAccessToken(self::BUSINESS_LICENSE);

        return $this->toArray($this->getHttpClient()->post($url, $this->buildRequestParam($imageOrUrl, $options)));
    }

    /**
     * 表格文字识别
     *
     * @param  string|\SplFileInfo $imageOrUrl
     * @param  array $options
     *         null
     *
     * @return array
     */
    public function tableWorld($imageOrUrl, $options = [])
    {
        //@Todo
    }

    /**
     * 通用票据识别
     *
     * @param  string|\SplFileInfo $imageOrUrl
     * @param  array $options
     *
     *         参数                     是否可选     类型        可选范围/说明
     *         recognize_granularity     N       string       big/small, 是否定位单字符位置，
     *                                                        big：不定位单字符位置，默认值；
     *                                                        small：定位单字符位置
     *         probability               N       boolean      是否返回识别结果中每一行的置信度
     *         accuracy                  N       string       normal,缺省   normal 使用快速服务;缺省或其它值使用高精度服务
     *         detect_direction          N       boolean      true/false 是否检测图像朝向，默认不检测
     * @return array
     */
    public function receipt($imageOrUrl, $options = [])
    {
        $this->supportUrl = false;

        $url = $this->appendAccessToken(self::RECEIPT);

        return $this->toArray($this->getHttpClient()->post($url, $this->buildRequestParam($imageOrUrl, $options)));
    }

    /**
     | -----------------------------------------------------------------
     */

    /**
     * Append access_token to this url
     *
     * @param  string $url
     *
     * @return string
     */
    protected function appendAccessToken($url)
    {
        $andChat = (stripos($url, '?') !== false) ? '&' : '?';

        $url .= $andChat . $this->accessToken->getQueryName() . '=' . $this->accessToken->getAccessToken();

        return $url;
    }

    /**
     * Build Request Param
     *
     * @param  string\SplFileInfo $imageOrUrl
     * @param  array  $options
     *
     * @return
     */
    protected function buildRequestParam($imageOrUrl, $options = [])
    {
        if ($this->supportUrl && $this->isUrl($imageOrUrl)) {
            $options['url'] = $imageOrUrl;
        } else {
            $options['image'] = $this->fileToBase64Encode($imageOrUrl);
        }

        return $options;
    }

    /**
     * Determine gieved path has active url
     *
     * @param  string  $path
     *
     * @return boolean
     */
    protected function isUrl($path)
    {
        return filter_var($path, FILTER_VALIDATE_URL) !== false;
    }
}
