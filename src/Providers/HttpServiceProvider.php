<?php

namespace Godruoyi\OCR\Providers;

use Godruoyi\OCR\Support\Arr;
use Godruoyi\OCR\Support\Http;
use GuzzleHttp\MessageFormatter;
use GuzzleHttp\Middleware;
use Psr\Log\LogLevel;
use Godruoyi\OCR\Support\Response;
use Godruoyi\Container\ContainerInterface;
use Godruoyi\Container\ServiceProviderInterface;
use GuzzleHttp\Exception\RequestException as GuzzleRequestException;

class HttpServiceProvider implements ServiceProviderInterface
{
    /**
     *  {@inheritdoc}
     */
    public function register(ContainerInterface $container)
    {
        $container->singleton('http', function ($app) {
            $http = new Http;

            if (!$app['config']->get('disable_log')) {
                $http->middlewares($this->globalLogHandler($app), 'orc.log');
            }

            $http->customHttpHandler($this->processHttpError());

            return $http;
        });

        $container->alias('http', Http::class);
    }

    /**
     * Process exception when request failure.
     *
     * @return callable
     */
    protected function processHttpError()
    {
        return function ($handler) {
            $handler->before('http_errors', function (callable $h) {
                return function ($request, array $options) use ($h) {
                    return $h($request, $options)->then(function ($response) {
                        return Response::createFromGuzzleHttpResponse($response);
                    }, function ($e) {
                        if ($e instanceof GuzzleRequestException && $e->hasResponse()) {
                            return Response::createFromGuzzleHttpResponse($e->getResponse());
                        }

                        throw $e;
                    });
                };
            }, 'ocr.exception');
        };
    }

    /**
     * Register global http log middleware.
     *
     * @param  ContainerInterface $app
     *
     * @return callable
     */
    protected function globalLogHandler(ContainerInterface $app)
    {
        $driver = $app['config']->get('log.default');
        $config = $app['config']->get('log.channels.'.$driver);
        $logger = $app['logger'];

        $formatter = Arr::get($config, 'formatter', MessageFormatter::DEBUG);
        $level     = Arr::get($config, 'level', LogLevel::DEBUG);

        return Middleware::log($logger, new MessageFormatter($formatter), $level);
    }
}
