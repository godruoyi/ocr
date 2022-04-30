<?php

/*
 * This file is part of the godruoyi/ocr.
 *
 * (c) Godruoyi <gmail@godruoyi.com>
 *
 * This source file is subject to the MIT license that is bundled.
 */

namespace Test;

use Godruoyi\Container\ContainerInterface;
use Godruoyi\Container\ServiceProviderInterface;

/**
 * service provider interface.
 *
 * @author Godruoyi
 */
class CustomServiceProvider implements ServiceProviderInterface
{
    /**
     * Registers services on the given container.
     *
     * This method should only be used to configure services and parameters.
     * It should not get services.
     *
     * @param ContainerInterface $container A container instance
     */
    public function register(ContainerInterface $container)
    {
        $container->singleton('custom', function () {
            return 'custom';
        });

        $container->alias('custom', 'custom1');
        $container->alias('custom', 'custom2');
        $container->alias('custom', 'custom3');
    }
}
