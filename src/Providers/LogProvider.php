<?php

namespace Godruoyi\OCR\Providers;

use Monolog\Logger;
use Godruoyi\OCR\Support\Log;
use Monolog\Handler\StreamHandler;
use Illuminate\Contracts\Container\Container;
use Godruoyi\OCR\Contracts\ServiceProviderInterface;

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
