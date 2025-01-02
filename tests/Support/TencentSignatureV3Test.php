<?php

/*
 * This file is part of the godruoyi/ocr.
 *
 * (c) Godruoyi <gmail@godruoyi.com>
 *
 * This source file is subject to the MIT license that is bundled.
 */

namespace Test\Support;

use Godruoyi\OCR\Support\TencentSignatureV3;
use GuzzleHttp\Psr7\Request;
use InvalidArgumentException;
use Test\TestCase;

class TencentSignatureV3Test extends TestCase
{
    // from https://cloud.tencent.com/document/product/866/33519
    protected $secretId = 'AKIDz8krbsJ5yKBZQpn74WFkmLPx3*******';

    protected $secretKey = 'Gu5t9xGARNpq86cd98joQYCN3*******';

    public function test_hashed_request_payload()
    {
        $signer = new TencentSignatureV3($this->secretId, $this->secretKey);
        $body = '{"Limit": 1, "Filters": [{"Values": ["\u672a\u547d\u540d"], "Name": "instance-name"}]}';

        $this->assertSame($signer->hashedRequestPayload($body), '35e9c5b0e3ae67532d3c9f17ead6c90222632e5b1ff7f6e89887f1398934f064');
    }

    public function test_canonical_request()
    {
        $signer = new TencentSignatureV3($this->secretId, $this->secretKey);

        $request = new Request(
            'post',
            'https://cvm.tencentcloudapi.com',
            [
                'content-type' => ['application/json; charset=utf-8'],
                'headers' => [
                    'X-TC-Timestamp' => '1551113065',
                ],
            ],
            '{"Limit": 1, "Filters": [{"Values": ["\u672a\u547d\u540d"], "Name": "instance-name"}]}'
        );

        $this->assertSame($signer->hashedRequestPayload($request->getBody()), '35e9c5b0e3ae67532d3c9f17ead6c90222632e5b1ff7f6e89887f1398934f064');
        $request->getBody()->rewind();
        $this->assertSame($signer->canonicalRequest($request), "POST\n/\n\ncontent-type:application/json; charset=utf-8\nhost:cvm.tencentcloudapi.com\n\ncontent-type;host\n35e9c5b0e3ae67532d3c9f17ead6c90222632e5b1ff7f6e89887f1398934f064");
    }

    public function test_authorization_invalid_argument_exception()
    {
        $this->expectException(InvalidArgumentException::class);

        $signer = new TencentSignatureV3($this->secretId, $this->secretKey);
        $signer->authorization(new Request('get', 'https://cvm.tencentcloudapi.com'));
    }
}
