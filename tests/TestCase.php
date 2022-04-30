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
use Godruoyi\OCR\Requests\AliyunRequest;
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

    /**
     * @param $response
     *
     * @return void
     */
    public function mockAliyunResponse($response, $times = 1)
    {
        $app = $this->application->getContainer();

        $app->bind(AliyunRequest::class, function () use ($response, $times) {
            $mockRequest = Mockery::mock("Request, " . AliyunRequest::class);
            $mockRequest->shouldReceive('send')
                ->times($times)
                ->andReturn($response);

            return $mockRequest;
        });
    }
}
