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
use Godruoyi\OCR\Service\TencentAI\OCRManager;
use Godruoyi\OCR\Service\TencentAI\Authorization;

class TencentAIProvider implements ServiceProviderInterface
{
    /**
     * {@inheritdoc}
     */
    public function register(Container $pimple)
    {
        $pimple['tencentai.auth'] = function ($app) {
            return new Authorization(
                $app['config']->get('ocrs.tencentai.app_id'),
                $app['config']->get('ocrs.tencentai.app_key')
            );
        };

        $pimple['tencentai'] = function ($app) {
            return new OCRManager($app['tencentai.auth']);
        };
    }
}
