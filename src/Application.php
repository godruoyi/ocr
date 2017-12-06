<?php

/*
 * This file is part of the godruoyi/ocr.
 *
 * (c) godruoyi <godruoyi@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Godruoyi\OCR;

use Exception;
use Pimple\Container;
use Godruoyi\OCR\Support\Config;

/**
 * @author    godruoyi godruoyi@gmail.com>
 * @copyright 2017
 *
 * @see  http://ai.baidu.com/docs#/OCR-API/top
 * @see  https://github.com/godruoyi/ocr
 *
 * @property string $baidu 百度OCR识别
 *     method generalBasic($files, $options = []) 通用文字识别
 *     method accurateBasic($files, $options = []) 通用文字识别（高精度版）
 *     method general($files, $options = []) 通用文字识别（含位置信息版）
 *     method accurate($files, $options = []) 通用文字识别（含位置高精度版）
 *     method generalEnhanced($files, $options = []) 通用文字识别（含生僻字版）
 *     method webimage($files, $options = []) 网络图片文字识别
 *     method idcard($files, $options = []) 身份证识别
 *     method bankcard($files, $options = []) 银行卡识别
 *     method drivingLicense($files, $options = []) 驾驶证识别
 *     method vehicleLicense($files, $options = []) 行驶证识别
 *     method licensePlate($files, $options = []) 车牌识别
 *     method businessLicense($files, $options = []) 营业执照识别
 *     method tableWorld($files, $options = []) 表格文字识别
 *     method receipt($files, $options = []) 通用票据识别
 *
 * @property string $aliyun 阿里OCR识别
 *     method array idcard($files, $options = []) 身份证识别
 *     method array vehicle($files, $options = []) 行驶证识别
 *     method array driverLicense($files, $options = []) 驾驶证识别
 *     method array shopSign($files, $options = []) 门店识别
 *     method array english($files, $options = []) 英文识别
 *     method array businessLicense($files, $options = []) 营业执照识别
 *     method array bankCard($files, $options = []) 银行卡识别
 *     method array businessCard($files, $options = []) 名片识别
 *     method array trainTicket($files, $options = []) 火车票识别
 *     method array vehiclePlate($files, $options = []) 车牌识别
 *     method array general($files, $options = []) 通用文字识别
 *
 * @property string $tencent 腾讯OCR识别
 *     method array namecard($images, $options = []) 名片识别
 *     method array idcard($images, $options = []) 身份证识别
 *     method array drivingLicence($images, $options = []) 行驶证驾驶证识别
 *     method array general($images, $options = []) 通用文字识别
 *     method array bankcard($images, $options = []) 银行卡识别
 *     method array plate($images, $options = []) 车牌号识别
 *     method array bizlicense($images, $options = []) 营业执照识别
 */
class Application extends Container
{
    /**
     * Default Providers
     *
     * @var array
     */
    protected $providers = [
        Providers\BaiduProvider::class,
        Providers\TencentProvider::class,
        Providers\AliyunProvider::class,
        Providers\TencentAIProvider::class
    ];

    /**
     * Initeral Application Instance
     *
     * @param string|array $configs
     */
    public function __construct($configs = null)
    {
        $this['config'] = new Config($configs);

        $this->register(new Providers\CacheProvider);
        $this->registerProviders();
    }

    /**
     * Register Provider
     *
     * @return void
     */
    protected function registerProviders()
    {
        foreach (array_merge($this->providers, $this['config']->get('providers', [])) as $provider) {
            $this->register(new $provider);
        }
    }

    /**
     * __get
     *
     * @param  string $property
     *
     * @return mixed
     */
    public function __get($property)
    {
        if (isset($this[$property])) {
            return $this[$property];
        }

        throw new Exception(sprintf('Property "%s" is not defined.', $property));
    }

    /**
     * Compatible Laravel
     *
     * @return mixed
     */
    public function baidu()
    {
        return $this['baidu'];
    }

    /**
     * Compatible Laravel
     *
     * @return mixed
     */
    public function aliyun()
    {
        return $this['aliyun'];
    }

    /**
     * Compatible Laravel
     *
     * @return mixed
     */
    public function tencent()
    {
        return $this['tencent'];
    }

    /**
     * Compatible Laravel
     *
     * @return mixed
     */
    public function tencentai() {
        return $this['tencentai'];
    }
}
