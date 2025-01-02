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
    public function test_sort_recursive()
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

    public function test_except()
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

    public function test_first()
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

    public function test_dot()
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

    public function test_random()
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

    public function test_divide()
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

    public function test_only()
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

    public function test_set()
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

    public function test_vvalue()
    {
        $this->assertSame('foo', Arr::vvalue('foo'));
        $this->assertSame('foo', Arr::vvalue(function () {
            return 'foo';
        })());
    }

    public function test_has_any()
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

    public function test_where()
    {
        $array = [
            'foo' => 'foo',
            'bar' => 'bar',
        ];

        $this->assertSame(['foo' => 'foo'], Arr::where($array, function ($v, $k) {
            return $k === 'foo';
        }));
    }

    public function test_exists()
    {
        $array = [
            'foo' => 'foo',
            'bar' => 'bar',
        ];

        $this->assertTrue(Arr::exists($array, 'foo'));
        $this->assertTrue(Arr::exists($array, 'bar'));
        $this->assertFalse(Arr::exists($array, 'baz'));
    }

    public function test_shuffle()
    {
        $array = [
            'foo', 'bar', 'baz',
        ];

        $this->assertSame(count($array), count(Arr::shuffle($array)));
        $this->assertSame(count($array), count(Arr::shuffle($array, time())));
    }

    public function test_prepend()
    {
        $array = [
            'foo', 'bar', 'baz',
        ];

        $this->assertSame(['bar', 'foo', 'bar', 'baz'], Arr::prepend($array, 'bar'));
        $this->assertSame(['zoo', 'foo', 'bar', 'baz'], Arr::prepend($array, 'zoo'));
        $this->assertEquals(['foo', 'bar', 'zoo'], Arr::prepend($array, 'zoo', 2));
    }

    public function test_query()
    {
        $array = [
            'foo' => 'foo',
            'bar' => 'bar',
        ];

        $this->assertSame('foo=foo&bar=bar', Arr::query($array));
    }

    public function test_get()
    {
        $array = [
            'foo' => 'foo',
            'bar' => 'bar',
        ];

        $this->assertSame('foo', Arr::get($array, 'foo'));
        $this->assertSame('bar', Arr::get($array, 'bar'));
        $this->assertSame('default', Arr::get($array, 'baz', 'default'));
    }

    public function test_pull()
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

    public function test_accessible()
    {
        $array = [
            'foo' => 'foo',
            'bar' => 'bar',
        ];

        $this->assertTrue(Arr::accessible($array));
    }

    public function test_is_assoc()
    {
        $array = [
            'foo' => 'foo',
            'bar' => 'bar',
        ];

        $this->assertTrue(Arr::isAssoc($array));
    }

    public function test_add()
    {
        $array = [
            'foo' => 'foo',
            'bar' => 'bar',
        ];

        $this->assertSame(['foo' => 'foo', 'bar' => 'bar', 'baz' => 'baz'], Arr::add($array, 'baz', 'baz'));
    }

    public function test_last()
    {
        $array = [
            'foo' => 'foo',
            'bar' => 'bar',
        ];

        $this->assertSame('bar', Arr::last($array));
    }

    public function test_has()
    {
        $array = [
            'foo' => 'foo',
            'bar' => 'bar',
        ];

        $this->assertTrue(Arr::has($array, 'foo'));
        $this->assertTrue(Arr::has($array, 'bar'));
        $this->assertFalse(Arr::has($array, 'baz'));
    }

    public function test_wrap()
    {
        $this->assertSame([1], Arr::wrap(1));
        $this->assertSame([1, 2], Arr::wrap([1, 2]));
        $this->assertSame([], Arr::wrap(null));
    }

    public function test_forget()
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

    public function test_macro()
    {
        Arr::macro('test', function () {
            return 'test';
        });

        $arr = new Arr;

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
