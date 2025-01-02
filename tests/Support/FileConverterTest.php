<?php

/*
 * This file is part of the godruoyi/ocr.
 *
 * (c) Godruoyi <gmail@godruoyi.com>
 *
 * This source file is subject to the MIT license that is bundled.
 */

namespace Test\Support;

use Godruoyi\OCR\Support\FileConverter;
use GuzzleHttp\Psr7\Response;
use RuntimeException;
use SplFileInfo;
use stdClass;
use Test\TestCase;

class FileConverterTest extends TestCase
{
    public function test_is_string()
    {
        $this->assertFalse(is_string(FileConverter::isString('test')));
    }

    public function test_is_resource()
    {
        $this->assertTrue(FileConverter::isResource(fopen('php://temp', 'r')));
    }

    public function test_is_url()
    {
        $this->assertTrue(FileConverter::isUrl('https://www.baidu.com'));
    }

    public function test_get_content()
    {
        $http = $this->mockHttpWithResponse(new Response(200, [], 'test'));
        FileConverter::setHttp($http);

        $tests = [
            [
                'name' => 'test isUrl',
                'file' => 'https://example.com',
            ],
            [
                'name' => 'test isResource',
                'file' => @fopen(__DIR__.'/../stubs/config.php', 'r'),
            ],
            [
                'name' => 'test isSplFileInfo',
                'file' => new SplFileInfo(__DIR__.'/../stubs/config.php'),
            ],
        ];

        foreach ($tests as $t) {
            $this->assertNotEmpty(FileConverter::getContent($t['file']), $t['name']);
        }
    }

    public function test_get_content_image()
    {
        $image = imagecreate(100, 100);

        $this->expectException(RuntimeException::class);
        $this->assertNotEmpty(FileConverter::getContent($image));
    }

    public function test_get_content_invalid_string()
    {
        $this->expectException(RuntimeException::class);
        $this->assertNotEmpty(FileConverter::getContent('invalid'));
    }

    public function test_get_content_invalid_type()
    {
        $this->expectException(RuntimeException::class);
        $this->assertNotEmpty(FileConverter::getContent(new stdClass));
    }

    public function test_is_image()
    {
        $this->assertTrue(FileConverter::isImage(imagecreate(100, 100)));
    }

    public function test_is_spl_file_info()
    {
        $this->assertTrue(FileConverter::isSplFileInfo(new SplFileInfo(__DIR__.'/../stubs/config.php')));
    }

    public function test_get_online_image_content()
    {
        $http = $this->mockHttpWithResponse(new Response(200, [], 'test'));
        FileConverter::setHttp($http);

        $this->assertSame('', FileConverter::getOnlineImageContent(''));
        $this->assertSame('test', FileConverter::getOnlineImageContent('https://example.com'));
    }

    public function test_is_file()
    {
        $this->assertTrue(FileConverter::isFile(__DIR__.'/../stubs/config.php'));
    }

    public function test_to_base64_encode()
    {
        $http = $this->mockHttpWithResponse(new Response(200, [], 'test'));
        FileConverter::setHttp($http);

        $this->assertSame('', FileConverter::toBase64Encode(''));
        $this->assertSame(base64_encode('test'), FileConverter::toBase64Encode('https://example.com'));
    }
}
