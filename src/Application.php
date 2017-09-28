<?php

namespace Godruoyi\OCR;

use Godruoyi\OCR\Support\Config;
use Illuminate\Container\Container;
use Godruoyi\OCR\Contracts\ServiceProviderInterface;

class Application extends Container
{
    /**
     * Default Providers
     *
     * @var array
     */
    protected $providers = [
        Providers\LogProvider::class
    ];

    /**
     * Initeral Application Instance
     *
     * @param string|array $configs
     */
    public function __construct($configs = null)
    {
        $this['config'] = new Config($configs);

        $this->registerProviders();
        $this->registerBase();
    }

    /**
     * Register Service Provider
     *
     * @param  ServiceProviderInterface $provider
     *
     * @return void
     */
    public function register(ServiceProviderInterface $provider)
    {
        $provider->register($this);
    }

    /**
     * Register Provider
     *
     * @return void
     */
    protected function registerProviders()
    {
        foreach ($this->providers as $provider) {
            $this->register(new $provider);
        }
    }

    /**
     * Register Base Binds
     *
     * @return void
     */
    protected function registerBase()
    {
        self::$instance = $this;

        $this['app']      = $this;
        $this[Container   ::class] = $this;
        $this[Application ::class] = $this;
    }
}
