<?php

namespace Godruoyi\OCR\Providers;

use Godruoyi\OCR\Support\Logger;
use Godruoyi\Container\ContainerInterface;
use Godruoyi\Container\ServiceProviderInterface;

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
