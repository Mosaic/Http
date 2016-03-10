<?php

namespace Mosaic\Http\Middleware;

use Mosaic\Http\Adapters\Psr7\Response;
use Mosaic\Http\Request;
use Mosaic\Http\ResponseFactory;
use Mosaic\Routing\RouteDispatcher;

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
     * DispatchRequest constructor.
     *
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
     * Dispatch the request
     *
     * @param Request $request
     *
     * @return Response
     */
    public function __invoke(Request $request)
    {
        return $this->factory->make(
            $this->dispatcher->dispatch($request->toPsr7())
        );
    }
}
