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

use Monolog\Logger;
use Pimple\Container;
use Godruoyi\OCR\Support\Log;
use Monolog\Handler\StreamHandler;
use Pimple\ServiceProviderInterface;

class LogProvider implements ServiceProviderInterface
{
    /**
     * {@inheritdoc}
     */
    public function register(Container $app)
    {
        if (Log::hasLogger(Log::getLogName())) {
            return;
        }

        $logger = new Logger(Log::getLogName());

        $logger->pushHandler(new StreamHandler(
            $app['config']->get('log.file'),
            $app['config']->get('log.level', Logger::WARNING),
            true,
            $app['config']->get('log.permission', null)
        ));

        Log::addLogger($logger);
    }
}
