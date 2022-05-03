<?php

/*
 * This file is part of the godruoyi/ocr.
 *
 * (c) Godruoyi <gmail@godruoyi.com>
 *
 * This source file is subject to the MIT license that is bundled.
 */

namespace Test;

use Godruoyi\OCR\Application;
use Godruoyi\OCR\Support\Http;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Response as GuzzleResponse;
use Mockery;
use PHPUnit\Framework\TestCase as BaseTestCase;

class TestCase extends BaseTestCase
{
    protected $config;

    protected $application;

    /**
     * Sets up the fixture, for example, open a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp(): void
    {
        $this->config = require __DIR__ . '/stubs/config.php';
        $this->application = new Application($this->config);
    }

    /**
     * Clean up after running a test.
     */
    public function tearDown(): void
    {
        Mockery::close();

        $this->application = null;
        $this->config = null;
    }

    protected function mockHttpWithResponseAndHistory($http, $response, &$container = []): Http
    {
        $http = $this->mockHttpWithResponse($response, $http);

        $http->middlewares(Middleware::history($container), 'history');

        return $http;
    }

    protected function mockHttpWithResponse($response, $http = null): Http
    {
        if (!$http) {
            $http = new Http();
        }

        $http->customHttpHandler(function ($stack) use ($response) {
            $stack->setHandler(new MockHandler(is_array($response) ? $response : [$response]));
        });

        return $http;
    }

    protected function createSuccessResponse(): GuzzleResponse
    {
        return new GuzzleResponse(200, [], 'OK');
    }

    protected function mockeryHttp()
    {
        $http = Mockery::mock('HTTP, ' . Http::class);
        $http->shouldReceive('middlewares')
            ->andReturnNull();

        return $http;
    }
}
