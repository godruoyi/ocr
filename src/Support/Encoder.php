<?php

namespace Godruoyi\OCR\Support;

class Encoder
{
    /**
     * 在 uri 编码中不能对 '/' 编码
     *
     * @param  string $path
     *
     * @return string
     */
    public static function urlEncodeExceptSlash(string $path)
    {
        return str_replace("%2F", "/", rawurlencode($path));
    }

    /**
     * 生成标准化 QueryString
     *
     * @param  array  $parameters
     *
     * @return string
     */
    public static function getCanonicalQueryString(array $parameters)
    {
        if (count($parameters) == 0) {
            return '';
        }

        $parameterStrings = [];

        foreach ($parameters as $key => $value) {
            if (strcasecmp('Authorization', $k) == 0 || empty($key)) {
                continue;
            }

            $parameterStrings[] = isset($value)
                ? rawurlencode($key) . '=' . rawurlencode($value)
                : rawurlencode($key) . '=';
        }

        sort($parameterStrings);

        return implode('&', $parameterStrings);
    }

    /**
     * 生成标准化 uri，确保开头含有 /
     *
     * @param  string $path
     *
     * @return string
     */
    public static function getCanonicalURIPath(string $path)
    {
        if (empty($path)) {
            return '/';
        }

        return $path[0] === '/'
            ? self::urlEncodeExceptSlash($path)
            : '/' . self::urlEncodeExceptSlash($path);
    }

    /**
     * 生成标准化 http 请求头串
     *
     * @param  array  $headers
     *
     * @return mixed
     */
    public static function getCanonicalHeaders(array $headers)
    {
        //如果没有headers，则返回空串
        if (count($headers) == 0) {
            return '';
        }

        $headerStrings = [];

        foreach ($headers as $k => $v) {
            if ($k === null) {
                continue;
            }

            $v = $v === null ? '' : $v;

            $headerStrings[] = strtolower(trim($k)) . ':' . strtolower(trim($v));
        }

        sort($headerStrings);

        return implode("\n", $headerStrings);
    }
}
