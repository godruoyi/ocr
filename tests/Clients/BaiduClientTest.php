<?php

/*
 * This file is part of the godruoyi/ocr.
 *
 * (c) Godruoyi <gmail@godruoyi.com>
 *
 * This source file is subject to the MIT license that is bundled.
 */

namespace Test\Clients;

use Godruoyi\OCR\Clients\BaiduClient;
use Godruoyi\OCR\Requests\BaiduRequest;
use Godruoyi\OCR\Support\Response;
use Psr\Http\Message\ResponseInterface;
use Test\TestCase;

class BaiduClientTest extends TestCase
{
    public function testBasic()
    {
        $baidu = $this->application->baidu;

        $this->assertInstanceOf(BaiduClient::class, $baidu);
    }

    public function testAllMethods()
    {
        $methods = [
            'generalBasic',
            'accurateBasic',
            'general',
            'accurate',
            'docAnalysisOffice',
            'handwriting',
            'idcard',
            'bankcard',
            'businessLicense',
            'passport',
            'businessCard',
            'householdRegister',
            'birthCertificate',
            'multiCardClassify',
            'hkMacauExitentrypermit',
            'taiwanExitentrypermit',
            'asynTable',
            'asynTableInfo',
            'syncTable',
            'receipt',
            'medicalInvoice',
            'medicalStatement',
            'medicalRecord',
            'insuranceDocuments',
            'vatInvoice',
            'trainTicket',
            'taxiReceipt',
            'quotaInvoice',
            'drivingLicense',
            'vehicleLicense',
            'licensePlate',
            'vehicleInvoice',
            'vehicleCertificate',
            'docAnalysis',
            'formula',
            'vin',
            'qrcode',
            'numbers',
            'webimage',
            'webimageLoc',
            'lottery',
            'meter',
            'seal',
            'facade',
            'invoice',
            'airTicket',
            'tollInvoice',
            'busTicket',
        ];

        $this->application->getContainer()->bind(BaiduRequest::class, function () use ($methods) {
            $request = \Mockery::mock('Request, ' . BaiduRequest::class);
            $request->shouldReceive('send')
                ->times(count($methods))
                ->andReturn(new Response(200, [], 'OK'));

            return $request;
        });
        foreach ($methods as $method) {
            if ($method == 'asynTableInfo') {
                $response = $this->application->baidu->$method('a', 'b');
            } else {
                $response = $this->application->baidu->$method(__DIR__ . '/../stubs/common.png', [
                    'language_type' => 'ENG',
                ]);
            }

            $this->assertInstanceOf(ResponseInterface::class, $response);
            $response->getBody()->rewind();
            $this->assertSame("OK", $response->getBody()->getContents());
        }
    }

    public function testGeneralBasic()
    {
        $http = $this->application->getContainer()['http'];
        $http = $this->mockHttpWithResponse([
            new Response(200, [], '{"access_token":"123456","expires_in":7200}'),
            new Response(200, [], 'OK'),
        ], $http);
        $this->application->getContainer()['http'] = $http;

        $response = $this->application->baidu->generalBasic(__DIR__ . '/../stubs/common.png', [
            'language_type' => 'ENG',
        ]);

        $this->assertInstanceOf(ResponseInterface::class, $response);
    }
}
