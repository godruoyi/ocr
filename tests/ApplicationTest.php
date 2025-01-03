<?php

/*
 * This file is part of the godruoyi/ocr.
 *
 * (c) Godruoyi <gmail@godruoyi.com>
 *
 * This source file is subject to the MIT license that is bundled.
 */

namespace Test;

use Godruoyi\Container\Container;
use Godruoyi\OCR\Application;
use Godruoyi\OCR\Clients\AliyunClient;
use Godruoyi\OCR\Clients\BaiduClient;
use Godruoyi\OCR\Clients\TencentClient;
use Godruoyi\OCR\Config;
use Godruoyi\OCR\Support\Response;
use InvalidArgumentException;
use Psr\SimpleCache\CacheInterface;
use Symfony\Component\Cache\Adapter\NullAdapter;
use Symfony\Component\Cache\Psr16Cache;

class ApplicationTest extends TestCase
{
    public function test_basic()
    {
        $application = new Application($this->config);
        $this->assertInstanceOf(Application::class, $application);

        $app = $application->getContainer();

        $this->assertInstanceOf(\Psr\Container\ContainerInterface::class, $app);
        $this->assertInstanceOf(Config::class, $app['config']);
    }

    public function test_app_has_registered_core_component()
    {
        $application = new Application(['a' => 1]);
        $app = $application->getContainer();

        $this->assertTrue($app->bound('config'));
        $this->assertTrue($app->bound('http'));
        $this->assertTrue($app->bound('log'));
    }

    public function test_get_container()
    {
        $application = new Application(['a' => 1]);
        $this->assertInstanceOf(Container::class, $application->getContainer());
    }

    public function test_register_service_provider()
    {
        $this->application->register(CustomServiceProvider::class);

        $app = $this->application->getContainer();

        $this->assertTrue($app->bound('custom'));
        $this->assertTrue($app->bound('custom1'));
        $this->assertTrue($app->bound('custom2'));
        $this->assertTrue($app->bound('custom3'));

        $this->assertSame('custom', $app->get('custom'));
        $this->assertSame('custom', $app->get('custom1'));
        $this->assertSame('custom', $app->get('custom2'));
        $this->assertSame('custom', $app->get('custom3'));
    }

    public function test_register_service_provider_not_exists()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Unsupported registration types');

        $this->application->register('not_exists');
    }

    public function test_register_service_closure()
    {
        $this->application->register(function ($app) {
            $app->singleton('custom', function () {
                return 'custom';
            });

            $app->alias('custom', 'custom1');
            $app->alias('custom', 'custom2');
            $app->alias('custom', 'custom3');
        });

        $app = $this->application->getContainer();

        $this->assertTrue($app->bound('custom'));
        $this->assertTrue($app->bound('custom1'));
        $this->assertTrue($app->bound('custom2'));
        $this->assertTrue($app->bound('custom3'));

        $this->assertSame('custom', $app->get('custom'));
        $this->assertSame('custom', $app->get('custom1'));
        $this->assertSame('custom', $app->get('custom2'));
        $this->assertSame('custom', $app->get('custom3'));
    }

    public function test_get()
    {
        $this->assertInstanceOf(AliyunClient::class, $this->application->aliyun);
        $this->assertInstanceOf(BaiduClient::class, $this->application->baidu);
        $this->assertInstanceOf(TencentClient::class, $this->application->tencent);
    }

    public function test_get_undefined_service()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Driver [foo] not supported.');

        $this->application->foo->x();
    }

    public function test_get_default_driver()
    {
        $app = new Application([
            'default' => 'aliyun',
        ]);

        $this->assertSame('aliyun', $app->getDefaultDriver());
    }

    public function test_get_default_driver_empty()
    {
        $app = new Application;

        $this->assertEmpty($app->getDefaultDriver());
    }

    public function test_get_default_driver2()
    {
        $this->assertSame('aliyun', $this->application->getDefaultDriver());
    }

    public function test_driver()
    {
        $app = new Application;

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Unable to resolve NULL driver for [Godruoyi\OCR\Application].');
        $app->driver(null);
    }

    public function test_create_driver_not_exists()
    {
        $app = new Application([
            'default' => 'foo',
        ]);

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Driver [foo] not supported.');
        $app->driver(null);
    }

    public function test_call_driver_many_times_should_same_instance()
    {
        $app = new Application;

        $a = $app->driver('aliyun');
        $b = $app->driver('aliyun');

        $this->assertSame($a, $b);
    }

    public function test_get_drivers()
    {
        $app = new Application;

        $this->assertSame([], array_keys($app->getDrivers()));

        $app->driver('aliyun');
        $this->assertSame(['aliyun'], array_keys($app->getDrivers()));

        $app->driver('aliyun');
        $this->assertSame(['aliyun'], array_keys($app->getDrivers()));

        $app->driver('baidu');
        $this->assertSame(['aliyun', 'baidu'], array_keys($app->getDrivers()));
    }

    public function test_extends()
    {
        $app = new Application([
            'default' => 'foo',
        ]);

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Driver [foo] not supported.');
        $app->driver(null);

        $app->extend('foo', function () {
            return 'foo';
        });

        $this->assertSame('foo', $app->driver('foo'));
    }

    public function test_call_method()
    {
        $app = $this->application->getContainer();

        $http = $this->mockHttpWithResponse(new Response(200, [], 'OK1'), $app['http']);
        $app['http'] = $http;

        $this->assertSame('OK1', $this->application->aliyun->idcard(__DIR__.'/stubs/common.png')->getBody()->getContents());
    }

    public function test_call_not_exists_driver()
    {
        $this->expectException(InvalidArgumentException::class);

        $this->application->tencentAi->idcard();
    }

    public function test_call_method_with_default()
    {
        $app = $this->application->getContainer();

        $http = $this->mockHttpWithResponse(new Response(200, [], 'OK1'), $app['http']);
        $app['http'] = $http;

        $this->assertSame('OK1', $this->application->idcard(__DIR__.'/stubs/common.png')->getBody()->getContents());
    }

    public function test_rebind_cache()
    {
        $this->application->rebindCache(new Psr16Cache(new NullAdapter));

        $this->assertInstanceOf(CacheInterface::class, $this->application->getContainer()['cache']);
    }
}
