<?php

namespace Godruoyi\OCR\Support;

class BaiduAccessToken
{
    const URL = 'https://aip.baidubce.com/oauth/2.0/token';

    protected $accessKeyId;
    protected $secretAccessKey;

    public function __construct($accessKeyId, $secretAccessKey)
    {
        $this->accessKeyId = $accessKeyId;
        $this->secretAccessKey = $secretAccessKey;
    }

    private function getAuthFilePath()
    {
        return dirname(__FILE__) . DIRECTORY_SEPARATOR . md5($this->accessKeyId);
    }

    /**
     * 写入本地文件
     * @param  array $obj
     * @return void
     */
    private function writeAuthObj(array $obj){
        $obj['time'] = time();
        @file_put_contents($this->getAuthFilePath(), json_encode($obj));
    }

    private function readAuthObj(){
        $content = @file_get_contents($this->getAuthFilePath());
        if($content !== false){
            $obj = json_decode($content, true);
            if($obj['time'] + $obj['expires_in'] - 30 > time()){
                return $obj;
            }
        }

        return null;
    }

    public function getAccessToken()
    {
        $obj = $this->readAuthObj();
        if(!empty($obj)){
            return $obj;
        }
        $query = http_build_query([
            'grant_type' => 'client_credentials',
            'client_id' => $this->accessKeyId,
            'client_secret' => $this->secretAccessKey,
        ]);
        $response = Response::createFromGuzzleHttpResponse((new Http())->getClient()->post(self::URL . '?' . $query));
        $this->writeAuthObj($response->toArray());
        return $response->toArray();
    }
}
