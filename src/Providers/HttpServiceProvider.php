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
use Godruoyi\OCR\Support\Http;
use Godruoyi\OCR\Support\Response;
use GuzzleHttp\Exception\RequestException as GuzzleRequestException;

class HttpServiceProvider implements ServiceProviderInterface
{
    /**
     *  {@inheritdoc}
     */
    public function register(ContainerInterface $container)
    {
        $container->singleton('http', function ($app) {
            $http = new Http();

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
}
