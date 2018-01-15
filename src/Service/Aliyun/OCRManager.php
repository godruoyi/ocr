<?php

/*
 * This file is part of the godruoyi/ocr.
 *
 * (c) godruoyi <godruoyi@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Godruoyi\OCR\Service\Aliyun;

use RuntimeException;
use Godruoyi\OCR\Support\Arr;
use Godruoyi\OCR\Support\Http;
use Godruoyi\OCR\Support\FileConverter;

/**
 * 印刷文字识别
 *
 * @author    godruoyi godruoyi@gmail.com>
 * @copyright 2017
 *
 * @see https://data.aliyun.com/product/ocr
 * @see https://github.com/godruoyi/ocr
 *
 * @method array idcard($files, $options = []) 身份证识别
 * @method array vehicle($files, $options = []) 行驶证识别
 * @method array driverLicense($files, $options = []) 驾驶证识别
 * @method array shopSign($files, $options = []) 门店识别
 * @method array english($files, $options = []) 英文识别
 * @method array businessLicense($files, $options = []) 营业执照识别
 * @method array bankCard($files, $options = []) 银行卡识别
 * @method array businessCard($files, $options = []) 名片识别
 * @method array trainTicket($files, $options = []) 火车票识别
 * @method array vehiclePlate($files, $options = []) 车牌识别
 * @method array general($files, $options = []) 通用文字识别
 */
class OCRManager
{
    /**
     * APPCode Instance
     *
     * @var AppCode
     */
    protected $appcode;

    protected $simpleRequestBody = false;

    const OCR_IDCARD = 'https://dm-51.data.aliyun.com/rest/160601/ocr/ocr_idcard.json';
    const OCR_VEHICLE = 'https://dm-53.data.aliyun.com/rest/160601/ocr/ocr_vehicle.json';
    const OCR_DRIVER_LICENSE = 'https://dm-52.data.aliyun.com/rest/160601/ocr/ocr_driver_license.json';
    const OCR_SHOP_SIGN = 'https://dm-54.data.aliyun.com/rest/160601/ocr/ocr_shop_sign.json';
    const OCR_ENGLISH = 'https://dm-55.data.aliyun.com/rest/160601/ocr/ocr_english.json';
    const OCR_BUSINESS_LICENSE = 'https://dm-58.data.aliyun.com/rest/160601/ocr/ocr_business_license.json';
    const OCR_BANK_CARD = 'http://yhk.market.alicloudapi.com/rest/160601/ocr/ocr_bank_card.json';
    const OCR_BUSINESS_CARD = 'https://dm-57.data.aliyun.com/rest/160601/ocr/ocr_business_card.json';
    const OCR_TRAIN_TICKET = 'http://ocrhcp.market.alicloudapi.com/api/predict/ocr_train_ticket';
    const OCR_VEHICLE_PLATE = 'http://ocrcp.market.alicloudapi.com/rest/160601/ocr/ocr_vehicle_plate.json';
    const OCR_GENERAL = 'http://tysbgpu.market.alicloudapi.com/api/predict/ocr_general';

    /**
     * Register APPCode
     *
     * @param AppCode $appcode
     */
    public function __construct(AppCode $appcode)
    {
        $this->appcode = $appcode;
    }

    /**
     * 印刷文字识别_身份证识别
     *
     * @see https://market.aliyun.com/products/57124001/cmapi010401.html#sku=yuncode440100000
     *
     * @param string|SplFIleInfo $images
     * @param array  $options
     *        参数              是否可选     类型        可选范围/说明
     *        side              N            string      默认face，身份证正反面类型:face/back
     *
     * @return array
     */
    public function idcard($images, array $options = [])
    {
        $options['side'] = empty($options['side']) ? 'face' : $options['side'];

        return $this->request(self::OCR_IDCARD, $images, $options);
    }

    /**
     * 印刷文字识别_行驶证识别
     *
     * @see https://market.aliyun.com/products/57002003/cmapi011791.html
     *
     * @param string|SplFIleInfo $images
     * @param array  $options
     *        null
     *
     * @return array
     */
    public function vehicle($images, array $options = [])
    {
        return $this->request(self::OCR_VEHICLE, $images, $options);
    }

    /**
     * 印刷文字识别-驾驶证识别
     *
     * @see https://market.aliyun.com/products/57002002/cmapi010402.html
     *
     * @param string|SplFIleInfo $images
     * @param array  $options
     *        参数              是否可选     类型        可选范围/说明
     *        side              N            string      默认face，驾驶证首页/副页:face/back
     *
     * @return array
     */
    public function driverLicense($images, array $options = [])
    {
        $options['side'] = empty($options['side']) ? 'face' : $options['side'];

        return $this->request(self::OCR_DRIVER_LICENSE, $images, $options);
    }

    /**
     * 印刷文字识别-门店识别
     *
     * @see https://market.aliyun.com/products/57124001/cmapi010404.html
     *
     * @param string|SplFIleInfo $images
     * @param array  $options
     *        null
     *
     * @return array
     */
    public function shopSign($images, array $options = [])
    {
        return $this->request(self::OCR_SHOP_SIGN, $images, $options);
    }

    /**
     * 印刷文字识别-英文识别
     *
     * @see https://market.aliyun.com/products/57124001/cmapi010405.html
     *
     * @param string|SplFIleInfo $images
     * @param array  $options
     *        null
     *
     * @return array
     */
    public function english($images, array $options = [])
    {
        return $this->request(self::OCR_ENGLISH, $images, $options);
    }

    /**
     * 印刷文字识别-营业执照识别
     *
     * @see https://market.aliyun.com/products/57124001/cmapi013592.html
     *
     * @param string|SplFIleInfo $images
     * @param array  $options
     *        null
     *
     * @return array
     */
    public function businessLicense($images, array $options = [])
    {
        return $this->request(self::OCR_BUSINESS_LICENSE, $images, $options);
    }

    /**
     * 印刷文字识别-银行卡识别
     *
     * @see https://market.aliyun.com/products/57124001/cmapi016870.html
     *
     * @param string|SplFIleInfo $images
     * @param array  $options
     *        null
     *
     * @return array
     */
    public function bankCard($images, array $options = [])
    {
        return $this->request(self::OCR_BANK_CARD, $images, $options);
    }

    /**
     * 印刷文字识别-名片识别
     *
     * @see https://market.aliyun.com/products/57124001/cmapi013591.html
     *
     * @param string|SplFIleInfo $images
     * @param array  $options
     *        null
     *
     * @return array
     */
    public function businessCard($images, array $options = [])
    {
        return $this->request(self::OCR_BUSINESS_CARD, $images, $options);
    }

    /**
     * 印刷文字识别-火车票识别
     *
     * @see https://market.aliyun.com/products/57124001/cmapi020096.html
     *
     * @param string|SplFIleInfo $images
     * @param array  $options
     *        null
     *
     * @return array
     */
    public function trainTicket($images, array $options = [])
    {
        $this->simpleRequestBody = true;

        return $this->request(self::OCR_TRAIN_TICKET, $images, $options);
    }

    /**
     * 印刷文字识别-车牌识别
     *
     * @see https://market.aliyun.com/products/57124001/cmapi020094.html
     *
     * @param string|SplFIleInfo $images
     * @param array  $options
     *        参数              是否可选     类型        可选范围/说明
     *        multi_crop       N           boolean    当设成true时,会做多crop预测，只有当多crop返回的结果一致，
     *                                                并且置信度>0.9时，才返回结果
     *
     * @return array
     */
    public function vehiclePlate($images, array $options = [])
    {
        return $this->request(self::OCR_VEHICLE_PLATE, $images, $options);
    }

    /**
     * 印刷文字识别-通用文字识别
     *
     * @see https://market.aliyun.com/products/57124001/cmapi020020.html
     *
     * @param string|SplFIleInfo $images
     * @param array  $options
     *        参数              是否可选     类型        可选范围/说明
     *        min_size         N           int        图片中文字的最小高度
     *        output_prob      N           boolean    是否输出文字框的概率
     *
     * @return array
     */
    public function general($images, array $options = [])
    {
        $this->simpleRequestBody = true;

        return $this->request(self::OCR_GENERAL, $images, $options);
    }


    /**
     * Request
     *
     * @param  string $url
     * @param  mixed $images
     * @param  array  $options
     *
     * @return array
     */
    public function request($url, $images, array $options = [])
    {
        $httpClient = (new Http)->setHeaders($this->appcode->getAppCodeHeader());

        try {
            $response = $httpClient->json($url, $this->getFixedFormat($images, $options));
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            if ($e->hasResponse()) {
                $response = $e->getResponse();
            }
        }

        //reset flag
        $this->simpleRequestBody = false;

        if ($response->getStatusCode() != 200) {
            $requestID = $response->getHeader('X-Ca-Request-Id');
            $messages  = $response->getHeader('X-Ca-Error-Message');

            $messages = empty($messages[0]) ? $response->getBody()->getContents() : $messages[0];

            return [
                'request_id' => current($requestID),
                'error_msg' => $messages,
            ];
        }


        $arr = $httpClient->parseJson($response);
  
        if (isset($arr['success']) && $arr['success'] === true) {
            return $arr;
        }

        if (! is_array(Arr::get($arr, 'outputs.0.outputValue.dataValue'))) {
            $arr['outputs'][0]['outputValue']['dataValue'] = json_decode($arr['outputs'][0]['outputValue']['dataValue'], true);
        }

        return $arr;
    }

    /**
     * Get aliyun fixed request format
     *
     * @param  mixed $images
     * @param  array  $options
     *
     * @return array
     */
    public function getFixedFormat($images, array $options = [])
    {
        //aliyun does not support batch operation
        $images = is_array($images) ? $images[0] : $images;

        if (FileConverter::isUrl($images)) {
            throw new RuntimeException("Aliyun ocr not support online picture.");
        }

        if ($this->simpleRequestBody) {
            return [
                'image' => FileConverter::toBase64Encode($images),
                'configure' => json_encode($options, JSON_UNESCAPED_UNICODE)
            ];
        }

        //aliyun cloud fixed request format
        return [
            'inputs' => [
                [
                    'image' => [
                        'dataType' => 50,
                        'dataValue' => FileConverter::toBase64Encode($images)
                    ],
                    'configure' => [
                        'dataType' => 50,
                        'dataValue' => json_encode($options, JSON_UNESCAPED_UNICODE)
                    ]
                ]
            ]
        ];
    }
}
