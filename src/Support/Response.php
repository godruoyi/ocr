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

class Response extends GuzzleHttpResponse
{
    /**
     * Create response from psr response.
     *
     * @param GuzzleHttpResponse $response
     *
     * @return self
     */
    public static function createFromGuzzleHttpResponse(GuzzleHttpResponse $response)
    {
        return new static(
            $response->getStatusCode(),
            $response->getHeaders(),
            $response->getBody(),
            $response->getProtocolVersion(),
            $response->getReasonPhrase()
        );
    }

    /**
     * Response to array.
     *
     * @return array
     */
    public function toArray(): array
    {
        $this->getBody()->rewind();
        $body = (string) $this->getBody();

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

        return (string) $this->getBody();
    }
}
