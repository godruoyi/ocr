<?php

/*
 * This file is part of the godruoyi/ocr.
 *
 * (c) Godruoyi <gmail@godruoyi.com>
 *
 * This source file is subject to the MIT license that is bundled.
 */

namespace Test;

use Godruoyi\OCR\Application;
use PHPUnit\Framework\TestCase as BaseTestCase;

class TestCase extends BaseTestCase
{
    protected $config;

    protected $application;

    /**
     * Sets up the fixture, for example, open a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->config = [

            /*
            |--------------------------------------------------------------------------
            | Default client
            |--------------------------------------------------------------------------
            |
            | 指定一个默认的 client 名称，其值需要在下列的 drivers 数组中配置。
            |
            */
            'driver' => 'baidu',

            /*
            |--------------------------------------------------------------------------
            | Client 配置
            |--------------------------------------------------------------------------
            |
            | Client 配置信息，包括基本密钥等；注意目前 aliyun 暂只支持 appcode 方式。
            |
            */
            'drivers' => [
                'aliyun' => [
                    'appcode'    => '',
                    'secret_id'  => '',
                    'secret_key' => '',
                ],

                'baidu' => [
                    'access_key' => 'nVdGOnnPd4ZC2jSXGsBtYGGO',
                    'secret_key' => 'Gp4GBGKQfVS8yLZpGfRN3K8FducGiu8q',
                ],

                'tencent' => [
                    'secret_id'  => '',
                    'secret_key' => '',
                ],
            ],
        ];
        $this->application = new Application($this->config);
    }
}
