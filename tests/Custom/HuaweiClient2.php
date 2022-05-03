<?php

/*
 * This file is part of the godruoyi/ocr.
 *
 * (c) Godruoyi <gmail@godruoyi.com>
 *
 * This source file is subject to the MIT license that is bundled.
 */

namespace Test\Custom;

use Godruoyi\OCR\Clients\Client;

class HuaweiClient2 extends Client
{
    public function idcard($url, $images, array $options = [])
    {
        // 做你自己的业务逻辑

        return $this->request($url, $images, $options);
    }
}
