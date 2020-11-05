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
