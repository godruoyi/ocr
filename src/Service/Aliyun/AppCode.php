<?php

namespace Godruoyi\OCR\Service\Aliyun;

class AppCode
{
    /**
     * Aliyun App Code
     *
     * @var string
     */
    protected $appCode;

    /**
     * Register App Code
     *
     * @param string $appCode
     */
    public function __construct($appCode)
    {
        $this->appCode = $appCode;
    }

    /**
     * Get App code
     *
     * @return string
     */
    public function getAppCode()
    {
        return $this->appCode;
    }

    /**
     * Get App Code with Header
     *
     * @return array
     */
    public function getAppCodeHeader()
    {
        return [
            'Authorization' => 'APPCODE ' . $this->getAppCode()
        ];
    }
}
