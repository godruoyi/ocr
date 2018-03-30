<?php
/**
 * Created by PhpStorm.
 * User: leohu
 * Date: 2017/12/6
 * Time: 下午12:44
 */

namespace Godruoyi\OCR\Service\TencentAI;

use Godruoyi\OCR\Support\Http;
use Godruoyi\OCR\Support\FileConverter;
use GuzzleHttp\Exception\ClientException;

/**
 * @copyright 2017
 *
 * @see  https://github.com/godruoyi/ocr
 *
 * @method array idcard($images, $options = []) 身份证识别
 * @method array namecard($images, $options = []) 名片识别
 * @method array driverlicen($images, $options = []) 行驶证驾驶证识别
 * @method array bankcard($images, $options = []) 银行卡识别
 * @method array general($images, $options = []) 通用文字识别
 * @method array bizlicense($images, $options = []) 营业执照识别
 */
class OCRManager
{
    /**
     * TENCENT AI OCR BIZLICENSEOCR URL
     */
    const URL_OCR_BIZLICENSEOCR = 'https://api.ai.qq.com/fcgi-bin/ocr/ocr_bizlicenseocr';
    const URL_OCR_IDCARD        = 'https://api.ai.qq.com/fcgi-bin/ocr/ocr_idcardocr';
    const URL_OCR_NAMECARD      = 'https://api.ai.qq.com/fcgi-bin/ocr/ocr_bcocr';
    const URL_OCR_DRIVERLICEN   = 'https://api.ai.qq.com/fcgi-bin/ocr/ocr_driverlicenseocr';
    const URL_OCR_BANKCARD      = 'https://api.ai.qq.com/fcgi-bin/ocr/ocr_creditcardocr';
    const URL_OCR_GENERAL       = 'https://api.ai.qq.com/fcgi-bin/ocr/ocr_generalocr';

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
     * 身份证OCR识别
     *
     * @param  string|\SplFileInfo|array $images
     * @param  array $options
     *
     *         参数        是否可选     类型        描述
     *         card_type   Y            int         身份证图片类型，0-正面，1-反面
     *
     * @see https://cloud.tencent.com/document/product/641/12423
     *
     * @return array
     */
    public function idcard($image, array $options = [])
    {
        if (! isset($options['card_type']) || ! in_array($options['card_type'], [0, 1], true)) {
            $options['card_type'] = 0;
        }

        return $this->request(self::URL_OCR_IDCARD, $image, $options);
    }

    /**
     * 名片OCR识别
     *
     * @param  string|\SplFileInfo|array $images
     * @param  array $options
     *
     *         参数        是否可选     类型        描述
     *
     * @see https://ai.qq.com/doc/ocrbcocr.shtml
     *
     * @return array
     */
    public function namecard($image, array $options = [])
    {
        return $this->request(self::URL_OCR_NAMECARD, $image, $options);
    }

    /**
     * 行驶证驾驶证OCR识别
     *
     * @param  string|\SplFileInfo|array $images
     * @param  array $options
     *
     *         参数        是否可选     类型        描述
     *         type       Y            int         识别类型，0-行驶证识别，1-驾驶证识别
     *
     * @see https://ai.qq.com/doc/ocrdriverlicenseocr.shtml
     *
     * @return array
     */
    public function driverlicen($image, array $options = [])
    {
        if (! isset($options['type']) || ! in_array($options['type'], [0, 1], true)) {
            $options['type'] = 0;
        }

        return $this->request(self::URL_OCR_DRIVERLICEN, $image, $options);
    }

    /**
     * 银行卡OCR识别
     *
     * @param  string|\SplFileInfo|array $images
     * @param  array $options
     *
     *         参数        是否可选     类型        描述
     *
     * @see https://ai.qq.com/doc/ocrcreditcardocr.shtml
     *
     * @return array
     */
    public function bankcard($image, array $options = [])
    {
        return $this->request(self::URL_OCR_BANKCARD, $image, $options);
    }

    /**
     * 通用OCR识别
     *
     * @param  string|\SplFileInfo|array $images
     * @param  array $options
     *
     *         参数        是否可选     类型        描述
     *
     * @see https://ai.qq.com/doc/ocrgeneralocr.shtml
     *
     * @return array
     */
    public function general($image, array $options = [])
    {
        return $this->request(self::URL_OCR_GENERAL, $image, $options);
    }

    /**
     * OCR营业执照识别
     *
     * @param string|array|\SplFileInfo $image OCR图片路径
     *
     * @see https://ai.qq.com/doc/ocrbizlicenseocr.shtml
     *
     * @return array
     * @throws \Exception
     */
    public function bizlicense($image, array $options = [])
    {
        return $this->request(self::URL_OCR_BIZLICENSEOCR, $image, $options);
    }

    /**
     * 发起HTTP请求，并返回JSON
     *
     * @param $method
     * @param $url
     * @param $params
     *
     * @throws \Exception
     *
     * @return array
     */
    protected function request($url, $image, array $options = [])
    {
        try {
            $image  = is_array($image) ? $image[0] : $image;
            $params = array_merge($options, ['image' => FileConverter::toBase64Encode($image)]);
            $params = $this->authorization->appendSignature($params);

            $http = new HTTP();
            $response = $http
                ->setHeaders(['Content-Type' => 'application/x-www-form-urlencoded'])
                ->request('POST', $url, [
                    'form_params' => $params
                ]);
        } catch (ClientException $ce) {
            $response = $response->getBody();
        }

        return $http->parseJson($response);
    }
}
