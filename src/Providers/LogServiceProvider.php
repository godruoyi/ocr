<?php

/*
 * This file is part of the godruoyi/ocr.
 *
 * (c) Godruoyi <gmail@godruoyi.com>
 *
 * This source file is subject to the MIT license that is bundled.
 */

namespace Godruoyi\OCR\Providers;

use Godruoyi\Container\ContainerInterface;
use Godruoyi\Container\ServiceProviderInterface;
use Godruoyi\OCR\Support\Logger;

class LogServiceProvider implements ServiceProviderInterface
{
    /**
     *  {@inheritdoc}
     */
    public function register(ContainerInterface $container)
    {
        $container->singleton('logger', function ($app) {
            return new Logger($app['config']->get('log'));
        });

        $container->alias('logger', 'log');
    }
}
