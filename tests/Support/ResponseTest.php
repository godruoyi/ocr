<?php

/*
 * This file is part of the godruoyi/ocr.
 *
 * (c) Godruoyi <gmail@godruoyi.com>
 *
 * This source file is subject to the MIT license that is bundled.
 */

namespace Test\Support;

use Godruoyi\OCR\Support\Response;
use Test\TestCase;

class ResponseTest extends TestCase
{
    public function testToArray()
    {
        $response = new Response(200, [], '');
        $this->assertSame([], $response->toArray());

        $response = new Response(200, [], '{');
        $this->assertSame([], $response->toArray());

        $response = new Response(200, [], '{"a":1}');
        $this->assertSame(['a' => 1], $response->toArray());
    }

    public function testOfsetGet()
    {
        $response = new Response(200, [], '{"a":1}');
        $this->assertSame(1, $response['a']);
        $this->assertSame(1, $response->offsetGet('a'));
        $this->assertNull($response->offsetGet('b'));
    }

    public function testOffsetExists()
    {
        $response = new Response(200, [], '{"a":1}');

        $this->assertTrue(isset($response['a']));
        $this->assertFalse(isset($response['b']));
    }

    public function testToJson()
    {
        $response = new Response(200, [], '{"a":1}');
        $this->assertSame('{"a":1}', $response->toJson());
    }
}
