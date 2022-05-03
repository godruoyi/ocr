<?php

$header = <<<EOF
This file is part of the godruoyi/ocr.

(c) Godruoyi <gmail@godruoyi.com>

This source file is subject to the MIT license that is bundled.
EOF;

use PhpCsFixer\Config;
use PhpCsFixer\Finder;

return (new Config())->setRules([
    '@PSR2' => true,
    'header_comment' => ['header' => $header],
    'array_syntax' => ['syntax' => 'short'],
    'ordered_imports' => true,
    'no_useless_else' => true,
    'no_useless_return' => true,
])
    ->setFinder(
        Finder::create()
            ->in(__DIR__)
            ->exclude('vendor')
            ->name('*.php')
            ->notName('*.blade.php')
    )
    ->setUsingCache(false)
    ->setRiskyAllowed(true);
