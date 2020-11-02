<?php

namespace Godruoyi\OCR\Requests;

use Godruoyi\OCR\Support\Http;
use Godruoyi\OCR\Support\Response;
use Godruoyi\Container\ContainerInterface;
use Godruoyi\OCR\Contracts\Request as RequestInterface;

abstract class Request implements RequestInterface
{
    /**
     * The http instance.
     *
     * @var \Godruoyi\OCR\Support\Http
     */
    protected $http;

    /**
     * Container instance
     *
     * @var \Godruoyi\Container\ContainerInterface
     */
    protected $app;

    /**
     * The default http request method.
     *
     * @var string
     */
    protected $method = 'json';

    /**
     * Auto regist http and container instance.
     *
     * @param Http               $http
     * @param ContainerInterface $container
     */
    public function __construct(Http $http, ContainerInterface $app)
    {
        // Clone new client
        $this->http = clone $http;
        $this->app = $app;

        $this->registerMiddlewares();
    }

    /**
     * Register middleware to http
     *
     * @return void
     */
    public function registerMiddlewares()
    {
        foreach ($this->middlewares() as $name => $middleware) {
            $this->http->middlewares($middleware, $name);
        }
    }

    /**
     * Middlewares.
     * [
     *     'aliyun' => callable
     * ]
     *
     * @return array
     */
    protected function middlewares(): array
    {
        return [];
    }

    /**
     * Translation $images and $options to guzzle http options
     *
     * @param  mixed $images
     * @param  array  $options
     *
     * @return array
     */
    abstract public function mergeOptions($images, array $options): array;

    /**
     * {@inheritdoc}
     */
    public function request($url, $images, array $options = []) : Response
    {
        return $this->http->{$this->method}($url, $this->mergeOptions($images, $options));
    }
}
