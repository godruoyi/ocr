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
use Pimple\ServiceProviderInterface;
use Godruoyi\OCR\Service\Baidu\AccessToken;
use Godruoyi\OCR\Service\Baidu\OCRManager;

class BaiduProvider implements ServiceProviderInterface
{
    /**
     * {@inheritdoc}
     */
    public function register(Container $pimple)
    {
        $pimple['baidu.auth'] = function ($app) {
            return new AccessToken(
                $app['config']->get('ocrs.baidu.app_key'),
                $app['config']->get('ocrs.baidu.secret_key'),
                $app['cache']
            );
        };

        $pimple['baidu'] = function ($app) {
            return new OCRManager($app['baidu.auth']);
        };
    }
}
