<?php

/*
 * This file is part of the godruoyi/ocr.
 *
 * (c) Godruoyi <gmail@godruoyi.com>
 *
 * This source file is subject to the MIT license that is bundled.
 */

namespace Godruoyi\OCR\Clients;

use Godruoyi\OCR\Requests\TencentRequest;

/**
 * @author    godruoyi godruoyi@gmail.com>
 * @copyright 2017
 *
 * @see https://cloud.tencent.com/document/product/866/33514
 * @see https://github.com/godruoyi/ocr
 *
 * @version 2.0
 */
class TencentClient extends Client
{
    /**
     * Register request.
     *
     * @param TencentClient $request
     */
    public function __construct(TencentRequest $request)
    {
        $this->request = $request;
    }

    /**
     * 通用印刷体识别.
     *
     * @see https://cloud.tencent.com/document/product/866/33526
     *
     * @param mixed $images
     *
     * @return mixed
     */
    public function generalBasic($images, array $options = [])
    {
        return $this->request('GeneralBasicOCR', $images, $options);
    }

    /**
     * 广告文字识别.
     *
     * @see https://cloud.tencent.com/document/product/866/49524
     *
     * @param mixed $images
     *
     * @return mixed
     */
    public function advertise($images, array $options = [])
    {
        return $this->request('AdvertiseOCR', $images, $options);
    }

    /**
     * 通用印刷体识别（高精度版）.
     *
     * @see https://cloud.tencent.com/document/product/866/34937
     *
     * @param mixed $images
     *
     * @return mixed
     */
    public function generalAccurate($images, array $options = [])
    {
        return $this->request('GeneralAccurateOCR', $images, $options);
    }

    /**
     * 通用印刷体识别（精简版）.
     *
     * @see https://cloud.tencent.com/document/product/866/37831
     *
     * @param mixed $images
     *
     * @return mixed
     */
    public function generalEfficient($images, array $options = [])
    {
        return $this->request('GeneralEfficientOCR', $images, $options);
    }

    /**
     * 通用印刷体识别（高速版）.
     *
     * @see https://cloud.tencent.com/document/product/866/33525
     *
     * @param mixed $images
     *
     * @return mixed
     */
    public function generalFast($images, array $options = [])
    {
        return $this->request('GeneralFastOCR', $images, $options);
    }

    /**
     * 英文识别.
     *
     * @see https://cloud.tencent.com/document/product/866/34938
     *
     * @param mixed $images
     *
     * @return mixed
     */
    public function english($images, array $options = [])
    {
        return $this->request('EnglishOCR', $images, $options);
    }

    /**
     * 通用手写体识别.
     *
     * @see https://cloud.tencent.com/document/product/866/36212
     *
     * @param mixed $images
     *
     * @return mixed
     */
    public function generalHandwriting($images, array $options = [])
    {
        return $this->request('GeneralHandwritingOCR', $images, $options);
    }

    /**
     * 快速文本检测.
     *
     * @see https://cloud.tencent.com/document/product/866/37830
     *
     * @param mixed $images
     *
     * @return mixed
     */
    public function textDetect($images, array $options = [])
    {
        return $this->request('TextDetect', $images, $options);
    }

    /**
     * 身份证识别.
     *
     * @see https://cloud.tencent.com/document/product/866/33524
     *
     * @param mixed $images
     *
     * @return mixed
     */
    public function idCard($images, array $options = [])
    {
        return $this->request('IDCardOCR', $images, $options);
    }

    /**
     * 名片识别.
     *
     * @see @see https://cloud.tencent.com/document/product/866/36214
     *
     * @param mixed $images
     *
     * @return mixed
     */
    public function businessCard($images, array $options = [])
    {
        return $this->request('BusinessCardOCR', $images, $options);
    }

    /**
     * 营业执照识别.
     *
     * @see @see https://cloud.tencent.com/document/product/866/36215
     *
     * @param mixed $images
     *
     * @return mixed
     */
    public function bizLicense($images, array $options = [])
    {
        return $this->request('BizLicenseOCR', $images, $options);
    }

    /**
     * 银行卡识别.
     *
     * @see @see https://cloud.tencent.com/document/product/866/36216
     *
     * @param mixed $images
     *
     * @return mixed
     */
    public function bankCard($images, array $options = [])
    {
        return $this->request('BankCardOCR', $images, $options);
    }

    /**
     * 港澳台通行证识别.
     *
     * @see @see https://cloud.tencent.com/document/product/866/37074
     *
     * @param mixed $images
     *
     * @return mixed
     */
    public function permit($images, array $options = [])
    {
        return $this->request('PermitOCR', $images, $options);
    }

    /**
     * 马来西亚身份证识别.
     *
     * @see @see https://cloud.tencent.com/document/product/866/37656
     *
     * @param mixed $images
     *
     * @return mixed
     */
    public function mlidCard($images, array $options = [])
    {
        return $this->request('MLIDCardOCR', $images, $options);
    }

    /**
     * 护照识别（港澳台地区及境外护照）.
     *
     * @see @see https://cloud.tencent.com/document/product/866/37657
     *
     * @param mixed $images
     *
     * @return mixed
     */
    public function mlidPassport($images, array $options = [])
    {
        return $this->request('MLIDPassportOCR', $images, $options);
    }

    /**
     * 护照识别（中国大陆地区护照）.
     *
     * @see @see https://cloud.tencent.com/document/product/866/37840
     *
     * @param mixed $images
     *
     * @return mixed
     */
    public function passport($images, array $options = [])
    {
        return $this->request('PassportOCR', $images, $options);
    }

    /**
     * 组织机构代码证识别.
     *
     * @see @see https://cloud.tencent.com/document/product/866/38298
     *
     * @param mixed $images
     *
     * @return mixed
     */
    public function orgCodeCert($images, array $options = [])
    {
        return $this->request('OrgCodeCertOCR', $images, $options);
    }

    /**
     * 事业单位法人证书识别.
     *
     * @see @see https://cloud.tencent.com/document/product/866/38299
     *
     * @param mixed $images
     *
     * @return mixed
     */
    public function institution($images, array $options = [])
    {
        return $this->request('InstitutionOCR', $images, $options);
    }

    /**
     * 不动产权证识别.
     *
     * @see @see https://cloud.tencent.com/document/product/866/38300
     *
     * @param mixed $images
     *
     * @return mixed
     */
    public function estateCert($images, array $options = [])
    {
        return $this->request('EstateCertOCR', $images, $options);
    }

    /**
     * 企业证照识别.
     *
     * @see @see https://cloud.tencent.com/document/product/866/38849
     *
     * @param mixed $images
     *
     * @return mixed
     */
    public function enterpriseLicense($images, array $options = [])
    {
        return $this->request('EnterpriseLicenseOCR', $images, $options);
    }

    /**
     * 户口本识别.
     *
     * @see @see https://cloud.tencent.com/document/product/866/40036
     *
     * @param mixed $images
     *
     * @return mixed
     */
    public function residenceBooklet($images, array $options = [])
    {
        return $this->request('ResidenceBookletOCR', $images, $options);
    }

    /**
     * 房产证识别.
     *
     * @see @see https://cloud.tencent.com/document/product/866/40037
     *
     * @param mixed $images
     *
     * @return mixed
     */
    public function propOwnerCert($images, array $options = [])
    {
        return $this->request('PropOwnerCertOCR', $images, $options);
    }

    /**
     * 港澳台来往内地通行证识别.
     *
     * @see @see https://cloud.tencent.com/document/product/866/43105
     *
     * @param mixed $images
     *
     * @return mixed
     */
    public function mainlandPermit($images, array $options = [])
    {
        return $this->request('MainlandPermitOCR', $images, $options);
    }

    /**
     * 港澳台居住证识别.
     *
     * @see @see https://cloud.tencent.com/document/product/866/43106
     *
     * @param mixed $images
     *
     * @return mixed
     */
    public function hmtResidentPermitOCR($images, array $options = [])
    {
        return $this->request('HmtResidentPermitOCR', $images, $options);
    }

    /**
     * 智能卡证分类.
     *
     * @see @see https://cloud.tencent.com/document/product/866/46770
     *
     * @param mixed $images
     *
     * @return mixed
     */
    public function classifyDetect($images, array $options = [])
    {
        return $this->request('ClassifyDetectOCR', $images, $options);
    }

    /**
     * 中国香港身份证识别.
     *
     * @see @see https://cloud.tencent.com/document/product/866/46919
     *
     * @param mixed $images
     *
     * @return mixed
     */
    public function hkIDCard($images, array $options = [])
    {
        return $this->request('HKIDCardOCR', $images, $options);
    }

    /**
     * 泰国身份证识别.
     *
     * @see @see https://cloud.tencent.com/document/product/866/48475
     *
     * @param mixed $images
     *
     * @return mixed
     */
    public function recognizeThaiIDCard($images, array $options = [])
    {
        return $this->request('RecognizeThaiIDCardOCR', $images, $options);
    }

    /**
     * 运单识别.
     *
     * @see https://cloud.tencent.com/document/product/866/34934
     *
     * @param mixed $images
     *
     * @return mixed
     */
    public function waybill($images, array $options = [])
    {
        return $this->request('WaybillOCR', $images, $options);
    }

    /**
     * 增值税发票识别.
     *
     * @see https://cloud.tencent.com/document/product/866/36210
     *
     * @param mixed $images
     *
     * @return mixed
     */
    public function vatInvoice($images, array $options = [])
    {
        return $this->request('VatInvoiceOCR', $images, $options);
    }

    /**
     * 火车票识别.
     *
     * @see https://cloud.tencent.com/document/product/866/37071
     *
     * @param mixed $images
     *
     * @return mixed
     */
    public function trainTicket($images, array $options = [])
    {
        return $this->request('TrainTicketOCR', $images, $options);
    }

    /**
     * 出租车发票识别.
     *
     * @see https://cloud.tencent.com/document/product/866/37072
     *
     * @param mixed $images
     *
     * @return mixed
     */
    public function taxiInvoice($images, array $options = [])
    {
        return $this->request('TaxiInvoiceOCR', $images, $options);
    }

    /**
     * 定额发票识别.
     *
     * @see https://cloud.tencent.com/document/product/866/37073
     *
     * @param mixed $images
     *
     * @return mixed
     */
    public function quotaInvoice($images, array $options = [])
    {
        return $this->request('QuotaInvoiceOCR', $images, $options);
    }

    /**
     * 机票行程单识别.
     *
     * @see https://cloud.tencent.com/document/product/866/37075
     *
     * @param mixed $images
     *
     * @return mixed
     */
    public function flightInvoice($images, array $options = [])
    {
        return $this->request('FlightInvoiceOCR', $images, $options);
    }

    /**
     * 购车发票识别.
     *
     * @see https://cloud.tencent.com/document/product/866/37076
     *
     * @param mixed $images
     *
     * @return mixed
     */
    public function carInvoice($images, array $options = [])
    {
        return $this->request('CarInvoiceOCR', $images, $options);
    }

    /**
     * 增值税发票（卷票）识别.
     *
     * @see https://cloud.tencent.com/document/product/866/37832
     *
     * @param mixed $images
     *
     * @return mixed
     */
    public function vatRollInvoice($images, array $options = [])
    {
        return $this->request('VatRollInvoiceOCR', $images, $options);
    }

    /**
     * 过路过桥费发票识别.
     *
     * @see https://cloud.tencent.com/document/product/866/37833
     *
     * @param mixed $images
     *
     * @return mixed
     */
    public function tollInvoice($images, array $options = [])
    {
        return $this->request('TollInvoiceOCR', $images, $options);
    }

    /**
     * 轮船票识别.
     *
     * @see https://cloud.tencent.com/document/product/866/37834
     *
     * @param mixed $images
     *
     * @return mixed
     */
    public function shipInvoice($images, array $options = [])
    {
        return $this->request('ShipInvoiceOCR', $images, $options);
    }

    /**
     * 混贴票据识别.
     *
     * @see https://cloud.tencent.com/document/product/866/37835
     *
     * @param mixed $images
     *
     * @return mixed
     */
    public function mixedInvoice($images, array $options = [])
    {
        return $this->request('MixedInvoiceOCR', $images, $options);
    }

    /**
     * 混贴票据分类.
     *
     * @see https://cloud.tencent.com/document/product/866/37836
     *
     * @param mixed $images
     *
     * @return mixed
     */
    public function mixedInvoiceDetect($images, array $options = [])
    {
        return $this->request('MixedInvoiceDetect', $images, $options);
    }

    /**
     * 通用机打发票识别.
     *
     * @see https://cloud.tencent.com/document/product/866/37837
     *
     * @param mixed $images
     *
     * @return mixed
     */
    public function invoiceGeneral($images, array $options = [])
    {
        return $this->request('InvoiceGeneralOCR', $images, $options);
    }

    /**
     * 汽车票识别.
     *
     * @see https://cloud.tencent.com/document/product/866/37838
     *
     * @param mixed $images
     *
     * @return mixed
     */
    public function busInvoice($images, array $options = [])
    {
        return $this->request('BusInvoiceOCR', $images, $options);
    }

    /**
     * 完税证明识别.
     *
     * @see https://cloud.tencent.com/document/product/866/37839
     *
     * @param mixed $images
     *
     * @return mixed
     */
    public function dutyPaidProof($images, array $options = [])
    {
        return $this->request('DutyPaidProofOCR', $images, $options);
    }

    /**
     * 金融票据切片识别.
     *
     * @see https://cloud.tencent.com/document/product/866/38295
     *
     * @param mixed $images
     *
     * @return mixed
     */
    public function finanBillSlice($images, array $options = [])
    {
        return $this->request('FinanBillSliceOCR', $images, $options);
    }

    /**
     * 金融票据整单识别.
     *
     * @see https://cloud.tencent.com/document/product/866/38296
     *
     * @param mixed $images
     *
     * @return mixed
     */
    public function finanBill($images, array $options = [])
    {
        return $this->request('FinanBillOCR', $images, $options);
    }

    /**
     * 车辆VIN码识别.
     *
     * @see https://cloud.tencent.com/document/product/866/34935
     *
     * @param mixed $images
     *
     * @return mixed
     */
    public function vin($images, array $options = [])
    {
        return $this->request('VinOCR', $images, $options);
    }

    /**
     * 行驶证识别.
     *
     * @see https://cloud.tencent.com/document/product/866/36209
     *
     * @param mixed $images
     *
     * @return mixed
     */
    public function vehicleLicense($images, array $options = [])
    {
        return $this->request('VehicleLicenseOCR', $images, $options);
    }

    /**
     * 车牌识别.
     *
     * @see https://cloud.tencent.com/document/product/866/36211
     *
     * @param mixed $images
     *
     * @return mixed
     */
    public function licensePlate($images, array $options = [])
    {
        return $this->request('LicensePlateOCR', $images, $options);
    }

    /**
     * 驾驶证识别.
     *
     * @see https://cloud.tencent.com/document/product/866/36213
     *
     * @param mixed $images
     *
     * @return mixed
     */
    public function driverLicense($images, array $options = [])
    {
        return $this->request('DriverLicenseOCR', $images, $options);
    }

    /**
     * 机动车登记证书识别.
     *
     * @see https://cloud.tencent.com/document/product/866/38297
     *
     * @param mixed $images
     *
     * @return mixed
     */
    public function vehicleRegCert($images, array $options = [])
    {
        return $this->request('VehicleRegCertOCR', $images, $options);
    }

    /**
     * 网约车驾驶证识别.
     *
     * @see https://cloud.tencent.com/document/product/866/47165
     *
     * @param mixed $images
     *
     * @return mixed
     */
    public function rideHailingDriverLicense($images, array $options = [])
    {
        return $this->request('RideHailingDriverLicenseOCR', $images, $options);
    }

    /**
     * 网约车运输证识别.
     *
     * @see https://cloud.tencent.com/document/product/866/47325
     *
     * @param mixed $images
     *
     * @return mixed
     */
    public function rideHailingTransportLicense($images, array $options = [])
    {
        return $this->request('RideHailingTransportLicenseOCR', $images, $options);
    }

    // 行业文档识别相关接口

    /**
     * 表格识别（V2).
     *
     * @see https://cloud.tencent.com/document/product/866/49525
     *
     * @param mixed $images
     *
     * @return mixed
     */
    public function recognizeTable($images, array $options = [])
    {
        return $this->request('RecognizeTableOCR', $images, $options);
    }

    /**
     * 表格识别（V1).
     *
     * 此接口为表格识别的旧版本服务，不再进行服务升级，建议您使用识别能力更强、服务性能更优的新版表格识别。
     *
     * @see https://cloud.tencent.com/document/product/866/34936
     *
     * @param mixed $images
     *
     * @return mixed
     */
    public function table($images, array $options = [])
    {
        return $this->recognizeTable($images, $options);
    }

    /**
     * 算式识别.
     *
     * @see https://cloud.tencent.com/document/product/866/34939
     *
     * @param mixed $images
     *
     * @return mixed
     */
    public function arithmetic($images, array $options = [])
    {
        return $this->request('ArithmeticOCR', $images, $options);
    }

    /**
     * 数学公式识别.
     *
     * @see https://cloud.tencent.com/document/product/866/38293
     *
     * @param mixed $images
     *
     * @return mixed
     */
    public function formula($images, array $options = [])
    {
        return $this->request('FormulaOCR', $images, $options);
    }

    /**
     * 数学试题识别.
     *
     * @see https://cloud.tencent.com/document/product/866/38294
     *
     * @param mixed $images
     *
     * @return mixed
     */
    public function eduPaper($images, array $options = [])
    {
        return $this->request('EduPaperOCR', $images, $options);
    }

    /**
     * 保险单据识别.
     *
     * @see https://cloud.tencent.com/document/product/866/38848
     *
     * @param mixed $images
     *
     * @return mixed
     */
    public function insuranceBill($images, array $options = [])
    {
        return $this->request('InsuranceBillOCR', $images, $options);
    }

    /**
     * 印章识别.
     *
     * @see https://cloud.tencent.com/document/product/866/45807
     *
     * @param mixed $images
     *
     * @return mixed
     */
    public function seal($images, array $options = [])
    {
        return $this->request('SealOCR', $images, $options);
    }

    // 智能扫码相关接口

    /**
     * 条码信息查询.
     *
     * @see https://cloud.tencent.com/document/product/866/45513
     *
     * @param mixed $images
     *
     * @return mixed
     */
    public function queryBarCode($images, array $options = [])
    {
        return $this->request('QueryBarCode', $images, $options);
    }

    /**
     * 二维码和条形码识别.
     *
     * @see https://cloud.tencent.com/document/product/866/38292
     *
     * @param mixed $images
     *
     * @return mixed
     */
    public function qrcode($images, array $options = [])
    {
        return $this->request('QrcodeOCR', $images, $options);
    }

    // 营业执照核验相关接口

    /**
     * 营业执照识别及核验（详细版）.
     *
     * @see https://cloud.tencent.com/document/product/866/47278
     *
     * @param mixed $images
     *
     * @return mixed
     */
    public function verifyBizLicense($images, array $options = [])
    {
        return $this->request('VerifyBizLicense', $images, $options);
    }

    /**
     * 营业执照识别及核验（基础版）.
     *
     * @see https://cloud.tencent.com/document/product/866/47279
     *
     * @param mixed $images
     *
     * @return mixed
     */
    public function verifyBasicBizLicense($images, array $options = [])
    {
        return $this->request('VerifyBasicBizLicense', $images, $options);
    }

    // 增值税发票核验相关接口

    /**
     * 增值税发票核验.
     *
     * @see https://cloud.tencent.com/document/product/866/47324
     *
     * @param mixed $images
     *
     * @return mixed
     */
    public function vatInvoiceVerify($images, array $options = [])
    {
        return $this->request('VatInvoiceVerify', $images, $options);
    }
}
