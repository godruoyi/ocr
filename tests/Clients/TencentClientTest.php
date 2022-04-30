<?php

/*
 * This file is part of the godruoyi/ocr.
 *
 * (c) Godruoyi <gmail@godruoyi.com>
 *
 * This source file is subject to the MIT license that is bundled.
 */

namespace Test\Clients;

use Godruoyi\OCR\Clients\TencentClient;
use Godruoyi\OCR\Requests\TencentRequest;
use Godruoyi\OCR\Support\Response;
use Psr\Http\Message\ResponseInterface;
use Test\TestCase;

class TencentClientTest extends TestCase
{
    public function testBasic()
    {
        $tencent = $this->application->tencent;

        $this->assertInstanceOf(TencentClient::class, $tencent);
    }

    public function testAllMethod()
    {
        $methods = [
            'generalBasic',
            'advertise',
            'generalAccurate',
            'generalEfficient',
            'generalFast',
            'english',
            'generalHandwriting',
            'textDetect',
            'idCard',
            'businessCard',
            'bizLicense',
            'bankCard',
            'permit',
            'mlidCard',
            'mlidPassport',
            'passport',
            'orgCodeCert',
            'institution',
            'estateCert',
            'enterpriseLicense',
            'residenceBooklet',
            'propOwnerCert',
            'mainlandPermit',
            'hmtResidentPermitOCR',
            'classifyDetect',
            'hkIDCard',
            'recognizeThaiIDCard',
            'waybill',
            'vatInvoice',
            'trainTicket',
            'taxiInvoice',
            'quotaInvoice',
            'flightInvoice',
            'carInvoice',
            'vatRollInvoice',
            'tollInvoice',
            'shipInvoice',
            'mixedInvoice',
            'mixedInvoiceDetect',
            'invoiceGeneral',
            'busInvoice',
            'dutyPaidProof',
            'finanBillSlice',
            'finanBill',
            'vin',
            'vehicleLicense',
            'licensePlate',
            'driverLicense',
            'vehicleRegCert',
            'rideHailingDriverLicense',
            'rideHailingTransportLicense',
            'recognizeTable',
            'table',
            'arithmetic',
            'formula',
            'eduPaper',
            'insuranceBill',
            'seal',
            'queryBarCode',
            'qrcode',
            'verifyBizLicense',
            'verifyBasicBizLicense',
            'vatInvoiceVerify',
        ];

        $this->application->getContainer()->bind(TencentRequest::class, function () use ($methods) {
            $request = \Mockery::mock('Request, ' . TencentRequest::class);
            $request->shouldReceive('send')
                ->times(count($methods))
                ->andReturn(new Response(200, [], 'OK'));
            return $request;
        });

        foreach ($methods as $method) {
            $response = $this->application->tencent->$method(__DIR__ . '/../stubs/common.png', [
                'Region' => 'ap-shanghai'
            ]);

            $this->assertInstanceOf(ResponseInterface::class, $response);
            $response->getBody()->rewind();
            $this->assertEquals("OK", $response->getBody()->getContents());
        }
    }
}
