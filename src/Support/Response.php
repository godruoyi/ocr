<?php

/*
 * This file is part of the godruoyi/ocr.
 *
 * (c) Godruoyi <gmail@godruoyi.com>
 *
 * This source file is subject to the MIT license that is bundled.
 */

namespace Godruoyi\OCR\Support;

use GuzzleHttp\Psr7\Response as GuzzleHttpResponse;

class Response extends GuzzleHttpResponse implements \ArrayAccess
{
    /**
     * Create response from psr response.
     *
     * @return self
     */
    public static function createFromGuzzleHttpResponse(GuzzleHttpResponse $response)
    {
        $response = new static(
            $response->getStatusCode(),
            $response->getHeaders(),
            $response->getBody(),
            $response->getProtocolVersion(),
            $response->getReasonPhrase()
        );
        
        $response->getBody()->rewind();

        return $response;
    }

    /**
     * Response to array.
     */
    public function toArray(): array
    {
        $this->getBody()->rewind();
        $body = (string)$this->getBody();

        if (empty($body)) {
            return [];
        }

        $response = json_decode($body, true);

        if (JSON_ERROR_NONE != json_last_error()) {
            return [];
        }

        return $response;
    }

    /**
     * Response to json string.
     *
     * @return string
     */
    public function toJson()
    {
        $this->getBody()->rewind();

        return (string)$this->getBody();
    }

    /**
     * @param int $offset
     */
    public function offsetExists($offset): bool
    {
        $item = $this->toArray();

        return isset($item[$offset]);
    }

    /**
     * @param mixed $offset
     *
     * @return mixed
     */
    public function offsetGet($offset)
    {
        $item = $this->toArray();

        if (isset($item[$offset])) {
            return $item[$offset];
        }

        return null;
    }

    /**
     * @param mixed $offset
     * @param mixed $value
     *
     * @return mixed
     */
    public function offsetSet($offset, $value): void
    {
    }

    /**
     * @param mixed $offset
     *
     * @return mixed
     */
    public function offsetUnset($offset): void
    {
    }
}
