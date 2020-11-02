<?php

namespace Test;

use Godruoyi\OCR\Application;
use Godruoyi\OCR\Config;

class ApplicationTest extends TestCase
{
    public function testBasic()
    {
        $application = new Application($this->config);

        $this->assertInstanceOf(Application::class, $application);

        $app = $application->getContainer();

        $this->assertInstanceOf(Config::class, $app['config']);
    }
}
