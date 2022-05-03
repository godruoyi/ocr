<?php

namespace Test\Support;

use Godruoyi\OCR\Support\Response;
use Test\TestCase;

class ResponseTest extends TestCase
{
    public function testToArray()
    {
        $response = new Response(200, [], '');
        $this->assertEquals([], $response->toArray());

        $response = new Response(200, [], '{');
        $this->assertEquals([], $response->toArray());

        $response = new Response(200, [], '{"a":1}');
        $this->assertEquals(['a' => 1], $response->toArray());
    }

    public function testOfsetGet()
    {
        $response = new Response(200, [], '{"a":1}');
        $this->assertEquals(1, $response['a']);
        $this->assertEquals(1, $response->offsetGet('a'));
        $this->assertEquals(null, $response->offsetGet('b'));
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
        $this->assertEquals('{"a":1}', $response->toJson());
    }
}
