<?php

namespace Godruoyi\OCR\Support;

use Exception;
use RuntimeException;
use SplFileInfo;
use Psr\Http\Message\StreamInterface;

class FileConverter
{
    /**
     * Converter Image To String
     *
     * @param  string|Object|Resource $image
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
     * Get image Content, support url/file/SplFileInfo
     *
     * @param  string|\SplFileInfo|Resource $image
     *
     * @return string
     */
    public static function getContent($image)
    {
        switch (true) {
            case self::isUrl($image):
                return self::getOnlineImageContent($image);
            case self::isImage($image):
                return file_get_contents($image);
            case self::isResource($image):
                return stream_get_contents($image);
            case self::isSplFileInfo($image):
                return file_get_contents($image->getRealPath());
            case self::isString($image):
                // When the image was String type, We just think it's a local image
                // And he does not exist
                throw new RuntimeException("file {$image} has not exist.");
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
            return (string) (new Http)->get($url)->getBody();
        } catch (Exception $e) {
            return '';
        }
    }

    /**
     * Determine the given file has a file
     *
     * @param  mixed $file
     *
     * @return boolean
     */
    public static function isString($file)
    {
        return is_string($file) && (! self::isUrl($file));
    }

    /**
     * Determine the given file has a active url
     *
     * @param  mixed  $file
     * @return boolean
     */
    public static function isUrl($file)
    {
        return filter_var($file, FILTER_VALIDATE_URL) !== false;
    }

    /**
     * Determine the given file has a active url
     *
     * @param  mixed  $file
     * @return boolean
     */
    public static function isFile($file)
    {
        return is_string($file) && is_file($file) && file_exists($file);
    }

    /**
     * Determine the given file has a image type
     *
     * @param  mixed  $file
     *
     * @return boolean
     */
    public static function isImage($file)
    {
        try {
            $level = error_reporting(E_ERROR | E_PARSE);
            $isImage = self::isFile($file) && getimagesize($file) !== false;
            error_reporting($level);

            return $isImage;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Determine the given file has Rescouve stream
     *
     * @param  mixed  $resource
     * @return boolean
     */
    public static function isResource($resource)
    {
        return is_resource($resource);
    }

    /**
     * Determine the given file has SplFileInfo instance
     *
     * @param  mixed  $splFile
     * @return boolean
     */
    public static function isSplFileInfo($splFile)
    {
        return $splFile instanceof SplFileInfo;
    }
}
