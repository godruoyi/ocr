<?php

/*
 * This file is part of the godruoyi/ocr.
 *
 * (c) godruoyi <godruoyi@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Godruoyi\OCR\Providers;

use Pimple\Container;
use Godruoyi\OCR\Service\Aliyun\AppCode;
use Godruoyi\OCR\Service\Aliyun\OCRManager;
use Pimple\ServiceProviderInterface;

class AliyunProvider implements ServiceProviderInterface
{
    /**
     * {@inheritdoc}
     */
    public function register(Container $pimple)
    {
        $pimple['aliyun.auth'] = function ($app) {
            return new AppCode($app['config']->get('ocrs.aliyun.appcode'));
        };

        $pimple['aliyun'] = function ($app) {
            return new OCRManager($app['aliyun.auth']);
        };
    }
}
