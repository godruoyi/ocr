<?php

/*
 * This file is part of the godruoyi/ocr.
 *
 * (c) Godruoyi <gmail@godruoyi.com>
 *
 * This source file is subject to the MIT license that is bundled.
 */

namespace Test\Support;

use BadMethodCallException;
use Godruoyi\OCR\Support\Arr;
use Test\TestCase;

class ArrTest extends TestCase
{
    public function testSortRecursive()
    {
        $array = [
            'foo' => 'bar',
            'bar' => [
                'baz' => 'foo',
                'foo' => 'bar',
            ],
        ];

        $expected = [
            'bar' => [
                'foo' => 'bar',
                'baz' => 'foo',
            ],
            'foo' => 'bar',
        ];

        $this->assertEquals($expected, Arr::sortRecursive($array));
    }

    public function testExcept()
    {
        $array = [
            'foo' => 'bar',
            'bar' => [
                'baz' => 'foo',
                'foo' => 'bar',
            ],
        ];

        $expected = [
            'foo' => 'bar',
            'bar' => [
                'baz' => 'foo',
            ],
        ];

        $this->assertSame($expected, Arr::except($array, ['bar.foo']));
    }

    public function testFirst()
    {
        $array = [
            'foo' => 'bar',
            'bar' => [
                'baz' => 'foo',
                'foo' => 'bar',
            ],
        ];

        $this->assertSame('default', Arr::first([], null, 'default'));
        $this->assertSame('bar', Arr::first($array, null, 'default'));

        $this->assertSame('bar', Arr::first($array, function ($v, $k) {
            return $k === 'foo';
        }));

        $this->assertSame('default', Arr::first($array, function ($v, $k) {
            return $k === 'baz';
        }, 'default'));
    }

    public function testDot()
    {
        $array = [
            'foo' => 'bar',
            'bar' => [
                'baz' => 'foo',
                'foo' => 'bar',
            ],
        ];

        $this->assertSame('bar', Arr::get($array, 'foo'));
        $this->assertSame('foo', Arr::get($array, 'bar.baz'));
        $this->assertSame('default', Arr::get($array, 'bar.baz.foo', 'default'));
    }

    public function testRandom()
    {
        $array = [
            'foo', 'bar', 'baz',
        ];

        $this->assertSame([], Arr::random($array, 0));
        $this->assertTrue(is_string(Arr::random($array)));
        $this->assertTrue(count(Arr::random($array, 2)) === 2);

        $this->expectException(\InvalidArgumentException::class);
        Arr::random($array, 4);
    }

    public function testDivide()
    {
        $array = [
            'k1' => 'v1',
            'k2' => 'v2',
        ];

        $expected = [
            ['k1', 'k2'],
            ['v1', 'v2'],
        ];

        $this->assertSame($expected, Arr::divide($array));
    }

    public function testOnly()
    {
        $array = [
            'foo' => 'bar',
            'bar' => [
                'baz' => 'foo',
                'foo' => 'bar',
            ],
        ];

        $expected = [
            'foo' => 'bar',
        ];

        $this->assertSame($expected, Arr::only($array, 'foo'));
    }

    public function testSet()
    {
        $array = [
            'foo' => 'bar',
            'bar' => [
                'baz' => 'foo',
                'foo' => 'bar',
            ],
        ];

        Arr::set($array, 'bar.foo', 'barbar');

        $this->assertSame('barbar', Arr::get($array, 'bar.foo'));
    }

    public function testVvalue()
    {
        $this->assertSame('foo', Arr::vvalue('foo'));
        $this->assertSame('foo', Arr::vvalue(function () {
            return 'foo';
        })());
    }

    public function testHasAny()
    {
        $array = [
            'foo' => 'bar',
            'bar' => [
                'baz' => 'foo',
                'foo' => 'bar',
            ],
        ];

        $this->assertFalse(Arr::hasAny($array, null));
        $this->assertFalse(Arr::hasAny('', null));
        $this->assertFalse(Arr::hasAny($array, []));
        $this->assertTrue(Arr::hasAny($array, 'foo'));
        $this->assertTrue(Arr::hasAny($array, 'bar.foo'));
        $this->assertFalse(Arr::hasAny($array, 'bar.bax'));
    }

    public function testWhere()
    {
        $array = [
            'foo' => 'foo',
            'bar' => 'bar',
        ];

        $this->assertSame(['foo' => 'foo'], Arr::where($array, function ($v, $k) {
            return $k === 'foo';
        }));
    }

    public function testExists()
    {
        $array = [
            'foo' => 'foo',
            'bar' => 'bar',
        ];

        $this->assertTrue(Arr::exists($array, 'foo'));
        $this->assertTrue(Arr::exists($array, 'bar'));
        $this->assertFalse(Arr::exists($array, 'baz'));
    }

    public function testShuffle()
    {
        $array = [
            'foo', 'bar', 'baz',
        ];

        $this->assertSame(count($array), count(Arr::shuffle($array)));
        $this->assertSame(count($array), count(Arr::shuffle($array, time())));
    }

    public function testPrepend()
    {
        $array = [
            'foo', 'bar', 'baz',
        ];

        $this->assertSame(['bar', 'foo', 'bar', 'baz'], Arr::prepend($array, 'bar'));
        $this->assertSame(['zoo', 'foo', 'bar', 'baz'], Arr::prepend($array, 'zoo'));
        $this->assertEquals(['foo', 'bar', 'zoo'], Arr::prepend($array, 'zoo', 2));
    }

    public function testQuery()
    {
        $array = [
            'foo' => 'foo',
            'bar' => 'bar',
        ];

        $this->assertSame('foo=foo&bar=bar', Arr::query($array));
    }

    public function testGet()
    {
        $array = [
            'foo' => 'foo',
            'bar' => 'bar',
        ];

        $this->assertSame('foo', Arr::get($array, 'foo'));
        $this->assertSame('bar', Arr::get($array, 'bar'));
        $this->assertSame('default', Arr::get($array, 'baz', 'default'));
    }

    public function testPull()
    {
        $array = [
            'foo' => 'foo',
            'bar' => 'bar',
        ];

        $this->assertSame('foo', Arr::pull($array, 'foo'));
        $this->assertNull(Arr::pull($array, 'foo'));
        $this->assertSame('bar', Arr::pull($array, 'bar'));
        $this->assertSame('default', Arr::pull($array, 'baz', 'default'));
    }

    public function testAccessible()
    {
        $array = [
            'foo' => 'foo',
            'bar' => 'bar',
        ];

        $this->assertTrue(Arr::accessible($array));
    }

    public function testIsAssoc()
    {
        $array = [
            'foo' => 'foo',
            'bar' => 'bar',
        ];

        $this->assertTrue(Arr::isAssoc($array));
    }

    public function testAdd()
    {
        $array = [
            'foo' => 'foo',
            'bar' => 'bar',
        ];

        $this->assertSame(['foo' => 'foo', 'bar' => 'bar', 'baz' => 'baz'], Arr::add($array, 'baz', 'baz'));
    }

    public function testLast()
    {
        $array = [
            'foo' => 'foo',
            'bar' => 'bar',
        ];

        $this->assertSame('bar', Arr::last($array));
    }

    public function testHas()
    {
        $array = [
            'foo' => 'foo',
            'bar' => 'bar',
        ];

        $this->assertTrue(Arr::has($array, 'foo'));
        $this->assertTrue(Arr::has($array, 'bar'));
        $this->assertFalse(Arr::has($array, 'baz'));
    }

    public function testWrap()
    {
        $this->assertSame([1], Arr::wrap(1));
        $this->assertSame([1, 2], Arr::wrap([1, 2]));
        $this->assertSame([], Arr::wrap(null));
    }

    public function testForget()
    {
        $array = [
            'foo' => 'foo',
            'bar' => 'bar',
            'zoo' => [
                'foo' => 'foo',
            ],
        ];

        Arr::forget($array, 'bar');
        $this->assertArrayNotHasKey('bar', $array);

        Arr::forget($array, null);
        $this->assertArrayHasKey('foo', $array);
        $this->assertArrayHasKey('zoo', $array);

        Arr::forget($array, 'zoo.foo');
        $this->assertArrayHasKey('foo', $array);
    }

    public function testMacro()
    {
        Arr::macro('test', function () {
            return 'test';
        });

        $arr = new Arr();

        $this->assertTrue(Arr::hasMacro('test'));
        $this->assertSame('test', Arr::test());
        $this->assertSame('test', $arr->test());

        $this->expectException(BadMethodCallException::class);
        Arr::test1();

        $this->expectException(BadMethodCallException::class);
        $arr->test1();

        Arr::mixin($arr);

        $this->assertTrue(Arr::hasMacro('accessible'));
    }
}
