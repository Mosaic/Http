<?php

namespace Mosaic\Http;

use Mosaic\Common\Arrayable;
use Psr\Http\Message\ResponseInterface;

interface ResponseFactory
{
    /**
     * @param string $content
     * @param int    $status
     * @param array  $headers
     *
     * @return ResponseInterface
     */
    public function html(string $content = null, int $status = 200, array $headers = []);

    /**
     * @param mixed $content
     *
     * @param int   $status
     * @param array $headers
     *
     * @return ResponseInterface
     */
    public function make($content = '', int $status = 200, array $headers = []);

    /**
     * @param array|Arrayable $content
     * @param int             $status
     * @param array           $headers
     * @param int             $option
     *
     * @return ResponseInterface
     */
    public function json($content = [], int $status = 200, array $headers = [], int $option = 79);
}
