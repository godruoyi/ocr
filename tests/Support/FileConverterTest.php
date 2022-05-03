<?php

namespace Test\Support;

use Godruoyi\OCR\Support\FileConverter;
use GuzzleHttp\Psr7\Response;
use RuntimeException;
use SplFileInfo;
use stdClass;
use Test\TestCase;

class FileConverterTest extends TestCase
{

    public function testIsString()
    {
        $this->assertFalse(is_string(FileConverter::isString('test')));
    }

    public function testIsResource()
    {
        $this->assertTrue(FileConverter::isResource(fopen('php://temp', 'r')));
    }

    public function testIsUrl()
    {
        $this->assertTrue(FileConverter::isUrl('https://www.baidu.com'));
    }

    public function testGetContent()
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
                'file' => @fopen(__DIR__ . '/../stubs/config.php', 'r'),
            ],
            [
                'name' => 'test isSplFileInfo',
                'file' => new SplFileInfo(__DIR__ . '/../stubs/config.php'),
            ]
        ];

        foreach ($tests as $t) {
            $this->assertNotEmpty(FileConverter::getContent($t['file']), $t['name']);
        }
    }

    public function testGetContentImage()
    {
        $image = imagecreate(100, 100);

        $this->expectException(RuntimeException::class);
        $this->assertNotEmpty(FileConverter::getContent($image));
    }

    public function testGetContentInvalidString()
    {
        $this->expectException(RuntimeException::class);
        $this->assertNotEmpty(FileConverter::getContent('invalid'));
    }

    public function testGetContentInvalidType()
    {
        $this->expectException(RuntimeException::class);
        $this->assertNotEmpty(FileConverter::getContent(new stdClass()));
    }

    public function testIsImage()
    {
        $this->assertTrue(FileConverter::isImage(imagecreate(100, 100)));
    }

    public function testIsSplFileInfo()
    {
        $this->assertTrue(FileConverter::isSplFileInfo(new SplFileInfo(__DIR__ . '/../stubs/config.php')));
    }

    public function testGetOnlineImageContent()
    {
        $http = $this->mockHttpWithResponse(new Response(200, [], 'test'));
        FileConverter::setHttp($http);

        $this->assertEquals('', FileConverter::getOnlineImageContent(''));
        $this->assertEquals('test', FileConverter::getOnlineImageContent('https://example.com'));
    }

    public function testIsFile()
    {
        $this->assertTrue(FileConverter::isFile(__DIR__ . '/../stubs/config.php'));
    }

    public function testToBase64Encode()
    {
        $http = $this->mockHttpWithResponse(new Response(200, [], 'test'));
        FileConverter::setHttp($http);

        $this->assertEquals('', FileConverter::toBase64Encode(''));
        $this->assertEquals(base64_encode('test'), FileConverter::toBase64Encode('https://example.com'));
    }
}
