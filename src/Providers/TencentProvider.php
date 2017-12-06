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
use Godruoyi\OCR\Service\Tencent\OCRManager;
use Godruoyi\OCR\Service\Tencent\Authorization;

class TencentProvider implements ServiceProviderInterface
{
    /**
     * {@inheritdoc}
     */
    public function register(Container $pimple)
    {
        $pimple['tencent.auth'] = function ($app) {
            return new Authorization(
                $app['config']->get('ocrs.tencent.app_id'),
                $app['config']->get('ocrs.tencent.secret_id'),
                $app['config']->get('ocrs.tencent.secret_key'),
                $app['config']->get('ocrs.tencent.bucket')
            );
        };

        $pimple['tencent'] = function ($app) {
            return new OCRManager($app['tencent.auth']);
        };
    }
}
