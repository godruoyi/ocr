<?php

namespace Test\Support;

use Test\TestCase;
use Godruoyi\OCR\Support\TencentSignatureV3;

class TencentSignatureV3Test extends TestCase
{
    // from https://cloud.tencent.com/document/product/866/33519
    protected $secretId = 'AKIDz8krbsJ5yKBZQpn74WFkmLPx3*******';

    protected $secretKey = 'Gu5t9xGARNpq86cd98joQYCN3*******';

    public function testHashedRequestPayload()
    {
        $signer = new TencentSignatureV3($this->secretId, $this->secretKey);
        $body = '{"Limit": 1, "Filters": [{"Values": ["\u672a\u547d\u540d"], "Name": "instance-name"}]}';

        $this->assertEquals($signer->hashedRequestPayload($body), '35e9c5b0e3ae67532d3c9f17ead6c90222632e5b1ff7f6e89887f1398934f064');
    }

    public function testHashedRequestPayload2()
    {
        $signer = new TencentSignatureV3($this->secretId, $this->secretKey);
        $body = '{"ImageUrl":"https://cloud.tencent.com/1"}';

        $this->assertEquals($signer->hashedRequestPayload($body), 'e3d1d29a998fcb1d83bb752972a15a6660041c7b112a481de8aedaa9e2e2729b');
    }

    public function testCanonicalRequest()
    {
        $signer = new TencentSignatureV3($this->secretId, $this->secretKey);

        $request = new \GuzzleHttp\Psr7\Request(
            'post',
            'https://cvm.tencentcloudapi.com',
            [
                'content-type' => ['application/json; charset=utf-8']
            ],
            '{"Limit": 1, "Filters": [{"Values": ["\u672a\u547d\u540d"], "Name": "instance-name"}]}',
        );

        $this->assertEquals($signer->hashedRequestPayload($request->getBody()), '35e9c5b0e3ae67532d3c9f17ead6c90222632e5b1ff7f6e89887f1398934f064');
        $this->assertEquals($signer->canonicalRequest($request), "POST\n/\n\ncontent-type:application/json; charset=utf-8\nhost:cvm.tencentcloudapi.com\n\ncontent-type;host\n35e9c5b0e3ae67532d3c9f17ead6c90222632e5b1ff7f6e89887f1398934f064");
    }

    // public function testAuthorization()
    // {
    //     $signer = new TencentSignatureV3($this->secretId, $this->secretKey);
    //     $request = new \GuzzleHttp\Psr7\Request(
    //         'post',
    //         'https://cvm.tencentcloudapi.com',
    //         [
    //             'content-type' => ['application/json; charset=utf-8'],
    //             'X-TC-Timestamp' => 1551113065,
    //         ],
    //         '{"Limit": 1, "Filters": [{"Values": ["\u672a\u547d\u540d"], "Name": "instance-name"}]}',
    //     );

    //     $a = $signer->authorization($request);

    //     var_dump($a);
    // }
}
