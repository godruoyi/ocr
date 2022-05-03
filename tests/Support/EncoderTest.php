<?php

/*
 * This file is part of the godruoyi/ocr.
 *
 * (c) Godruoyi <gmail@godruoyi.com>
 *
 * This source file is subject to the MIT license that is bundled.
 */

namespace Test\Support;

use Godruoyi\OCR\Support\Encoder as EncoderAlias;
use Test\TestCase;

class EncoderTest extends TestCase
{
    public function testGetCanonicalHeaders()
    {
        $headers = [
            'A' => ' a ',
            'c' => 'c',
            'e' => ' E',
            ' D' => null,
            '' => 'x',
        ];

        $this->assertSame(
            "a:a\nc:c\nd:\ne:e",
            EncoderAlias::getCanonicalHeaders($headers)
        );

        $this->assertSame(
            '',
            EncoderAlias::getCanonicalHeaders([])
        );
    }
}
