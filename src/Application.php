<?php

/*
 * This file is part of the godruoyi/ocr.
 *
 * (c) Godruoyi <gmail@godruoyi.com>
 *
 * This source file is subject to the MIT license that is bundled.
 */

namespace Godruoyi\OCR;

use Closure;
use Exception;
use Godruoyi\Container\Container;
use Godruoyi\Container\ServiceProviderInterface;
use Godruoyi\OCR\Contracts\Client;
use InvalidArgumentException;
use Psr\SimpleCache\CacheInterface;

class Application extends Manager
{
    /**
     * The container instance,.
     */
    protected Container $container;

    /**
     * OCR configure.
     */
    protected array|Config $config;

    /**
     * The default service providers.
     */
    protected array $defaultProviders = [
        Providers\HttpServiceProvider::class,
        Providers\LogServiceProvider::class,
        Providers\CacheServiceProvider::class,
    ];

    /**
     * Create application instance.
     *
     * @throws Exception
     */
    public function __construct(array $config = [])
    {
        $this->container = new Container;
        $this->config = new Config($config);

        $this->boot();

        parent::__construct($this->container);
    }

    /**
     * Get ocr container.
     */
    public function getContainer(): Container
    {
        return $this->container;
    }

    /**
     * Boot application for ocr.
     *
     * @throws Exception
     */
    protected function boot(): void
    {
        $this->registerCore();
        $this->registerDefaultProvider();
    }

    /**
     * Register core alias and service.
     *
     *
     * @throws Exception
     */
    protected function registerCore(): void
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
     */
    protected function registerDefaultProvider(): void
    {
        foreach ($this->defaultProviders as $p) {
            $this->register($p);
        }
    }

    /**
     * Register a service to container.
     *
     * @throws Exception
     */
    public function register(mixed $service): void
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
     * Recover __get method.
     *
     * @param  mixed  $key
     * @return mixed
     */
    public function __get($key)
    {
        return $this->driver($key);
    }

    /**
     * RebindCache cache support.
     */
    public function rebindCache(CacheInterface $cache): self
    {
        $this->container->instance('cache', $cache);

        return $this;
    }

    /**
     * Create Aliyun Driver.
     *
     * @return mixed
     */
    protected function createAliyunDriver(): Client
    {
        return $this->container->make(Clients\AliyunClient::class);
    }

    /**
     * Create Baidu Driver.
     *
     * @return mixed
     */
    protected function createBaiduDriver(): Client
    {
        return $this->container->make(Clients\BaiduClient::class);
    }

    /**
     * Create Tencent Driver.
     *
     * @return mixed
     */
    protected function createTencentDriver(): Client
    {
        return $this->container->make(Clients\TencentClient::class);
    }

    /**
     * Create Ai Driver.
     *
     * @return mixed
     */
    protected function createTencentAiDriver(): Client
    {
        throw new InvalidArgumentException('Tencent AI is not supported');
    }

    /**
     *  {@inheritdoc}
     */
    public function getDefaultDriver()
    {
        $default = $this->container['config']->get('default') ?: null;

        // compatible with old config
        return $default ?: $this->container['config']->get('driver');
    }
}
