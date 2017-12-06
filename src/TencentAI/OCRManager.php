<?php
/**
 * Created by PhpStorm.
 * User: leohu
 * Date: 2017/12/6
 * Time: 下午12:44
 */

namespace Godruoyi\OCR\TencentAI;


use Godruoyi\OCR\Support\Http;
use GuzzleHttp\Exception\ClientException;

class OCRManager
{

    /**
     * TENCENT AI OCR BIZLICENSEOCR URL
     */
    const URL_OCR_BIZLICENSEOCR = 'https://api.ai.qq.com/fcgi-bin/ocr/ocr_bizlicenseocr';

    /**
     * Tencent AI Authorization
     *
     * @var Authorization
     */
    protected $authorization;

    /**
     * OCRManager constructor.
     *
     * @param Authorization $authorization
     */
    public function __construct(Authorization $authorization)
    {

        $this->authorization = $authorization;

    }

    /**
     * OCR营业执照识别
     *
     * @param $imagePath OCR图片路径
     *
     * @return array
     * @throws \Exception
     */
    public function bizlicenseocr($imagePath)
    {
        if(!file_exists($imagePath)) {
            throw new \Exception('image file not exists');
        }

        $binary = file_get_contents($imagePath);
        $image = base64_encode($binary);
        $params = [
            'image' => $image
        ];

        return $this->request('POST', self::URL_OCR_BIZLICENSEOCR, $params);

    }

    /**
     * 发起HTTP请求，并返回JSON
     *
     * @param $method
     * @param $url
     * @param $params
     *
     * @return array
     * @throws \Exception
     */
    protected function request($method, $url, $params) {

        try {
            $params = $this->authorization->appendSignature($params);

            $http = new HTTP();
            $response = $http->request($method, $url, [
                'form_params' => $params
            ]);

            return $http->parseJson($response);
        } catch(ClientException $ce) {
            throw $ce;
        } catch(\Exception $e) {
            throw new \Exception("invalid response: " . $response->getBody());
        }

    }


}