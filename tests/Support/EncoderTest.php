<?php

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

        $this->assertEquals(
            "a:a\nc:c\nd:\ne:e",
            EncoderAlias::getCanonicalHeaders($headers)
        );

        $this->assertEquals(
            '',
            EncoderAlias::getCanonicalHeaders([])
        );
    }
}
