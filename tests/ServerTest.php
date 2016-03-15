<?php

namespace Mosaic\Http\Tests;

use InvalidArgumentException;
use Mockery\Mock;
use Mosaic\Http\Emitter;
use Mosaic\Http\Request;
use Mosaic\Http\Response;
use Mosaic\Http\Server;
use Mosaic\Http\WebServer;
use PHPUnit_Framework_TestCase;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class ServerTest extends PHPUnit_Framework_TestCase
{
    use MocksWebServerHelpers;

    /**
     * @var Server
     */
    private $server;

    /**
     * @var Mock
     */
    protected $request;

    /**
     * @var Mock
     */
    private $emitter;

    /**
     * @var Mock
     */
    private $psr7;

    /**
     * @var Mock
     */
    private $psr7Response;

    /**
     * @var Mock
     */
    private $response;

    protected function setUp()
    {
        $this->request      = \Mockery::mock(Request::class);
        $this->psr7         = \Mockery::mock(ServerRequestInterface::class);
        $this->psr7Response = \Mockery::mock(ResponseInterface::class);
        $this->response     = \Mockery::mock(Response::class);

        $this->request->shouldReceive('toPsr7')->andReturn($this->psr7);
        $this->request->shouldReceive('prepareResponse')->andReturn($this->response);
        $this->response->shouldReceive('toPsr7')->andReturn($this->psr7Response);

        $this->emitter = \Mockery::mock(Emitter::class);

        $this->server = new WebServer($this->emitter);
    }

    public function tearDown()
    {
        \Mockery::close();
    }

    public function test_cannot_serve_without_pipes()
    {
        StdMock::$sapi->shouldReceive('ob_start')->never();

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('No pipes were given to the web server. Response could not be served.');

        $this->server->serve($this->request);
    }

    public function test_can_serve_the_request()
    {
        StdMock::$sapi->shouldReceive('ob_start')->once();

        $this->server->pipe(
            PipeStub::class
        );

        $this->psr7Response->shouldReceive('withStatus')
                           ->with(200)->andReturn($this->psr7Response)->once();

        $this->emitter->shouldReceive('emit')->with($this->psr7Response, 1)->once();

        $this->server->serve($this->request);
    }

    public function test_can_add_a_terminate_call_to_the_end_of_the_serve()
    {
        StdMock::$sapi->shouldReceive('ob_start')->once();

        $this->server->pipe(
            PipeStub::class
        );

        $this->psr7Response->shouldReceive('withStatus')
                           ->with(200)->andReturn($this->psr7Response)->once();

        $this->emitter->shouldReceive('emit')->with($this->psr7Response, 1)->once();

        $this->server->serve($this->request, function ($request, $response) {
            $this->assertInstanceOf(RequestInterface::class, $request);
            $this->assertInstanceOf(ResponseInterface::class, $response);
        });
    }

    public function test_can_serve_the_request_with_multiple_pipes()
    {
        StdMock::$sapi->shouldReceive('ob_start')->once();

        $this->server->pipe(
            ExtraPipeStub::class,
            PipeStub::class
        );

        $this->psr7Response->shouldReceive('withStatus')
                           ->with(200)->andReturn($this->psr7Response)->once();

        $this->psr7Response->shouldReceive('withStatus')
                           ->with(304)->andReturn($this->psr7Response)->once();

        $this->emitter->shouldReceive('emit')->with($this->psr7Response, 1)->once();

        $this->server->serve($this->request);
    }
}

class PipeStub
{
    public function __invoke($request, $response, callable $next)
    {
        $response = $next($request, $response);

        return $response->withStatus(200);
    }
}

class ExtraPipeStub
{
    public function __invoke($request, $response, callable $next)
    {
        $response = $next($request, $response);

        return $response->withStatus(304);
    }
}
