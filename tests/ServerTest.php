<?php

namespace Mosaic\Http\Tests;

use Mosaic\Container\Container;
use Mosaic\Contracts\Application;
use Mosaic\Exceptions\ExceptionRunner;
use Mosaic\Http\Adapters\Psr7\Response;
use Mosaic\Http\Emitter;
use Mosaic\Http\Middleware\DispatchRequest;
use Mosaic\Http\Request;
use Mosaic\Http\Server;
use Mosaic\Http\WebServer;
use PHPUnit_Framework_TestCase;
use Zend\Diactoros\Response as ZendResponse;

class ServerTest extends PHPUnit_Framework_TestCase
{
    use MocksTheStandardLibrary;

    /**
     * @var \Mockery\MockInterface|Application
     */
    private $app;

    /**
     * @var Server
     */
    private $server;

    /**
     * @var \Mockery\MockInterface|Emitter
     */
    private $emitter;

    protected function setUp()
    {
        $this->app     = \Mockery::mock(Application::class);
        $this->emitter = \Mockery::mock(Emitter::class);

        $this->server  = new WebServer($this->app, $this->emitter);
    }

    public function tearDown()
    {
        \Mockery::close();
    }

    public function test_it_has_a_name()
    {
        // @todo change when servers can have custom names?
        $this->assertEquals('web', $this->server->getName());
    }

    public function test_it_listens()
    {
        $this->setUpDependencies();

        $this->server->listen();
    }

    public function test_it_has_a_default_emitter()
    {
        $this->assertNotNull(new WebServer($this->app));
    }

    public function test_it_can_call_a_custom_terminate_callable()
    {
        $callMe = \Mockery::mock(['invoke' => true]);
        $callMe->shouldReceive('invoke')->once();

        $this->setUpDependencies();

        $this->server->listen([$callMe, 'invoke']);
    }

    public function test_it_may_fail_and_delegate_to_the_exception_runner()
    {
        $this->setUpDependencies(true);

        $runner = \Mockery::mock(ExceptionRunner::class);
        $runner->shouldReceive('handleException')->once()->with(\Mockery::type(\InvalidArgumentException::class));
        $this->app->shouldReceive('getExceptionRunner')->once()->andReturn($runner);

        $this->server->listen();
    }

    private function setUpDependencies(bool $fail = false)
    {
        $dispatcher = \Mockery::mock(DispatchRequest::class);
        $dispatcher->shouldReceive('__invoke')->once()->andReturn(
            $response = new Response(new ZendResponse())
        );

        $container = \Mockery::mock(Container::class);
        $container->shouldReceive('make')->with(Request::class)->andReturn(\Mockery::mock(Request::class));
        $container->shouldReceive('make')->with(DispatchRequest::class)->once()->andReturn($dispatcher);

        $this->app->shouldReceive('getContainer')->andReturn($container);
        $this->app->shouldReceive('setExceptionRunner')->once()->with(\Mockery::type(ExceptionRunner::class));
        $this->app->shouldReceive('setContext')->once()->with('web');
        $this->app->shouldReceive('bootstrap')->once();

        if ($fail) {
            $this->emitter->shouldReceive('emit')->andThrow(\InvalidArgumentException::class);
        } else {
            $this->emitter->shouldReceive('emit')->with($response, 1)->once();
        }
    }
}
