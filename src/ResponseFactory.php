<?php

namespace Mosaic\Http;

use Mosaic\Common\Arrayable;

interface ResponseFactory
{
    /**
     * @param string $content
     * @param int    $status
     * @param array  $headers
     *
     * @return Response
     */
    public function html(string $content = null, int $status = 200, array $headers = []);

    /**
     * @param mixed $content
     *
     * @param int   $status
     * @param array $headers
     *
     * @return Response
     */
    public function make($content = '', int $status = 200, array $headers = []);

    /**
     * @param array|Arrayable $content
     * @param int             $status
     * @param array           $headers
     * @param int             $option
     *
     * @return Response
     */
    public function json($content = [], int $status = 200, array $headers = [], int $option = 79);
}
