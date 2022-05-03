<?php

namespace Test\Requests;

use Exception;
use Godruoyi\OCR\Application;
use Godruoyi\OCR\Requests\AliyunRequest;
use Godruoyi\OCR\Support\Response;
use InvalidArgumentException;
use Psr\Http\Message\ResponseInterface;
use Test\TestCase;

class AliyunRequestTest extends TestCase
{
    /**
     * @throws Exception
     */
    public function testBasicSend()
    {
        $app = new Application([
            'drivers' => [
                'aliyun' => [
                    'appcode' => 'appcode',
                ],
            ],
        ]);

        $http = $this->mockHttpWithResponse($this->createSuccessResponse());
        $request = new AliyunRequest($http, $app->getContainer());

        $response = $request->send('url', __DIR__ . '/../stubs/common.png');

        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertEquals('OK', $response->getBody()->getContents());
    }

    public function testBasicSendEnableLog()
    {
        $app = new Application([
            'drivers' => [
                'aliyun' => [
                    'appcode' => 'appcode',
                ],
            ],

            'log' => [
                'enable' => true,
                'default' => 'syslog',
            ],
        ]);

        $http = $this->mockHttpWithResponse($this->createSuccessResponse());

        $request = new AliyunRequest($http, $app->getContainer());
        $response = $request->send('url', __DIR__ . '/../stubs/common.png');

        $this->assertInstanceOf(ResponseInterface::class, $response);
        $response->getBody()->rewind();
        $this->assertEquals('OK', $response->getBody()->getContents());
    }

    public function testCanNotUseSecretIDAndSecret()
    {
        $app = new Application([
            'drivers' => [
                'aliyun' => [
                    'appcode' => 'appcode',
                    'secret_id' => 'not_empty',
                    'secret_key' => 'not_empty',
                ],
            ],
        ]);

        $http = $this->mockHttpWithResponse($this->createSuccessResponse());

        $request = new AliyunRequest($http, $app->getContainer());

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Aliyun AppKey and AppSecret has not be completed');

        $request->send('url', __DIR__ . '/../stubs/common.png');
    }

    public function testUseAppCode()
    {
        $app = new Application([
            'drivers' => [
                'aliyun' => [
                    'appcode' => 'appcode',
                ],
            ],
        ]);

        $histories = [];
        $request = new AliyunRequest($app->getContainer()['http'], $app->getContainer());
        $this->mockHttpWithResponseAndHistory($request->getHttp(), $this->createSuccessResponse(), $histories);

        $response = $request->send('url', __DIR__ . '/../stubs/common.png');

        $this->assertEquals('APPCODE appcode', $histories[0]['request']->getHeaderLine('Authorization'));
        $this->assertEquals('OK', $response->getBody()->getContents());
    }

    public function testInvalidImagesShouldThrowAError()
    {
        $this->expectException(InvalidArgumentException::class);

        $request = new AliyunRequest($this->application->getContainer()['http'], $this->application->getContainer());
        $request->send('url', []);
    }

    public function testInvalidFormatType()
    {
        $this->expectException(InvalidArgumentException::class);

        $request = new AliyunRequest($this->application->getContainer()['http'], $this->application->getContainer());
        $request->send('url', [], ['_format' => 'invalid']);
    }

    public function testFormatInputsIsURLImage()
    {
        $http = $this->mockeryHttp();
        $http->shouldReceive('json')
            ->withArgs(function ($url, $options) {
                return $url == 'url' && $options['inputs'][0]['image']['dataValue'] == 'https://example.com';
            })
            ->andReturn(new Response(200, [], 'OK'));

        $request = new AliyunRequest($http, $this->application->getContainer());
        $response = $request->send('url', 'https://example.com');

        $this->assertEquals('OK', $response->getBody()->getContents());
    }

    public function testFormatInputs()
    {
        $http = $this->mockeryHttp();
        $http->shouldReceive('json')
            ->withArgs(function ($url, $options) {
                $image = $options['inputs'][0]['image']['dataValue'];
                return $url == 'url' && is_string($image) && !empty($image);
            })
            ->andReturn(new Response(200, [], 'OK'));

        $request = new AliyunRequest($http, $this->application->getContainer());
        $response = $request->send('url', [__DIR__ . '/../stubs/common.png']);

        $this->assertEquals('OK', $response->getBody()->getContents());
    }

    public function testFormatBasic()
    {
        $http = $this->mockeryHttp();
        $http->shouldReceive('json')
            ->withArgs(function ($url, $options) {
                $image = $options['image'];
                return $url == 'url' && is_string($image) && !empty($image);
            })
            ->andReturn(new Response(200, [], 'OK'));

        $request = new AliyunRequest($http, $this->application->getContainer());
        $response = $request->send('url', [__DIR__ . '/../stubs/common.png'], ['_format' => 'basic']);

        $this->assertEquals('OK', $response->getBody()->getContents());
    }

    public function testFormatBasicIsURLImage()
    {
        $http = $this->mockeryHttp();
        $http->shouldReceive('json')
            ->withArgs(function ($url, $options) {
                return $url == 'url' && $options['image'] == 'https://example.com';
            })
            ->andReturn(new Response(200, [], 'OK'));

        $request = new AliyunRequest($http, $this->application->getContainer());
        $response = $request->send('url', ['https://example.com'], ['_format' => 'basic']);

        $this->assertEquals('OK', $response->getBody()->getContents());
    }

    public function testFormatImgOrUrl()
    {
        $http = $this->mockeryHttp();
        $http->shouldReceive('json')
            ->withArgs(function ($url, $options) {
                return $url == 'url' && $options['url'] == 'https://example.com';
            })
            ->once()
            ->andReturn(new Response(200, [], 'OK'));

        $http->shouldReceive('json')
            ->withArgs(function ($url, $options) {
                return $url == 'url' && is_string($options['img']) && !empty($options['img']);
            })
            ->once()
            ->andReturn(new Response(200, [], 'OK'));

        $request = new AliyunRequest($http, $this->application->getContainer());

        $response = $request->send('url', ['https://example.com'], ['_format' => 'imgorurl']);
        $this->assertEquals('OK', $response->getBody()->getContents());

        $response = $request->send('url', [__DIR__ . '/../stubs/common.png'], ['_format' => 'imgorurl']);
        $this->assertEquals('OK', $response->getBody()->getContents());
    }
}
