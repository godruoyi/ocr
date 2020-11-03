<?php

namespace Godruoyi\OCR\Requests;

use InvalidArgumentException;
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
        $this->init();
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
     * custon init method.
     *
     * @return void
     */
    protected function init()
    {
    }

    /**
     * Translation $images and $options to guzzle http options
     *
     * @param  mixed $images
     * @param  array  $options
     *
     * @return array
     */
    abstract public function request($url, $images, array $options = []) : Response;

    /**
     * Get app instance
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
     * @param  mixed $images
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
