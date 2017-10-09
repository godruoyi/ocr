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
use Doctrine\Common\Cache\FilesystemCache;
use Doctrine\Common\Cache\Cache;

class CacheProvider implements ServiceProviderInterface
{
    /**
     * {@inheritdoc}
     */
    public function register(Container $pimple)
    {
        $pimple['cache'] = function () {
            return new FilesystemCache(sys_get_temp_dir());
        };
    }
}
