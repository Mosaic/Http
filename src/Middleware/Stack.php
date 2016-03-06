<?php

namespace Mosaic\Http\Middleware;

use Mosaic\Container\Container;
use Mosaic\Http\Request;
use Mosaic\Http\Response;

class Stack
{
    /**
     * @var Request
     */
    private $request;

    /**
     * @var Container
     */
    private $container;

    /**
     * Stack constructor.
     *
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * @param Request $request
     *
     * @return Stack
     */
    public function run(Request $request) : Stack
    {
        $this->request = $request;

        return $this;
    }

    /**
     * @param array $middleware
     *
     * @return Response
     */
    public function through(array $middleware) : Response
    {
        $stack = array_reverse($middleware);

        return array_reduce($stack, $this->send());
    }

    /**
     * @return callable
     */
    protected function send() : callable
    {
        return function ($next, $pipe) {
            $run = $this->container->make($pipe);

            return $run($this->request, function () use ($next) {
                return $next;
            });
        };
    }
}
