<?php

namespace Mosaic\Http\Tests\Middleware;

use Mockery\Mock;
use Mosaic\Http\Middleware\DispatchRequest;
use Mosaic\Http\Request;
use Mosaic\Http\Response;
use Mosaic\Http\ResponseFactory;
use Mosaic\Routing\RouteDispatcher;
use PHPUnit_Framework_TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class DispatchRequestTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var DispatchRequest
     */
    private $middleware;

    /**
     * @var Mock
     */
    private $response;

    /**
     * @var Mock
     */
    private $dispatcher;

    /**
     * @var Mock
     */
    private $factory;

    /**
     * @var Mock
     */
    private $request;

    public function setUp()
    {
        $this->dispatcher = \Mockery::mock(RouteDispatcher::class);
        $this->factory    = \Mockery::mock(ResponseFactory::class);
        $this->request    = \Mockery::mock(ServerRequestInterface::class);
        $this->response   = \Mockery::mock(ResponseInterface::class);

        $this->middleware = new DispatchRequest(
            $this->dispatcher,
            $this->factory
        );
    }

    public function test_can_dispatch_request()
    {
        $middleware = $this->middleware;

        $this->dispatcher->shouldReceive('dispatch')->with($this->request)->andReturn('response');
        $this->factory->shouldReceive('make')->with('response')->andReturn($this->response);

        $this->assertEquals($this->response, $middleware($this->request, $this->response));
    }

    public function tearDown()
    {
        \Mockery::close();
    }
}
