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
use ReturnTypeWillChange;

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
        $body = (string) $this->getBody();

        if (empty($body)) {
            return [];
        }

        $response = json_decode($body, true);

        if (json_last_error() != JSON_ERROR_NONE) {
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

        return (string) $this->getBody();
    }

    /**
     * @param  int  $offset
     */
    public function offsetExists($offset): bool
    {
        $item = $this->toArray();

        return isset($item[$offset]);
    }

    #[ReturnTypeWillChange]
    public function offsetGet(mixed $offset): mixed
    {
        $item = $this->toArray();

        if (isset($item[$offset])) {
            return $item[$offset];
        }

        return null;
    }

    public function offsetSet(mixed $offset, mixed $value): void {}

    public function offsetUnset(mixed $offset): void {}
}
