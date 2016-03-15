<?php

namespace Mosaic\Http;

use Psr\Http\Message\ResponseInterface;

interface Emitter
{
    /**
     * @param ResponseInterface $response
     * @param null              $maxBufferLevel
     */
    public function emit(ResponseInterface $response, $maxBufferLevel = null);
}
