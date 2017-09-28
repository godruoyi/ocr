<?php

namespace Godruoyi\OCR\Contracts;

use Illuminate\Contracts\Container\Container;

interface ServiceProviderInterface
{
    /**
     * Registers services on the given container.
     *
     * This method should only be used to configure services and parameters.
     * It should not get services.
     *
     * @param Container $app A container instance
     */
    public function register(Container $app);
}
