<?php

/*
 * This file is part of the godruoyi/ocr.
 *
 * (c) Godruoyi <gmail@godruoyi.com>
 *
 * This source file is subject to the MIT license that is bundled.
 */

namespace Godruoyi\OCR\Clients;

use Godruoyi\OCR\Requests\BaiduRequest;

/**
 * Baidu OCR 识别.
 *
 * @author    godruoyi godruoyi@gmail.com>
 * @copyright 2019
 *
 * @see  https://ai.baidu.com/
 * @see  https://github.com/godruoyi/ocr
 */
class BaiduClient extends Client
{
    /**
     * Register auth request instance.
     */
    public function __construct(BaiduRequest $request)
    {
        $this->request = $request;
    }

    /**
     * 通用文字识别（标准版）.
     *
     * @see https://ai.baidu.com/ai-doc/OCR/zk3h7xz52 查看请求参数
     *
     * @param string|\SplFileInfo $images
     *
     * @return array
     */
    public function generalBasic($images, array $options = [])
    {
        return $this->request('general_basic', $images, $options);
    }

    /**
     * 通用文字识别（高精度版）.
     *
     * @see https://ai.baidu.com/ai-doc/OCR/1k3h7y3db 查看请求参数
     *
     * @param string|\SplFileInfo $images
     *
     * @return array
     */
    public function accurateBasic($images, array $options = [])
    {
        $options['_urlauto2base64'] = true;

        return $this->request('accurate_basic', $images, $options);
    }

    /**
     * 通用文字识别（标准含位置版）.
     *
     * @see https://ai.baidu.com/ai-doc/OCR/vk3h7y58v 查看请求参数
     *
     * @param string|\SplFileInfo $images
     *
     * @return array
     */
    public function general($images, array $options = [])
    {
        return $this->request('general', $images, $options);
    }

    /**
     * 通用文字识别（高精度含位置版）.
     *
     * @see https://ai.baidu.com/ai-doc/OCR/tk3h7y2aq 查看请求参数
     *
     * @param string|\SplFileInfo $images
     *
     * @return array
     */
    public function accurate($images, array $options = [])
    {
        $options['_urlauto2base64'] = true;

        return $this->request('accurate', $images, $options);
    }

    /**
     * 办公文档识别.
     *
     * @see https://ai.baidu.com/ai-doc/OCR/ykg9c09ji 查看请求参数
     *
     * @param string|\SplFileInfo $images
     *
     * @return array
     */
    public function docAnalysisOffice($images, array $options = [])
    {
        $options['_urlauto2base64'] = true;

        return $this->request('doc_analysis_office', $images, $options);
    }

    /**
     * 手写文字识别.
     *
     * @see  https://ai.baidu.com/ai-doc/OCR/hk3h7y2qq 查看完整请求参数
     *
     * @param string|\SplFileInfo $images
     *
     * @return array
     */
    public function handwriting($images, array $options = [])
    {
        $options['_urlauto2base64'] = true;

        return $this->request('handwriting', $images, $options);
    }

    /**
     * 身份证识别.
     *
     * @see https://ai.baidu.com/ai-doc/OCR/rk3h7xzck
     *
     * @param string|\SplFileInfo $images
     *
     * @return array
     */
    public function idcard($images, array $options = [])
    {
        $options['_urlauto2base64'] = true;

        return $this->request('idcard', $images, $options);
    }

    /**
     * 银行卡识别.
     *
     * @see https://ai.baidu.com/ai-doc/OCR/ak3h7xxg3
     *
     * @param string|\SplFileInfo $images
     *
     * @return array
     */
    public function bankcard($images, array $options = [])
    {
        return $this->request('bankcard', $images, $options);
    }

    /**
     * 营业执照识别.
     *
     * @see https://ai.baidu.com/ai-doc/OCR/sk3h7y3zs
     *
     * @param string|\SplFileInfo $images
     *
     * @return array
     */
    public function businessLicense($images, array $options = [])
    {
        $options['_urlauto2base64'] = true;

        return $this->request('business_license', $images, $options);
    }

    /**
     * 护照识别.
     *
     * @see https://ai.baidu.com/ai-doc/OCR/Wk3h7y1gi
     *
     * @param string|\SplFileInfo $images
     *
     * @return array
     */
    public function passport($images, array $options = [])
    {
        return $this->request('passport', $images, $options);
    }

    /**
     * 名片识别.
     *
     * @see https://ai.baidu.com/ai-doc/OCR/5k3h7xyi2
     *
     * @param string|\SplFileInfo $images
     *
     * @return array
     */
    public function businessCard($images, array $options = [])
    {
        return $this->request('business_card', $images, $options);
    }

    /**
     * 户口本识别.
     *
     * @see https://ai.baidu.com/ai-doc/OCR/ak3h7xzk7
     *
     * @param string|\SplFileInfo $images
     *
     * @return array
     */
    public function householdRegister($images, array $options = [])
    {
        return $this->request('household_register', $images, $options);
    }

    /**
     * 出生医学证明识别.
     *
     * @see https://ai.baidu.com/ai-doc/OCR/mk3h7y1o6
     *
     * @param string|\SplFileInfo $images
     *
     * @return array
     */
    public function birthCertificate($images, array $options = [])
    {
        return $this->request('birth_certificate', $images, $options);
    }

    /**
     * 多卡证类别检测.
     *
     * @see https://ai.baidu.com/ai-doc/OCR/nkbq6wxxy
     *
     * @param string|\SplFileInfo $images
     *
     * @return array
     */
    public function multiCardClassify($images, array $options = [])
    {
        return $this->request('multi_card_classify', $images, $options);
    }

    /**
     * 港澳通行证识别.
     *
     * @see https://ai.baidu.com/ai-doc/OCR/4k3h7y0ly
     *
     * @param string|\SplFileInfo $images
     *
     * @return array
     */
    public function hkMacauExitentrypermit($images, array $options = [])
    {
        return $this->request('HK_Macau_exitentrypermit', $images, $options);
    }

    /**
     * 台湾通行证识别.
     *
     * @see https://ai.baidu.com/ai-doc/OCR/kk3h7y2yc
     *
     * @param string|\SplFileInfo $images
     *
     * @return array
     */
    public function taiwanExitentrypermit($images, array $options = [])
    {
        return $this->request('taiwan_exitentrypermit', $images, $options);
    }

    /**
     * 表格文字识别(异步接口).
     *
     * 接口为异步接口，分为两个 API：提交请求接口、获取结果接口
     *
     * @see https://ai.baidu.com/ai-doc/OCR/Ik3h7y238
     *
     * @param string|\SplFileInfo $images
     *
     * @return array
     */
    public function asynTable($images, array $options = [])
    {
        $uri = 'https://aip.baidubce.com/rest/2.0/solution/v1/form_ocr/request';

        return $this->request($uri, $images, $options);
    }

    /**
     * 表格文字识别(异步接口) - 获取结果接口.
     *
     * @see https://ai.baidu.com/ai-doc/OCR/Ik3h7y238
     *
     * @param string|\SplFileInfo $requestId
     * @param array $requestType 期望获取结果的类型，取值为 “excel” 时返回 xls 文件的地址，
     *                                         取值为 “json” 时返回 json 格式的字符串,默认为 ”excel”
     *
     * @throws \RuntimeException
     *
     * @return array
     */
    public function asynTableInfo(string $requestId, string $requestType = null)
    {
        $uri = 'https://aip.baidubce.com/rest/2.0/solution/v1/form_ocr/get_request_result';

        return $this->request($uri, [
            'result_type' => $requestType,
            'request_id' => $requestId,
        ]);
    }

    /**
     * 表格文字识别(同步接口).
     *
     * 此接口需要您在 申请页面 中提交合作咨询开通权限，对出生时间、姓名、性别、出生证编号、父亲姓名、母亲姓名字段进行识别
     *
     * @see https://ai.baidu.com/ai-doc/OCR/ik3h7xyxf 查看请求参数
     *
     * @param string|\SplFileInfo $images
     *
     * @return array
     */
    public function syncTable($images, array $options = [])
    {
        return $this->request('form', $images, $options);
    }

    /**
     * 通用票据识别.
     *
     * @see https://ai.baidu.com/ai-doc/OCR/6k3h7y11b
     *
     * @param string|\SplFileInfo $images
     *
     * @return array
     */
    public function receipt($images, array $options = [])
    {
        $options['_urlauto2base64'] = true;

        return $this->request('receipt', $images, $options);
    }

    /**
     * 医疗发票识别.
     *
     * @see https://ai.baidu.com/ai-doc/OCR/yke30j1hq
     *
     * @param mixed $images
     *
     * @return array
     */
    public function medicalInvoice($images, array $options = [])
    {
        $options['_urlauto2base64'] = true;

        return $this->request('medical_invoice', $images, $options);
    }

    /**
     * 医疗费用结算单识别.
     *
     * @see https://ai.baidu.com/ai-doc/OCR/Jke30ki7d
     *
     * @param mixed $images
     *
     * @return array
     */
    public function medicalStatement($images, array $options = [])
    {
        $options['_urlauto2base64'] = true;

        return $this->request('medical_statement', $images, $options);
    }

    /**
     * 病案首页识别.
     *
     * @see https://ai.baidu.com/ai-doc/OCR/1ke30k2s2
     *
     * @param mixed $images
     *
     * @return array
     */
    public function medicalRecord($images, array $options = [])
    {
        $options['_urlauto2base64'] = true;

        return $this->request('medical_record', $images, $options);
    }

    /**
     * 保单识别.
     *
     * @see https://ai.baidu.com/ai-doc/OCR/Wk3h7y0eb
     *
     * @param mixed $images
     *
     * @return array
     */
    public function insuranceDocuments($images, array $options = [])
    {
        $options['_urlauto2base64'] = true;

        return $this->request('insurance_documents', $images, $options);
    }

    /**
     * 增值税发票识别.
     *
     * @see https://ai.baidu.com/ai-doc/OCR/nk3h7xy2t
     *
     * @param string|\SplFileInfo $images
     *
     * @return array
     */
    public function vatInvoice($images, array $options = [])
    {
        $options['_urlauto2base64'] = true;

        return $this->request('vat_invoice', $images, $options);
    }

    /**
     * 火车票识别.
     *
     * @see https://ai.baidu.com/ai-doc/OCR/Ok3h7y35u
     *
     * @param string|\SplFileInfo $images
     *
     * @return array
     */
    public function trainTicket($images, array $options = [])
    {
        $options['_urlauto2base64'] = true;

        return $this->request('train_ticket', $images, $options);
    }

    /**
     * 出租车票识别.
     *
     * @see https://ai.baidu.com/ai-doc/OCR/Zk3h7xxnn
     *
     * @param string|\SplFileInfo $images
     *
     * @return array
     */
    public function taxiReceipt($images, array $options = [])
    {
        $options['_urlauto2base64'] = true;

        return $this->request('taxi_receipt', $images, $options);
    }

    /**
     * 定额发票识别.
     *
     * @see https://ai.baidu.com/ai-doc/OCR/lk3h7y4ev
     *
     * @param string|\SplFileInfo $images
     *
     * @return array
     */
    public function quotaInvoice($images, array $options = [])
    {
        return $this->request('quota_invoice', $images, $options);
    }

    /**
     * 驾驶证识别.
     *
     * @see https://ai.baidu.com/ai-doc/OCR/Vk3h7xzz7
     *
     * @param string|\SplFileInfo $images
     *
     * @return array
     */
    public function drivingLicense($images, array $options = [])
    {
        $options['_urlauto2base64'] = true;

        return $this->request('driving_license', $images, $options);
    }

    /**
     * 行驶证识别.
     *
     * @see https://ai.baidu.com/ai-doc/OCR/yk3h7y3ks
     *
     * @param string|\SplFileInfo $images
     *
     * @return array
     */
    public function vehicleLicense($images, array $options = [])
    {
        $options['_urlauto2base64'] = true;

        return $this->request('vehicle_license', $images, $options);
    }

    /**
     * 车牌识别.
     *
     * @see https://ai.baidu.com/ai-doc/OCR/ck3h7y191
     *
     * @param string|\SplFileInfo $images
     *
     * @return array
     */
    public function licensePlate($images, array $options = [])
    {
        $options['_urlauto2base64'] = true;

        return $this->request('license_plate', $images, $options);
    }

    /**
     * 机动车销售发票识别.
     *
     * @see https://ai.baidu.com/ai-doc/OCR/vk3h7y4tx
     *
     * @param string|\SplFileInfo $images
     *
     * @return array
     */
    public function vehicleInvoice($images, array $options = [])
    {
        $options['_urlauto2base64'] = true;

        return $this->request('vehicle_invoice', $images, $options);
    }

    /**
     * 车辆合格证识别.
     *
     * @see https://ai.baidu.com/ai-doc/OCR/yk3h7y3sc
     *
     * @param string|\SplFileInfo $images
     *
     * @return array
     */
    public function vehicleCertificate($images, array $options = [])
    {
        $options['_urlauto2base64'] = true;

        return $this->request('vehicle_certificate', $images, $options);
    }

    /**
     * 试卷分析与识别.
     *
     * @see https://ai.baidu.com/ai-doc/OCR/jk9m7mj1l
     *
     * @param string|\SplFileInfo $images
     *
     * @return array
     */
    public function docAnalysis($images, array $options = [])
    {
        $options['_urlauto2base64'] = true;

        return $this->request('doc_analysis', $images, $options);
    }

    /**
     * 公式识别.
     *
     * @see https://ai.baidu.com/ai-doc/OCR/Ok3h7xxva
     *
     * @param string|\SplFileInfo $images
     *
     * @return array
     */
    public function formula($images, array $options = [])
    {
        $options['_urlauto2base64'] = true;

        return $this->request('formula', $images, $options);
    }

    /**
     * VIN码识别.
     *
     * @see https://ai.baidu.com/ai-doc/OCR/zk3h7y51e
     *
     * @param string|\SplFileInfo $images
     *
     * @return array
     */
    public function vin($images, array $options = [])
    {
        $options['_urlauto2base64'] = true;

        return $this->request('vin_code', $images, $options);
    }

    /**
     * 二维码识别.
     *
     * @see https://ai.baidu.com/ai-doc/OCR/qk3h7y5o7
     *
     * @param string|\SplFileInfo $images
     *
     * @return array
     */
    public function qrcode($images, array $options = [])
    {
        $options['_urlauto2base64'] = true;

        return $this->request('qrcode', $images, $options);
    }

    /**
     * 数字识别.
     *
     * 对图像中的阿拉伯数字进行识别提取，适用于快递单号、手机号、充值码提取等场景
     *
     * @see https://ai.baidu.com/ai-doc/OCR/Ok3h7y1vo 查看请求参数
     *
     * @param string|\SplFileInfo $images
     *
     * @return array
     */
    public function numbers($images, array $options = [])
    {
        $options['_urlauto2base64'] = true;

        return $this->request('numbers', $images, $options);
    }

    /**
     * 网络图片文字识别.
     *
     * 针对网络图片进行专项优化，支持识别艺术字体或背景复杂的文字内容。
     *
     * @see https://ai.baidu.com/ai-doc/OCR/Sk3h7xyad 查看请求参数
     *
     * @param string|\SplFileInfo $images
     *
     * @return array
     */
    public function webimage($images, array $options = [])
    {
        return $this->request('webimage', $images, $options);
    }

    /**
     * 网络图片文字识别（含位置版）.
     *
     * @see https://ai.baidu.com/ai-doc/OCR/Nkaz574we 查看请求参数
     *
     * @param string|\SplFileInfo $images
     *
     * @return array
     */
    public function webimageLoc($images, array $options = [])
    {
        $options['_urlauto2base64'] = true;

        return $this->request('webimage_loc', $images, $options);
    }

    /**
     * 彩票识别.
     *
     * @see https://ai.baidu.com/ai-doc/OCR/ik3h7y5gl
     *
     * @param string|\SplFileInfo $images
     *
     * @return array
     */
    public function lottery($images, array $options = [])
    {
        $options['_urlauto2base64'] = true;

        return $this->request('lottery', $images, $options);
    }

    /**
     * 仪器仪表盘读数识别.
     *
     * @see https://ai.baidu.com/ai-doc/OCR/Jkafike0v
     *
     * @param string|\SplFileInfo $images
     *
     * @return array
     */
    public function meter($images, array $options = [])
    {
        $options['_urlauto2base64'] = true;

        return $this->request('meter', $images, $options);
    }

    /**
     * 印章检测.
     *
     * @see https://ai.baidu.com/ai-doc/OCR/Mk3h7y47a
     *
     * @param string|\SplFileInfo $images
     *
     * @return array
     */
    public function seal($images, array $options = [])
    {
        $options['_urlauto2base64'] = true;

        return $this->request('seal', $images, $options);
    }

    /**
     * 门脸文字识别.
     *
     * @see https://ai.baidu.com/ai-doc/OCR/wk5hw3cvo
     *
     * @param string|\SplFileInfo $images
     *
     * @return array
     */
    public function facade($images, array $options = [])
    {
        $options['_urlauto2base64'] = true;

        return $this->request('lottery', $images, $options);
    }

    /**
     * 通用机打发票识别.
     *
     * @see https://ai.baidu.com/ai-doc/OCR/Pk3h7y06q
     *
     * @param string|\SplFileInfo $images
     *
     * @return array
     */
    public function invoice($images, array $options = [])
    {
        $options['_urlauto2base64'] = true;

        return $this->request('invoice', $images, $options);
    }

    /**
     * 行程单识别.
     *
     * @see https://ai.baidu.com/ai-doc/OCR/Qk3h7xzro
     *
     * @param string|\SplFileInfo $images
     *
     * @return array
     */
    public function airTicket($images, array $options = [])
    {
        $options['_urlauto2base64'] = true;

        return $this->request('air_ticket', $images, $options);
    }

    /**
     * 汽车票识别.
     *
     * @see https://ai.baidu.com/ai-doc/OCR/Kkblx01ww
     *
     * @param string|\SplFileInfo $images
     *
     * @return array
     */
    public function busTicket($images, array $options = [])
    {
        $options['_urlauto2base64'] = true;

        return $this->request('bus_ticket', $images, $options);
    }

    /**
     * 通行费发票识别.
     *
     * @see https://ai.baidu.com/ai-doc/OCR/1kbpyx8js
     *
     * @param string|\SplFileInfo $images
     *
     * @return array
     */
    public function tollInvoice($images, array $options = [])
    {
        $options['_urlauto2base64'] = true;

        return $this->request('toll_invoice', $images, $options);
    }
}
