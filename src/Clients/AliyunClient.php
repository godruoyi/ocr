<?php

/*
 * This file is part of the godruoyi/ocr.
 *
 * (c) Godruoyi <gmail@godruoyi.com>
 *
 * This source file is subject to the MIT license that is bundled.
 */

namespace Godruoyi\OCR\Clients;

use Godruoyi\OCR\Requests\AliyunRequest;

/**
 * 印刷文字识别.
 *
 * @author    godruoyi godruoyi@gmail.com>
 * @copyright 2017
 *
 * @see https://data.aliyun.com/product/ocr
 * @see https://github.com/godruoyi/ocr
 *
 * @version 2.0
 *
 * @method array idcard($files, $options = [])            身份证识别
 * @method array vehicle($files, $options = [])           行驶证识别
 * @method array driverLicense($files, $options = [])     驾驶证识别
 * @method array businessLicense($files, $options = [])   营业执照识别
 * @method array bankAccountPermit($files, $options = []) 营业执照识别
 * @method array bankCard($files, $options = [])          银行卡识别
 * @method array businessCard($files, $options = [])      名片识别
 * @method array trainTicket($files, $options = [])       火车票识别
 * @method array vehiclePlate($files, $options = [])      车牌识别
 * @method array general($files, $options = [])           通用文字识别
 * @method array passport($files, $options = [])          护照
 * @method array tableParse($files, $options = [])        表格识别
 * @method array vin($files, $options = [])               vin码识别
 * @method array generalAdvanced($files, $options = [])   通用文字识别高精版
 * @method array invoice($files, $options = [])           增值税电子发票识别
 * @method array houseCert($files, $options = [])         房产证图片文字识别
 * @method array document($files, $options = [])          文档小说图片文字识别
 * @method array ecommerce($files, $options = [])         电商图片文字识别
 * @method array ugc($files, $options = [])               网络UGC图片文字识
 * @method array custom($files, $options = [])            自定义模板识别
 */
class AliyunClient extends Client
{
    const OCR_IDCARD = 'https://dm-51.data.aliyun.com/rest/160601/ocr/ocr_idcard.json';
    const OCR_VEHICLE = 'https://dm-53.data.aliyun.com/rest/160601/ocr/ocr_vehicle.json';
    const OCR_DRIVER_LICENSE = 'https://dm-52.data.aliyun.com/rest/160601/ocr/ocr_driver_license.json';
    const OCR_BUSINESS_LICENSE = 'https://dm-58.data.aliyun.com/rest/160601/ocr/ocr_business_license.json';
    const OCR_BANK_ACCOUNT_PERMIT = 'https://ocrapi-bank-account-permit.taobao.com/ocrservice/bankAccountPermit';
    const OCR_BANK_CARD = 'http://yhk.market.alicloudapi.com/rest/160601/ocr/ocr_bank_card.json';
    const OCR_BUSINESS_CARD = 'https://dm-57.data.aliyun.com/rest/160601/ocr/ocr_business_card.json';
    const OCR_TRAIN_TICKET = 'http://ocrhcp.market.alicloudapi.com/api/predict/ocr_train_ticket';
    const OCR_VEHICLE_PLATE = 'http://ocrcp.market.alicloudapi.com/rest/160601/ocr/ocr_vehicle_plate.json';
    const OCR_GENERAL = 'http://tysbgpu.market.alicloudapi.com/api/predict/ocr_general';
    const OCR_PASSPORT = 'https://ocrhz.market.alicloudapi.com/rest/160601/ocr/ocr_passport.json';
    const TABLE_PARSE = 'https://form.market.alicloudapi.com/api/predict/ocr_table_parse';
    const OCR_VIN = 'https://vin.market.alicloudapi.com/api/predict/ocr_vin';
    const OCR_INVOICE = 'https://ocrapi-invoice.taobao.com/ocrservice/invoice';
    const OCR_GENERAL_ADVANCED = 'https://ocrapi-advanced.taobao.com/ocrservice/advanced';
    const OCR_HOUSECERT = 'https://ocrapi-house-cert.taobao.com/ocrservice/houseCert';
    const OCR_DOCUMENT = 'https://ocrapi-document.taobao.com/ocrservice/document';
    const OCR_ECOMMERCE = 'https://ocrapi-ecommerce.taobao.com/ocrservice/ecommerce';
    const OCR_UGC = 'https://ocrapi-ugc.taobao.com/ocrservice/ugc';
    const OCR_CUSTOM = 'https://ocrdiy.market.alicloudapi.com/api/predict/ocr_sdt';

    /**
     * Register Aliyun request.
     *
     * @param AppCode $appcode
     */
    public function __construct(AliyunRequest $request)
    {
        $this->request = $request;
    }

    /**
     * 印刷文字识别_身份证识别.
     *
     * @see https://market.aliyun.com/products/57124001/cmapi010401.html#sku=yuncode440100000
     *
     * @param string|SplFIleInfo $images
     * @param array              $options
     *                                    参数              是否可选     类型        可选范围/说明
     *                                    side              N            string      默认face，身份证正反面类型:face/back
     *
     * @return array
     */
    public function idcard($images, array $options = [])
    {
        $options['side'] = $options['side'] ?? 'face';

        return $this->request(self::OCR_IDCARD, $images, $options);
    }

    /**
     * 印刷文字识别_行驶证识别.
     *
     * @see https://market.aliyun.com/products/57002003/cmapi011791.html
     *
     * @param string|SplFIleInfo $images
     * @param array              $options
     *                                    side              N            string      默认face，行驶证正反面类型:face/back
     *
     * @return array
     */
    public function vehicle($images, array $options = [])
    {
        $options['side'] = $options['side'] ?? 'face';

        return $this->request(self::OCR_VEHICLE, $images, $options);
    }

    /**
     * 印刷文字识别-驾驶证识别.
     *
     * @see https://market.aliyun.com/products/57002002/cmapi010402.html
     *
     * @param string|SplFIleInfo $images
     * @param array              $options
     *                                    参数              是否可选     类型        可选范围/说明
     *                                    side              N            string      默认face，驾驶证首页/副页:face/back
     *
     * @return array
     */
    public function driverLicense($images, array $options = [])
    {
        $options['side'] = $options['side'] ?? 'face';

        return $this->request(self::OCR_DRIVER_LICENSE, $images, $options);
    }

    /**
     * 印刷文字识别-营业执照识别.
     *
     * @see https://market.aliyun.com/products/57124001/cmapi013592.html
     *
     * @param string|SplFIleInfo $images
     * @param array              $options
     *                                    null
     *
     * @return array
     */
    public function businessLicense($images, array $options = [])
    {
        return $this->request(self::OCR_BUSINESS_LICENSE, $images, $options);
    }

	/**
	 * 印刷文字识别-银行开户许可证识别.
	 *
	 * @see https://market.aliyun.com/products/57124001/cmapi00042885.html
	 *
	 * @param string|SplFIleInfo $images
	 * @param array              $options
	 *                                    null
	 *
	 * @return array
	 */
	public function bankAccountPermit($images, array $options = [])
	{
		return $this->request(self::OCR_BANK_ACCOUNT_PERMIT, $images, $options);
	}

    /**
     * 印刷文字识别-银行卡识别.
     *
     * @see https://market.aliyun.com/products/57124001/cmapi016870.html
     *
     * @param string|SplFIleInfo $images
     * @param array              $options
     *                                    null
     *
     * @return array
     */
    public function bankCard($images, array $options = [])
    {
        return $this->request(self::OCR_BANK_CARD, $images, $options);
    }

    /**
     * 印刷文字识别-名片识别.
     *
     * @see https://market.aliyun.com/products/57124001/cmapi013591.html
     *
     * @param string|SplFIleInfo $images
     * @param array              $options
     *                                    null
     *
     * @return array
     */
    public function businessCard($images, array $options = [])
    {
        return $this->request(self::OCR_BUSINESS_CARD, $images, $options);
    }

    /**
     * 印刷文字识别_护照识别.
     *
     * @see https://market.aliyun.com/products/57124001/cmapi016682.html
     *
     * @param string|SplFIleInfo $images
     * @param array              $options
     *                                    null
     *
     * @return array
     */
    public function passport($images, array $options = [])
    {
        return $this->request(self::OCR_PASSPORT, $images, $options);
    }

    /**
     * 表格识别.
     *
     * @see https://market.aliyun.com/products/57124001/cmapi024968.html
     *
     * @param string|SplFIleInfo $images
     * @param array              $options
     *                                    参数              是否可选     类型        可选范围/说明
     *                                    format             N          string      format 输出格式html/json/xlsx
     *                                    dir_assure         N          bool        图片方向是否确定是正向的: true(确定)/false(不确定)
     *                                    line_less          N          bool        是否无线条: true(无线条,或者只有横线没有竖线)/false(有线条)
     *                                    skip_detection     N          bool        是否跳过检测，如果没有检测到表格，可以设置"skip_detection":true
     *
     * @return array
     */
    public function tableParse($images, array $options = [])
    {
        $options['format'] = $options['format'] ?? 'html';

        return $this->request(self::TABLE_PARSE, $images, $options);
    }

    /**
     * vin码识别.
     *
     * @see https://market.aliyun.com/products/57124001/cmapi023049.html
     *
     * @param string|SplFIleInfo $images
     * @param array              $options
     *                                    参数              是否可选     类型        可选范围/说明
     *
     * @return array
     */
    public function vin($images, array $options = [])
    {
        return $this->request(self::OCR_VIN, $images, $options);
    }

    /**
     * 印刷文字识别-火车票识别.
     *
     * @see https://market.aliyun.com/products/57124001/cmapi020096.html
     *
     * @param string|SplFIleInfo $images
     * @param array              $options
     *                                    null
     *
     * @return array
     */
    public function trainTicket($images, array $options = [])
    {
        $options['_format'] = 'basic';

        return $this->request(self::OCR_TRAIN_TICKET, $images, $options);
    }

    /**
     * 印刷文字识别-车牌识别.
     *
     * @see https://market.aliyun.com/products/57124001/cmapi020094.html
     *
     * @param string|SplFIleInfo $images
     * @param array              $options
     *                                    参数              是否可选     类型        可选范围/说明
     *                                    multi_crop       N           boolean    当设成true时,会做多crop预测，只有当多crop返回的结果一致，
     *                                    并且置信度>0.9时，才返回结果
     *
     * @return array
     */
    public function vehiclePlate($images, array $options = [])
    {
        $options['multi_crop'] = $options['multi_crop'] ?? false;

        return $this->request(self::OCR_VEHICLE_PLATE, $images, $options);
    }

    /**
     * 印刷文字识别-通用文字识别.
     *
     * @see https://market.aliyun.com/products/57124001/cmapi020020.html
     *
     * @param string|SplFIleInfo $images
     * @param array              $options
     *                                    参数              是否可选     类型        可选范围/说明
     *                                    min_size  16,                           图片中文字的最小高度，单位像素
     *                                    output_prob  true,                      是否输出文字框的概率
     *                                    output_keypoints false,                 是否输出文字框角点
     *                                    skip_detection false                    是否跳过文字检测步骤直接进行文字识别
     *                                    without_predicting_direction false      是否关闭文字行方向预测
     *
     * @return array
     */
    public function general($images, array $options = [])
    {
        $options['_format'] = 'basic';
        $options['min_size'] = $options['min_size'] ?? 16;

        return $this->request(self::OCR_GENERAL, $images, $options);
    }

    /**
     * 通用文字识别－高精版.
     *
     * @see https://market.aliyun.com/products/57124001/cmapi028554.html
     *
     * @param string|SplFIleInfo $images
     * @param array              $options
     *                                    参数              是否可选     类型        可选范围/说明
     *                                    prob              N           bool        是否需要识别结果中每一行的置信度，默认不需要。 true：需要 false：不需要
     *                                    charInfo          N           bool        是否需要单字识别功能，默认不需要。 true：需要 false：不需要
     *                                    rotate            N           bool        是否需要自动旋转功能，默认不需要。 true：需要 false：不需要
     *                                    table             N           bool        是否需要表格识别功能，默认不需要。 true：需要 false：不需要
     *                                    sortPage          N           bool        字块返回顺序，false表示从左往右，从上到下的顺序，true表示从上到下，从左往右的顺序，默认false
     *
     * @return array
     */
    public function generalAdvanced($images, array $options = [])
    {
        $options['_format'] = 'imgorurl';

        $options = array_merge([
            'prob' => false,
            'charInfo' => false,
            'rotate' => false,
            'table' => false,
            'sortPage' => false,
        ], $options);

        return $this->request(self::OCR_GENERAL_ADVANCED, $images, $options);
    }

    /**
     * 增值税发票识别.
     *
     * @see https://market.aliyun.com/products/57124001/cmapi027758.html
     *
     * @param string|SplFIleInfo $images
     * @param array              $options
     *                                    参数              是否可选     类型        可选范围/说明
     *
     * @return array
     */
    public function invoice($images, array $options = [])
    {
        $options['_format'] = 'imgorurl';

        return $this->request(self::OCR_INVOICE, $images, $options);
    }

    /**
     * 印刷文字识别-房产证识别.
     *
     * @see https://market.aliyun.com/products/57124001/cmapi028523.html
     *
     * @param string|SplFIleInfo $images
     * @param array              $options
     *                                    参数              是否可选     类型        可选范围/说明
     *
     * @return array
     */
    public function houseCert($images, array $options = [])
    {
        $options['_format'] = 'imgorurl';

        return $this->request(self::OCR_HOUSECERT, $images, $options);
    }

    /**
     * 印刷文字识别－文档小说图片文字识别.
     *
     * @see https://market.aliyun.com/products/57124001/cmapi023866.html
     *
     * @param string|SplFIleInfo $images
     * @param array              $options
     *                                    参数              是否可选     类型        可选范围/说明
     *                                    prob              N           bool        是否需要识别结果中每一行的置信度，默认不需要。 true：需要 false：不需要
     *
     * @return array
     */
    public function document($images, array $options = [])
    {
        $options['_format'] = 'imgorurl';

        return $this->request(self::OCR_DOCUMENT, $images, $options);
    }

    /**
     * 印刷文字识别－网络UGC图片文字识别.
     *
     * @see https://market.aliyun.com/products/57124001/cmapi023869.html
     *
     * @param string|SplFIleInfo $images
     * @param array              $options
     *                                    参数              是否可选     类型        可选范围/说明
     *                                    prob              N           bool        是否需要识别结果中每一行的置信度，默认不需要。 true：需要 false：不需要
     *
     * @return array
     */
    public function ugc($images, array $options = [])
    {
        $options['_format'] = 'imgorurl';

        return $this->request(self::OCR_UGC, $images, $options);
    }

    /**
     * 自定义模板OCR识别/OCR文字识别.
     *
     * @see https://market.aliyun.com/products/57124001/cmapi029975.html
     *
     * @param string|SplFIleInfo $images
     * @param array              $options
     *                                    参数              是否可选     类型        可选范围/说明
     *                                    prob              N           bool        是否需要识别结果中每一行的置信度，默认不需要。 true：需要 false：不需要
     *
     * @return array
     */
    public function custom($images, array $options = [])
    {
        $options['_format'] = 'basic';

        return $this->request(self::OCR_CUSTOM, $images, $options);
    }

    /**
     * 印刷文字识别－电商图片文字识别.
     *
     * @see https://market.aliyun.com/products/57124001/cmapi023866.html
     *
     * @param string|SplFIleInfo $images
     * @param array              $options
     *                                    参数              是否可选     类型        可选范围/说明
     *                                    prob              N           bool        是否需要识别结果中每一行的置信度，默认不需要。 true：需要 false：不需要
     *
     * @return array
     */
    public function ecommerce($images, array $options = [])
    {
        $options['_format'] = 'imgorurl';

        return $this->request(self::OCR_ECOMMERCE, $images, $options);
    }
}
