<?php

/*
 * This file is part of the godruoyi/ocr.
 *
 * (c) Godruoyi <gmail@godruoyi.com>
 *
 * This source file is subject to the MIT license that is bundled.
 */

namespace Godruoyi\OCR\Support;

use Monolog\Formatter\LineFormatter;
use Monolog\Handler\ErrorLogHandler;
use Monolog\Handler\HandlerInterface;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Handler\SlackWebhookHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\SyslogHandler;
use Monolog\Logger as Monolog;
use Psr\Log\LoggerInterface;

class Logger implements LoggerInterface
{
    /**
     * The array of resolved channels.
     *
     * @var array
     */
    protected $channels = [];

    /**
     * The array of default config.
     *
     * @var array
     */
    protected $config = [];

    /**
     * The registered custom driver creators.
     *
     * @var array
     */
    protected $customCreators = [];

    /**
     * The Log levels.
     *
     * @var array
     */
    protected $levels = [
        'debug' => Monolog::DEBUG,
        'info' => Monolog::INFO,
        'notice' => Monolog::NOTICE,
        'warning' => Monolog::WARNING,
        'error' => Monolog::ERROR,
        'critical' => Monolog::CRITICAL,
        'alert' => Monolog::ALERT,
        'emergency' => Monolog::EMERGENCY,
    ];

    /**
     * LogManager constructor.
     */
    public function __construct(array $config = [])
    {
        $this->config = $config;
    }

    /**
     * Create a new, on-demand aggregate logger instance.
     *
     * @param string|null $channel
     *
     * @return \Psr\Log\LoggerInterface
     */
    public function stack(array $channels, $channel = null)
    {
        return $this->createStackDriver(compact('channels', 'channel'));
    }

    /**
     * Get a log channel instance.
     *
     * @param string|null $channel
     *
     * @return mixed
     */
    public function channel($channel = null)
    {
        return $this->get($channel);
    }

    /**
     * Get a log driver instance.
     *
     * @param string|null $driver
     *
     * @return mixed
     */
    public function driver($driver = null)
    {
        return $this->get($driver ?? $this->getDefaultDriver());
    }

    /**
     * Attempt to get the log from the local cache.
     *
     * @param string $name
     *
     * @return \Psr\Log\LoggerInterface
     */
    protected function get($name)
    {
        if (empty($name)) {
            throw new \InvalidArgumentException('Log driver is empty.');
        }

        try {
            return $this->channels[$name] ?? ($this->channels[$name] = $this->resolve($name));
        } catch (\Throwable $e) {
            $logger = $this->createEmergencyLogger();

            $logger->emergency('Unable to create configured logger. Using emergency logger.', [
                'exception' => $e,
            ]);

            return $logger;
        }
    }

    /**
     * Resolve the given log instance by name.
     *
     * @param string $name
     *
     * @return \Psr\Log\LoggerInterface
     *
     * @throws \InvalidArgumentException
     */
    protected function resolve($name)
    {
        $channels = Arr::get($this->config, 'channels', []);

        if (empty($channels) || !isset($channels[$name])) {
            throw new \InvalidArgumentException(\sprintf('Log [%s] is not defined.', $name));
        }

        $config = $channels[$name];

        if (isset($this->customCreators[$config['driver']])) {
            return $this->callCustomCreator($config);
        }

        $driverMethod = 'create' . ucfirst($config['driver']) . 'Driver';

        if (method_exists($this, $driverMethod)) {
            return $this->{$driverMethod}($config);
        }

        throw new \InvalidArgumentException(\sprintf('Driver [%s] is not supported.', $config['driver']));
    }

    /**
     * Create an emergency log handler to avoid white screens of death.
     *
     * @return \Monolog\Logger
     */
    protected function createEmergencyLogger()
    {
        return new Monolog('OCR', $this->prepareHandlers([new StreamHandler(
            \sys_get_temp_dir() . '/ocr.log',
            $this->level(['level' => 'debug'])
        )]));
    }

    /**
     * Call a custom driver creator.
     *
     * @return mixed
     */
    protected function callCustomCreator(array $config)
    {
        return $this->customCreators[$config['driver']]($config);
    }

    /**
     * Create an aggregate log driver instance.
     *
     * @return \Monolog\Logger
     */
    protected function createStackDriver(array $config)
    {
        $handlers = [];

        foreach ($config['channels'] ?? [] as $channel) {
            $handlers = \array_merge($handlers, $this->channel($channel)->getHandlers());
        }

        return new Monolog('OCR', $handlers);
    }

    /**
     * Create an instance of the single file log driver.
     *
     * @return \Psr\Log\LoggerInterface
     */
    protected function createSingleDriver(array $config)
    {
        return new Monolog($this->parseChannel($config), [
            $this->prepareHandler(
                new StreamHandler($config['path'], $this->level($config))
            ),
        ]);
    }

    /**
     * Create an instance of the daily file log driver.
     *
     * @return \Psr\Log\LoggerInterface
     */
    protected function createDailyDriver(array $config)
    {
        return new Monolog($this->parseChannel($config), [
            $this->prepareHandler(new RotatingFileHandler(
                $config['path'],
                $config['days'] ?? 7,
                $this->level($config)
            )),
        ]);
    }

    /**
     * Create an instance of the Slack log driver.
     *
     * @return \Psr\Log\LoggerInterface
     */
    protected function createSlackDriver(array $config)
    {
        return new Monolog($this->parseChannel($config), [
            $this->prepareHandler(new SlackWebhookHandler(
                $config['url'],
                $config['channel'] ?? null,
                $config['username'] ?? 'OCR',
                $config['attachment'] ?? true,
                $config['emoji'] ?? ':boom:',
                $config['short'] ?? false,
                $config['context'] ?? true,
                $this->level($config)
            )),
        ]);
    }

    /**
     * Create an instance of the syslog log driver.
     *
     * @return \Psr\Log\LoggerInterface
     */
    protected function createSyslogDriver(array $config)
    {
        return new Monolog($this->parseChannel($config), [
            $this->prepareHandler(new SyslogHandler(
                'OCR',
                $config['facility'] ?? LOG_USER,
                $this->level($config)
            )),
        ]);
    }

    /**
     * Create an instance of the "error log" log driver.
     *
     * @return \Psr\Log\LoggerInterface
     */
    protected function createErrorlogDriver(array $config)
    {
        return new Monolog($this->parseChannel($config), [
            $this->prepareHandler(new ErrorLogHandler(
                $config['type'] ?? ErrorLogHandler::OPERATING_SYSTEM,
                $this->level($config)
            )),
        ]);
    }

    /**
     * Prepare the handlers for usage by Monolog.
     *
     * @return array
     */
    protected function prepareHandlers(array $handlers)
    {
        foreach ($handlers as $key => $handler) {
            $handlers[$key] = $this->prepareHandler($handler);
        }

        return $handlers;
    }

    /**
     * Prepare the handler for usage by Monolog.
     *
     * @return \Monolog\Handler\HandlerInterface
     */
    protected function prepareHandler(HandlerInterface $handler)
    {
        return $handler->setFormatter($this->formatter());
    }

    /**
     * Get a Monolog formatter instance.
     *
     * @return \Monolog\Formatter\FormatterInterface
     */
    protected function formatter()
    {
        $formatter = new LineFormatter(null, null, true, true);
        $formatter->includeStacktraces();

        return $formatter;
    }

    /**
     * Extract the log channel from the given configuration.
     *
     * @return string
     */
    protected function parseChannel(array $config)
    {
        return $config['name'] ?? null;
    }

    /**
     * Parse the string level into a Monolog constant.
     *
     * @return int
     *
     * @throws \InvalidArgumentException
     */
    protected function level(array $config)
    {
        $level = $config['level'] ?? 'debug';

        if (isset($this->levels[$level])) {
            return $this->levels[$level];
        }

        throw new \InvalidArgumentException('Invalid log level.');
    }

    /**
     * Get the default log driver name.
     *
     * @return string
     */
    public function getDefaultDriver()
    {
        return $this->config['default'];
    }

    /**
     * Set the default log driver name.
     *
     * @param string $name
     */
    public function setDefaultDriver($name)
    {
        $this->config['default'] = $name;
    }

    /**
     * Register a custom driver creator Closure.
     *
     * @param string $driver
     *
     * @return $this
     */
    public function extend($driver, \Closure $callback)
    {
        $this->customCreators[$driver] = $callback->bindTo($this, $this);

        return $this;
    }

    /**
     * System is unusable.
     *
     * @param string $message
     *
     * @return mixed
     */
    public function emergency($message, array $context = [])
    {
        return $this->driver()->emergency($message, $context);
    }

    /**
     * Action must be taken immediately.
     *
     * Example: Entire website down, database unavailable, etc. This should
     * trigger the SMS alerts and wake you up.
     *
     * @param string $message
     *
     * @return mixed
     */
    public function alert($message, array $context = [])
    {
        return $this->driver()->alert($message, $context);
    }

    /**
     * Critical conditions.
     *
     * Example: Application component unavailable, unexpected exception.
     *
     * @param string $message
     *
     * @return mixed
     */
    public function critical($message, array $context = [])
    {
        return $this->driver()->critical($message, $context);
    }

    /**
     * Runtime errors that do not require immediate action but should typically
     * be logged and monitored.
     *
     * @param string $message
     *
     * @return mixed
     */
    public function error($message, array $context = [])
    {
        return $this->driver()->error($message, $context);
    }

    /**
     * Exceptional occurrences that are not errors.
     *
     * Example: Use of deprecated APIs, poor use of an API, undesirable things
     * that are not necessarily wrong.
     *
     * @param string $message
     *
     * @return mixed
     */
    public function warning($message, array $context = [])
    {
        return $this->driver()->warning($message, $context);
    }

    /**
     * Normal but significant events.
     *
     * @param string $message
     *
     * @return mixed
     */
    public function notice($message, array $context = [])
    {
        return $this->driver()->notice($message, $context);
    }

    /**
     * Interesting events.
     *
     * Example: User logs in, SQL logs.
     *
     * @param string $message
     *
     * @return mixed
     */
    public function info($message, array $context = [])
    {
        return $this->driver()->info($message, $context);
    }

    /**
     * Detailed debug information.
     *
     * @param string $message
     *
     * @return mixed
     */
    public function debug($message, array $context = [])
    {
        return $this->driver()->debug($message, $context);
    }

    /**
     * Logs with an arbitrary level.
     *
     * @param mixed $level
     * @param string $message
     *
     * @return mixed
     */
    public function log($level, $message, array $context = [])
    {
        return $this->driver()->log($level, $message, $context);
    }

    /**
     * Dynamically call the default driver instance.
     *
     * @param string $method
     * @param array $parameters
     *
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        return $this->driver()->$method(...$parameters);
    }
}
