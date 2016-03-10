<?php

namespace Mosaic\Http\Tests;

use InvalidArgumentException;
use Mockery\Mock;
use Mosaic\Http\Emitter;
use Mosaic\Http\Middleware\DispatchRequest;
use Mosaic\Http\Request;
use Mosaic\Http\Response;
use Mosaic\Http\Server;
use Mosaic\Http\WebServer;
use PHPUnit_Framework_TestCase;

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

    protected function setUp()
    {
        $this->request = \Mockery::mock(Request::class);
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
            $pipe = \Mockery::mock(DispatchRequest::class)
        );

        $pipe->shouldReceive('__invoke')->once()->andReturn($response = \Mockery::mock(Response::class));

        $this->emitter->shouldReceive('emit')->with($response, 1)->once();

        $this->server->serve($this->request);
    }

    public function test_can_add_a_terminate_call_to_the_end_of_the_serve()
    {
        StdMock::$sapi->shouldReceive('ob_start')->once();

        $this->server->pipe(
            $pipe = \Mockery::mock(DispatchRequest::class)
        );

        $pipe->shouldReceive('__invoke')->once()->andReturn($response = \Mockery::mock(Response::class));

        $this->emitter->shouldReceive('emit')->with($response, 1)->once();

        $this->server->serve($this->request, function ($request, $response) {
            $this->assertInstanceOf(Request::class, $request);
            $this->assertInstanceOf(Response::class, $response);
        });
    }

    public function test_can_serve_the_request_with_multiple_pipes()
    {
        StdMock::$sapi->shouldReceive('ob_start')->once();

        $this->server->pipe(
            new PipeStub(),
            $pipe = \Mockery::mock(DispatchRequest::class)
        );

        $pipe->shouldReceive('__invoke')->once()->andReturn($response = \Mockery::mock(Response::class));

        // Make sure both pipes are called
        $response->shouldReceive('withStatus')->with(200)->andReturn($response);

        $this->emitter->shouldReceive('emit')->with($response, 1)->once();

        $this->server->serve($this->request);
    }
}

class PipeStub
{
    public function __invoke($request, callable $next)
    {
        $response = $next($request);

        return $response->withStatus(200);
    }
}
