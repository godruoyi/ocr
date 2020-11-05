<?php

namespace Godruoyi\OCR;

use Closure;
use InvalidArgumentException;
use Godruoyi\Container\Container;
use Godruoyi\OCR\Contracts\Client;
use Psr\Container\ContainerInterface;
use Godruoyi\Container\ServiceProviderInterface;

class Application extends Manager
{
    /**
     * The container instance,
     *
     * @var \Godruoyi\Container\Container
     */
    protected $container;

    /**
     * OCR configurage
     *
     * @var array
     */
    protected $config;

    /**
     * The default service providers.
     *
     * @var array
     */
    protected $defaultProviders = [
        Providers\HttpServiceProvider::class,
        Providers\CacheServiceProvider::class,
        Providers\LogServiceProvider::class,
    ];

    /**
     * Create application instance.
     *
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        $this->container = new Container;
        $this->config = new Config($config);

        $this->boot();

        parent::__construct($this->container);
    }

    /**
     * Get ocr container
     *
     * @return \Godruoyi\Container\Container
     */
    public function getContainer(): Container
    {
        return $this->container;
    }

    /**
     * Boot application for ocr
     *
     * @return void
     */
    protected function boot()
    {
        $this->registerCore();
        $this->registerDefaultProvider();
    }

    /**
     * Registe core alias and service
     *
     * @return mixed
     */
    protected function registerCore()
    {
        $this->container->singleton('app', function ($app) {
            return $app;
        });

        $this->container->singleton('config', function ($app) {
            return $this->config;
        });

        $this->container->alias('app', Container::class);
        $this->container->alias('app', 'Godruoyi\Container\ContainerInterface');

        $this->container->alias('config', Config::class);
    }

    /**
     * Register default service provider.
     *
     * @return mixed
     */
    protected function registerDefaultProvider()
    {
        foreach ($this->defaultProviders as $p) {
            $this->register($p);
        }
    }

    /**
     * Registe a service to container.
     *
     * @param  mixed $service
     *
     * @return void
     */
    public function register($service)
    {
        if (is_string($service) && class_exists($service)) {
            $service = $this->container->make($service);
        }

        if ($service instanceof ServiceProviderInterface) {
            $service->register($this->container);

            return;
        }

        if ($service instanceof Closure) {
            $service($this->container);
        } else {
            throw new InvalidArgumentException('Unsupported registration types');
        }
    }

    /**
     * Recover __get method
     *
     * @param  mixed $key
     *
     * @return mixed
     */
    public function __get($key)
    {
        return $this->driver($key);
    }

    /**
     * Create Aliyun Driver
     *
     * @return miced
     */
    protected function createAliyunDriver(): Client
    {
        return $this->container->make(Clients\AliyunClient::class);
    }

    /**
     * Create Baidu Driver
     *
     * @returnmiced
     */
    protected function createBaiduDriver(): Client
    {
        return $this->container->make(Clients\BaiduClient::class);
    }

    /**
     * Create Tencent Driver
     *
     * @return [miced
     */
    protected function createTencentDriver(): Client
    {
        return $this->container->make(Clients\TencentClient::class);
    }

    /**
     * Create Ai Driver
     *
     * @retmiced
     */
    protected function createTencentAiDriver(): Client
    {
        # code...
    }

    /**
     *  {@inheritdoc}
     */
    public function getDefaultDriver()
    {
        return $this->container['config']->get('driver') ?: 'aliyun';
    }
}
