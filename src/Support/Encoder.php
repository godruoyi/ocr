<?php

/*
 * This file is part of the godruoyi/ocr.
 *
 * (c) Godruoyi <gmail@godruoyi.com>
 *
 * This source file is subject to the MIT license that is bundled.
 */

namespace Godruoyi\OCR\Support;

class Encoder
{
    /**
     * 在 uri 编码中不能对 '/' 编码
     *
     * @return string
     */
    public static function urlEncodeExceptSlash(string $path)
    {
        return str_replace('%2F', '/', rawurlencode($path));
    }

    /**
     * 生成标准化 QueryString.
     *
     * @return string
     */
    public static function getCanonicalQueryString(array $parameters)
    {
        if (0 == count($parameters)) {
            return '';
        }

        $parameterStrings = [];

        foreach ($parameters as $key => $value) {
            if (0 == strcasecmp('Authorization', $k) || empty($key)) {
                continue;
            }

            $parameterStrings[] = isset($value)
                ? rawurlencode($key).'='.rawurlencode($value)
                : rawurlencode($key).'=';
        }

        sort($parameterStrings);

        return implode('&', $parameterStrings);
    }

    /**
     * 生成标准化 uri，确保开头含有 /.
     *
     * @return string
     */
    public static function getCanonicalURIPath(string $path)
    {
        if (empty($path)) {
            return '/';
        }

        return '/' === $path[0]
            ? self::urlEncodeExceptSlash($path)
            : '/'.self::urlEncodeExceptSlash($path);
    }

    /**
     * 生成标准化 http 请求头串.
     *
     * @return mixed
     */
    public static function getCanonicalHeaders(array $headers)
    {
        // 如果没有 headers，则返回空串
        if (0 == count($headers)) {
            return '';
        }

        $headerStrings = [];

        foreach ($headers as $k => $v) {
            if (null === $k) {
                continue;
            }

            $v = null === $v ? '' : $v;

            $headerStrings[] = strtolower(trim($k)).':'.strtolower(trim($v));
        }

        sort($headerStrings);

        return implode("\n", $headerStrings);
    }
}
