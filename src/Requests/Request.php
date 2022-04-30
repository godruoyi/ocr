<?php

/*
 * This file is part of the godruoyi/ocr.
 *
 * (c) Godruoyi <gmail@godruoyi.com>
 *
 * This source file is subject to the MIT license that is bundled.
 */

namespace Godruoyi\OCR\Requests;

use Godruoyi\Container\ContainerInterface;
use Godruoyi\OCR\Contracts\Request as RequestInterface;
use Godruoyi\OCR\Support\Arr;
use Godruoyi\OCR\Support\Http;
use GuzzleHttp\MessageFormatter;
use GuzzleHttp\Middleware;
use InvalidArgumentException;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LogLevel;

abstract class Request implements RequestInterface
{
    /**
     * The http instance.
     *
     * @var \Godruoyi\OCR\Support\Http
     */
    protected $http;

    /**
     * Container instance.
     *
     * @var \Godruoyi\Container\ContainerInterface
     */
    protected $app;

    /**
     * Auto regist http and container instance.
     *
     * @param ContainerInterface $container
     */
    public function __construct(Http $http, ContainerInterface $app)
    {
        // Clone new client
        $this->http = clone $http;
        $this->app = $app;

        $this->registerMiddlewares();
        $this->init();
    }

    /**
     * Register middleware to http.
     */
    public function registerMiddlewares()
    {
        foreach ($this->middlewares() as $name => $middleware) {
            $this->http->middlewares($middleware, $name);
        }

        if ($this->app['config']->get('log.enable')) {
            $this->http->middlewares($this->logMiddleware(), 'orc.log');
        }
    }

    /**
     * Register global http log middleware.
     *
     * @param ContainerInterface $app
     *
     * @return callable
     */
    protected function logMiddleware()
    {
        $driver = $this->app['config']->get('log.default');
        $config = $this->app['config']->get('log.channels.' . $driver);
        $logger = $this->app['logger'];

        // because base64 image is very big, we just record request header if you not set formater in you log configurage.
        $defaultFormatter = ">>>>>>>>\n{req_headers}\n\n<<<<<<<<\n{response}\n--------\nError: {error}\n\n";

        $formatter = Arr::get($config, 'formatter', $defaultFormatter);
        $level = Arr::get($config, 'level', LogLevel::DEBUG);

        return Middleware::log($logger, new MessageFormatter($formatter), $level);
    }

    /**
     * Middlewares.
     * [
     *     'aliyun' => callable
     * ].
     */
    protected function middlewares(): array
    {
        return [];
    }

    /**
     * custon init method.
     */
    protected function init()
    {
    }

    /**
     * Translation $images and $options to guzzle http options.
     *
     * @param mixed $images
     *
     * @return array
     */
    abstract public function send($url, $images, array $options = []): ResponseInterface;

    /**
     * Get app instance.
     *
     * @return \Godruoyi\Container\ContainerInterface
     */
    public function getApp()
    {
        return $this->app;
    }

    /**
     * Get http instance.
     *
     * @return \Godruoyi\OCR\Support\Http
     */
    public function getHttp()
    {
        return $this->http;
    }

    /**
     * Filter images to one.
     *
     * @param mixed $images
     *
     * @return mixed
     */
    protected function filterOneImage($images, $message = 'Only one image can be operated at a time.')
    {
        if (is_array($images)) {
            if (($count = count($images)) >= 1) {
                $count > 1 && $this->app['log']->warning($message);

                return $images[0];
            }

            throw new InvalidArgumentException('The recognize image cannot be empty.');
        }

        return $images;
    }
}
