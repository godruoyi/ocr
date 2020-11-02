<?php

namespace Godruoyi\OCR\Providers;

use Godruoyi\Container\ContainerInterface;
use Godruoyi\Container\ServiceProviderInterface;

class CacheServiceProvider implements ServiceProviderInterface
{
    /**
     *  {@inheritdoc}
     */
    public function register(ContainerInterface $container)
    {
        $container->singleton('cache', function ($app) {
            return null;
        });
    }
}
