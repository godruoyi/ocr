<?php

namespace Godruoyi\OCR\Support;

use Illuminate\Support\Arr;
use InvalidArgumentException;

class Config
{
    /**
     * The config settings.
     *
     * @var array
     */
    protected $configs;

    /**
     * Instance
     *
     * @param string|array $config
     */
    public function __construct($config = null)
    {
        if (is_array($config)) {
            $this->configs = $config;
        } elseif (is_file($config) && file_exists($config)) {
            $this->configs = require $config;
        } else {
            throw new InvalidArgumentException("config must be array or php file");
        }
    }

    /**
     * Get an item from an array using "dot" notation.
     *
     * @param  string  $key
     * @param  mixed   $default
     *
     * @return mixed
     */
    public function get($name = null, $default = null)
    {
        return Arr::get($this->configs, $name, $default);
    }

    /**
     * Set an array item to a given value using "dot" notation.
     *
     * If no key is given to the method, the entire array will be replaced.
     *
     * @param  string  $key
     * @param  mixed   $value
     * @return array
     */
    public function set($key, $value)
    {
        Arr::set($this->configs, $key, $value);

        return $this;
    }
}
