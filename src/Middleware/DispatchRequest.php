<?php

namespace Mosaic\Http\Middleware;

use Mosaic\Http\Adapters\Psr7\Response;
use Mosaic\Http\ResponseFactory;
use Mosaic\Routing\RouteDispatcher;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class DispatchRequest
{
    /**
     * @var ResponseFactory
     */
    private $factory;

    /**
     * @var RouteDispatcher
     */
    private $dispatcher;

    /**
     * @param RouteDispatcher $dispatcher
     * @param ResponseFactory $factory
     */
    public function __construct(
        RouteDispatcher $dispatcher,
        ResponseFactory $factory
    ) {
        $this->factory    = $factory;
        $this->dispatcher = $dispatcher;
    }

    /**
     * @param  RequestInterface  $request
     * @param  ResponseInterface $response
     * @param  callable          $next
     * @return Response
     */
    public function __invoke(RequestInterface $request, ResponseInterface $response, callable $next = null)
    {
        return $this->factory->make(
            $this->dispatcher->dispatch($request)
        );
    }
}
