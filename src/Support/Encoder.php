<?php

/*
 * This file is part of the godruoyi/ocr.
 *
 * (c) Godruoyi <gmail@godruoyi.com>
 *
 * This source file is subject to the MIT license that is bundled.
 */

namespace Godruoyi\OCR\Support;

class Encoder
{
    /**
     * 生成标准化 http 请求头串.
     *
     * @return mixed
     */
    public static function getCanonicalHeaders(array $headers)
    {
        // 如果没有 headers，则返回空串
        if (0 == count($headers)) {
            return '';
        }

        $headerStrings = [];

        foreach ($headers as $k => $v) {
            if (empty($k)) {
                continue;
            }

            $v = null === $v ? '' : $v;

            $headerStrings[] = strtolower(trim($k)) . ':' . strtolower(trim($v));
        }

        sort($headerStrings);

        return implode("\n", $headerStrings);
    }
}
