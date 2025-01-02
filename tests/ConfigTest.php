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
    public function test_basic()
    {
        $c = new Config;

        $this->assertInstanceOf(Config::class, $c);
    }

    public function test_get_array()
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
