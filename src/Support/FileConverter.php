<?php

/*
 * This file is part of the godruoyi/ocr.
 *
 * (c) Godruoyi <gmail@godruoyi.com>
 *
 * This source file is subject to the MIT license that is bundled.
 */

namespace Godruoyi\OCR\Support;

use Exception;
use RuntimeException;
use SplFileInfo;

class FileConverter
{
    private static $http;

    /**
     * Converter Image To String.
     *
     * @param string|object|resource $image
     *
     * @return string
     */
    public static function toBase64Encode($image)
    {
        if (empty($image)) {
            return '';
        }

        return base64_encode(self::getContent($image));
    }

    /**
     * Get image Content, support url/file/SplFileInfo.
     *
     * @param string|\SplFileInfo|resource $image
     *
     * @return string
     */
    public static function getContent($image)
    {
        switch (true) {
            case self::isFile($image):
                return file_get_contents($image);
            case self::isUrl($image):
                return self::getOnlineImageContent($image);
            case self::isImage($image):
                throw new RuntimeException('we can not support image resource, if you want to use image resource, please convert it to string.');
            case self::isResource($image):
                return stream_get_contents($image);
            case self::isSplFileInfo($image):
                return file_get_contents($image->getRealPath());
            default:
                throw new RuntimeException('not support image type.');
        }
    }

    public static function getOnlineImageContent($url)
    {
        if (empty($url)) {
            return '';
        }

        try {
            $http = self::$http ?? new Http();

            $response = $http->get($url);

            return $response->getBody()->getContents();
        } catch (Exception $e) {
            throw new RuntimeException("get image content failed, url: {$url}, err: {$e->getMessage()}");
        }
    }

    /**
     * Determine the given file has a file.
     *
     * @param mixed $file
     *
     * @return bool
     */
    public static function isString($file)
    {
        return is_string($file) && (!self::isUrl($file));
    }

    /**
     * Determine the given file has a active url.
     *
     * @param mixed $file
     *
     * @return bool
     */
    public static function isUrl($file)
    {
        return false !== filter_var($file, FILTER_VALIDATE_URL);
    }

    /**
     * Determine the given file has a active url.
     *
     * @param mixed $file
     *
     * @return bool
     */
    public static function isFile($file)
    {
        return is_string($file) && is_file($file) && file_exists($file);
    }

    /**
     * Determine the given file has a image type.
     *
     * @param mixed $file
     *
     * @return bool
     */
    public static function isImage($file)
    {
        if (phpversion() >= 8.0) {
            return $file instanceof \GdImage;
        }

        return self::isResource($file) && get_resource_type($file) === 'gd';
    }

    /**
     * Determine the given file has Rescouve stream.
     *
     * @param mixed $resource
     *
     * @return bool
     */
    public static function isResource($resource)
    {
        return is_resource($resource);
    }

    /**
     * Determine the given file has SplFileInfo instance.
     *
     * @param mixed $splFile
     *
     * @return bool
     */
    public static function isSplFileInfo($splFile)
    {
        return $splFile instanceof SplFileInfo;
    }

    /**
     * Set http instance.
     *
     * @param Http $http
     * @return void
     */
    public static function setHttp(Http $http)
    {
        self::$http = $http;
    }
}
