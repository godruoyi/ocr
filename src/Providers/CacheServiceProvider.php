<?php

namespace Godruoyi\OCR\Providers;

use Godruoyi\Container\ContainerInterface;
use Godruoyi\Container\ServiceProviderInterface;
use Psr\Cache\CacheItemPoolInterface;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;

class CacheServiceProvider implements ServiceProviderInterface
{
    public function register(ContainerInterface $container)
    {
        $container->singleton('cache', function ($container) {
            return new FilesystemAdapter('ocr.cache');
        });

        $container->alias('cache', CacheItemPoolInterface::class);
    }
}