<?php

/*
 * This file is part of the godruoyi/ocr.
 *
 * (c) godruoyi <godruoyi@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Godruoyi\OCR\Support;

use Monolog\Registry;

class Log extends Registry
{
    /**
     * Default Logger name
     *
     * @var string
     */
    protected static $loggerName = 'ocr';

    /**
     * Return a new cloned instance with the name changed
     *
     * @return void
     */
    public static function withName($name)
    {
        self::$loggerName = $name;
        self::addLogger(self::withName($name));
    }

    /**
     * Git default logger name
     *
     * @return string
     */
    public static function getLogName()
    {
        return self::$loggerName;
    }

    /**
     * Gets Logger instance from the registry via static method call
     *
     * @param  string                    $name      Name of the requested Logger instance
     * @param  array                     $arguments Arguments passed to static method call
     * @throws \InvalidArgumentException If named Logger instance is not in the registry
     * @return Logger                    Requested instance of Logger
     */
    public static function __callStatic($method, $arguments)
    {
        return forward_static_call_array([self::getInstance(self::getLogName()), $method], $arguments);
    }

    /**
     * Forward call.
     *
     * @param string $method
     * @param array  $args
     *
     * @return mixed
     */
    public function __call($method, $args)
    {
        return call_user_func_array([self::getInstance(self::getLogName()), $method], $args);
    }
}
