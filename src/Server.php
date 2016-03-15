<?php

namespace Mosaic\Http;

interface Server
{
    /**
     * @param array ...$pipes
     * @return Server
     */
    public function pipe(...$pipes);

    /**
     * @param  callable $resolver
     * @return Server
     */
    public function setResolver(callable $resolver);

    /**
     * @param Request       $request
     * @param callable|null $terminate
     */
    public function serve(Request $request, callable $terminate = null);
}
