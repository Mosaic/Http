<?php

namespace Mosaic\Http;

use Mosaic\Container\Container;
use Mosaic\Http\Emitters\SapiEmitter;
use Mosaic\Http\Middleware\DispatchRequest;
use Mosaic\Http\Middleware\Stack;
use Mosaic\Http\Server as ServerContract;

class WebServer implements ServerContract
{
    /**
     * @var array
     */
    protected $middleware = [
        DispatchRequest::class,
    ];

    /**
     * @var Emitter
     */
    protected $emitter;

    /**
     * @var Request
     */
    protected $request;

    /**
     * @var Container
     */
    protected $container;

    /**
     * @param Request   $request
     * @param Container $container
     * @param Emitter   $emitter
     */
    public function __construct(Request $request, Container $container, Emitter $emitter = null)
    {
        $this->emitter   = $emitter ?: new SapiEmitter;
        $this->request   = $request;
        $this->container = $container;
    }

    /**
     * @return string
     */
    public function getName() : string
    {
        return 'web';
    }

    /**
     * Listen to a server request
     *
     * @param callable $terminate
     */
    public function listen(callable $terminate = null)
    {
        $this->handle($terminate);
    }

    /**
     * @param callable $terminate
     */
    protected function handle(callable $terminate = null)
    {
        $request = $this->request;

        ob_start();
        $bufferLevel = ob_get_level();

        // Run the request through the stack of middleware
        $response = (new Stack($this->container))->run($request)->through(
            $this->middleware()
        );

        // Call the terminate closure when given
        if (is_callable($terminate)) {
            $terminate($request, $response);
        }

        $this->getEmitter()->emit($response, $bufferLevel);
    }

    /**
     * @return array
     */
    protected function middleware() : array
    {
        return $this->middleware;
    }

    /**
     * @return Emitter
     */
    protected function getEmitter() : Emitter
    {
        return $this->emitter;
    }
}
