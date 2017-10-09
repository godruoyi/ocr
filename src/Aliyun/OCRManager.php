<?php

namespace Godruoyi\OCR\Aliyun;

use Godruoyi\OCR\AbstractAPI;
use Godruoyi\OCR\Support\FileConverter;

/**
 * 印刷文字识别
 *
 * @see https://data.aliyun.com/product/ocr
 *
 * aliyun 不支持批量操作
 */
class OCRManager extends AbstractAPI
{
    /**
     * APPCode Instance
     *
     * @var AppCode
     */
    protected $appcode;

    const OCR_IDCARD = 'https://dm-51.data.aliyun.com/rest/160601/ocr/ocr_idcard.json';
    const OCR_IDCARD1 = 'https://dm-51.data.aliyun.com/rest/160601/ocr/ocr_idcard.json';
    const OCR_IDCARD2 = 'https://dm-51.data.aliyun.com/rest/160601/ocr/ocr_idcard.json';
    const OCR_IDCARD3 = 'https://dm-51.data.aliyun.com/rest/160601/ocr/ocr_idcard.json';
    const OCR_IDCARD4 = 'https://dm-51.data.aliyun.com/rest/160601/ocr/ocr_idcard.json';
    const OCR_IDCARD5 = 'https://dm-51.data.aliyun.com/rest/160601/ocr/ocr_idcard.json';
    const OCR_IDCARD6 = 'https://dm-51.data.aliyun.com/rest/160601/ocr/ocr_idcard.json';
    const OCR_IDCARD7 = 'https://dm-51.data.aliyun.com/rest/160601/ocr/ocr_idcard.json';
    const OCR_IDCARD8 = 'https://dm-51.data.aliyun.com/rest/160601/ocr/ocr_idcard.json';
    const OCR_IDCARD9 = 'https://dm-51.data.aliyun.com/rest/160601/ocr/ocr_idcard.json';
    const OCR_IDCARD10 = 'https://dm-51.data.aliyun.com/rest/160601/ocr/ocr_idcard.json';
    const OCR_IDCARD11 = 'https://dm-51.data.aliyun.com/rest/160601/ocr/ocr_idcard.json';
    const OCR_IDCARD12 = 'https://dm-51.data.aliyun.com/rest/160601/ocr/ocr_idcard.json';
    const OCR_IDCARD13 = 'https://dm-51.data.aliyun.com/rest/160601/ocr/ocr_idcard.json';
    const OCR_IDCARD14 = 'https://dm-51.data.aliyun.com/rest/160601/ocr/ocr_idcard.json';
    const OCR_IDCARD15 = 'https://dm-51.data.aliyun.com/rest/160601/ocr/ocr_idcard.json';

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


    public function request($url, $images, array $options = [])
    {
        $images = is_array($images) ? $images[0] : $images;

        //阿里云固定请求格式
        $data = [
            'inputs' => [
                'images' => [
                    'dataType' => 50,
                    'dataValue' => '',//FileConverter::toBase64Encode($images)
                ],
                'configure' => [
                    'dataType' => 50,
                    'dataValue' => $options
                ]
            ]
        ];

        $response = $this->getHttpClient()->setHeaders($this->appcode->getAppCodeHeader())->json($url, $data);

// getHeader

        dump($response);
        exit;

        $body = $this->toArray($response);



        return $response;
    }
}
