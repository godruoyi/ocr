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
 *
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
 * @property string $tencent 腾讯OCR识别
 *
 */
class Application extends Container
{
    /**
     * Default Providers
     *
     * @var array
     */
    protected $providers = [
        Providers\LogProvider::class,
        Providers\CacheProvider::class,

        //default support ocr provider
        Providers\BaiduProvider::class,
        Providers\TencentProvider::class,
        Providers\AliyunProvider::class,
    ];

    /**
     * Initeral Application Instance
     *
     * @param string|array $configs
     */
    public function __construct($configs = null)
    {
        $this['config'] = new Config($configs);

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

    public function __get($property)
    {
        if (isset($this[$property])) {
            return $this[$property];
        }

        throw new Exception(sprintf('Property "%s" is not defined.', $property));
    }
}
