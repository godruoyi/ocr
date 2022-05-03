<?php

/*
 * This file is part of the godruoyi/ocr.
 *
 * (c) Godruoyi <gmail@godruoyi.com>
 *
 * This source file is subject to the MIT license that is bundled.
 */

namespace Test;

use Godruoyi\OCR\Config;

class ConfigTest extends TestCase
{
    public function testBasic()
    {
        $c = new Config();

        $this->assertInstanceOf(Config::class, $c);
    }

    public function testGetArray()
    {
        $c = new Config([
            'a' => [
                'b' => [
                    'c' => 1,
                ],
            ],
        ]);

        $this->assertSame(1, $c->get('a.b.c'));
    }
}
