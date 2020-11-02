<?php

namespace Test;

use Godruoyi\OCR\Application;
use PHPUnit\Framework\TestCase as BaseTestCase;

class TestCase extends BaseTestCase
{
    protected $config;

    protected $application;

    /**
     * Sets up the fixture, for example, open a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->config = require 'tests/stubs/config.php';
        $this->application = new Application($this->config);
    }
}
