<?php

/*
 * This file is part of the godruoyi/ocr.
 *
 * (c) Godruoyi <gmail@godruoyi.com>
 *
 * This source file is subject to the MIT license that is bundled.
 */

namespace Godruoyi\OCR\Clients;

use BadMethodCallException;
use Closure;
use Godruoyi\OCR\Contracts\Client as ClientInterface;
use Psr\Http\Message\ResponseInterface;

class Client implements ClientInterface
{
    /**
     * The registered custom methods.
     *
     * @var array
     */
    protected $customMethods = [];

    /**
     * The http client instance.
     *
     * @var \Godruoyi\OCR\Contracts\Request
     */
    protected $request;

    /**
     * Register a custom method Closure.
     *
     * @param \Closure $callback
     *
     * @return $this
     */
    public function extend(string $method, Closure $fn)
    {
        $this->customMethods[$method] = $fn;

        return $this;
    }

    /**
     * Dynamically call the default driver instance.
     *
     * @param string $method
     * @param array $parameters
     *
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        if (!isset($this->customMethods[$method])) {
            throw new BadMethodCallException(sprintf('Method %s::%s does not exist.', static::class, $method));
        }

        $fn = $this->customMethods[$method];

        return $fn($this->request, ...$parameters);
    }

    /**
     * Request.
     *
     * @param string $url
     * @param mixed $images
     *
     * @return array
     */
    public function request($url, $images, array $options = []): ResponseInterface
    {
        return $this->request->send(...func_get_args());
    }
}
