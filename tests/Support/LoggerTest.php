<?php

namespace Test\Support;

use Godruoyi\OCR\Support\Logger;
use InvalidArgumentException as InvalidArgumentExceptionAlias;
use Monolog\Handler\ErrorLogHandler;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Handler\SlackWebhookHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\SyslogHandler;
use Monolog\Logger as MonoLogger;
use Test\TestCase;

class LoggerTest extends TestCase
{
    protected function getLogger(): Logger
    {
        return $this->application->getContainer()['logger'];
    }

    public function testDebug()
    {
        $log = $this->getLogger();

        $log->debug('debug message');

        $this->assertTrue(true);
    }

    public function testSetDefaultDriver()
    {
        $log = new Logger([
            'default' => 'daily',
            'channels' => [
                'error_log' => [
                    'name' => 'OCR',
                    'level' => 'debug',
                    'driver' => 'errorlog',
                ],
            ],
        ]);
        $log->setDefaultDriver('error_log');

        $this->assertEquals('error_log', $log->getDefaultDriver());

        $this->assertInstanceOf(MonoLogger::class, $log->driver());
        $this->assertInstanceOf(ErrorLogHandler::class, $log->driver()->getHandlers()[0]);
    }

    public function testAlert()
    {
        $this->getLogger()->alert('alert message');

        $this->assertTrue(true);
    }

    public function testDriver()
    {
        $log = $this->getLogger();

        $this->assertInstanceOf(RotatingFileHandler::class, $log->driver()->getHandlers()[0]);

        $this->expectException(InvalidArgumentExceptionAlias::class);
        $log->driver('');

        $this->assertInstanceOf(StreamHandler::class, $log->driver('invalid')->getHandlers()[0]);

        $log = new Logger([
            'default' => 'daily',
            'channels' => [
                'error_log' => [
                    'name' => 'OCR',
                    'level' => 'debug',
                    'driver' => 'invalid',
                ],
            ],
        ]);
        $log->setDefaultDriver('error_log');

        $this->expectException(InvalidArgumentExceptionAlias::class);
        $this->expectExceptionMessage('Driver invalid is not supported.');
        $log->driver()->log('debug', 'debug message');
    }

    public function testEmergency()
    {
        $this->getLogger()->emergency('emergency message');

        $this->assertTrue(true);
    }

    public function testLog()
    {
        $this->getLogger()->log('info', 'info message');

        $this->assertTrue(true);
    }

    public function testError()
    {
        $this->getLogger()->error('error message');

        $this->assertTrue(true);
    }

    public function testWarning()
    {
        $this->getLogger()->warning('warning message');

        $this->assertTrue(true);
    }

    public function testExtend()
    {
        $log = new Logger([
            'default' => 'daily',
            'channels' => [
                'custom' => [
                    'name' => 'OCR',
                    'level' => 'debug',
                    'driver' => 'custom',
                ],
            ],
        ]);
        $log->setDefaultDriver('custom');
        $log->extend('custom', function ($config) {
            return 1;
        });

        $this->assertEquals(1, $log->driver());
    }

    public function test__call()
    {
        $this->getLogger()->info('info message');

        $this->assertTrue(true);
    }

    public function testChannel()
    {
        $log = $this->getLogger();

        $this->assertInstanceOf(StreamHandler::class, $log->channel('daily')->getHandlers()[0]);
    }

    public function testInfo()
    {
        $this->getLogger()->info('info message');

        $this->assertTrue(true);
    }

    public function testNotice()
    {
        $this->getLogger()->notice('notice message');

        $this->assertTrue(true);
    }

    public function testStack()
    {
        $this->getLogger()->stack(['daily', 'errorlog']);

        $this->assertTrue(true);
    }

    public function testCritical()
    {
        $this->getLogger()->critical('critical message');

        $this->assertTrue(true);
    }

    public function testCall()
    {
        $this->getLogger()->info('info message');

        $this->assertTrue(true);
    }

    public function testCreateSyslogDriver()
    {
        $log = new Logger([
            'default' => 'daily',
            'channels' => [
                'syslog' => [
                    'name' => 'OCR',
                    'level' => 'debug',
                    'driver' => 'syslog',
                ],
            ],
        ]);
        $log->setDefaultDriver('syslog');

        $this->assertInstanceOf(MonoLogger::class, $log->driver());
        $this->assertInstanceOf(SyslogHandler::class, $log->driver()->getHandlers()[0]);
    }

    public function testCreateSlackDriver()
    {
        $log = new Logger([
            'default' => 'slack',
            'channels' => [
                'slack' => [
                    'name' => 'OCR',
                    'level' => 'debug',
                    'driver' => 'slack',
                    'url' => 'https://hooks.slack.com',
                ],
            ],
        ]);
        $this->assertInstanceOf(MonoLogger::class, $log->driver());
        $this->assertInstanceOf(SlackWebhookHandler::class, $log->driver()->getHandlers()[0]);
    }

    public function testCreateSingleDriver()
    {
        $log = new Logger([
            'default' => 'single',
            'channels' => [
                'single' => [
                    'name' => 'OCR',
                    'level' => 'debug',
                    'driver' => 'single',
                    'path' => '/tmp/log.log',
                ],
            ],
        ]);
        $this->assertInstanceOf(MonoLogger::class, $log->driver());
        $this->assertInstanceOf(StreamHandler::class, $log->driver()->getHandlers()[0]);
    }

    public function testInvalideType()
    {
        $log = new Logger([
            'default' => 'daily',
            'channels' => [
                'error_log' => [
                    'name' => 'OCR',
                    'level' => 'debug',
                    'driver' => 'invalid',
                ],
            ],
        ]);
        $log->setDefaultDriver('error_log');
        $log->driver();

        $this->assertInstanceOf(StreamHandler::class, $log->driver()->getHandlers()[0]);
    }

    public function testInvalidLevel()
    {
        $log = new Logger([
            'default' => 'daily',
            'channels' => [
                'daily' => [
                    'name' => 'OCR',
                    'level' => 'invalid',
                    'driver' => 'daily',
                    'path' => '/tmp/log.log',
                ],
            ],
        ]);
        $log->driver('daily');
        $this->assertInstanceOf(StreamHandler::class, $log->driver()->getHandlers()[0]);
    }
}
