<?php

namespace Godruoyi\OCR\Exceptions;

use Exception;
use GuzzleHttp\Exception\RequestException as GuzzleRequestException;

class RequestException extends Exception
{
    protected $requestException;

    public function __construct(GuzzleRequestException $e, string $message)
    {
        $this->requestException = $e;

        parent::__construct($message, $e->getErrorCode(), $e);
    }

    public function getException()
    {
        return $this->requestException;
    }

    public function hasResponse()
    {
        return $this->requestException->hasResponse();
    }

    public function getOriginalResponse()
    {
        return $this->requestException->getResponse();
    }
}
