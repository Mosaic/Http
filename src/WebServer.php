<?php

namespace Mosaic\Http;

use InvalidArgumentException;
use Mosaic\Http\Emitters\SapiEmitter;
use Mosaic\Http\Server as ServerContract;
use Psr\Http\Message\ResponseInterface;
use Relay\RelayBuilder;

class WebServer implements ServerContract
{
    /**
     * @var array
     */
    protected $pipes = [];

    /**
     * @var Emitter
     */
    protected $emitter;

    /**
     * @var callable
     */
    protected $resolver;

    /**
     * @param Emitter $emitter
     */
    public function __construct(Emitter $emitter = null)
    {
        $this->emitter = $emitter ?: new SapiEmitter;

        $this->setResolver(function ($class) {
            return new $class;
        });
    }

    /**
     * @param Request       $request
     * @param callable|null $terminate
     */
    public function serve(Request $request, callable $terminate = null)
    {
        if (count($this->pipes) < 1) {
            throw new InvalidArgumentException('No pipes were given to the web server. Response could not be served.');
        }

        ob_start();
        $bufferLevel = ob_get_level();

        $response = $this->relay($request);

        if (is_callable($terminate)) {
            $terminate($request->toPsr7(), $response);
        }

        $this->getEmitter()->emit($response, $bufferLevel);
    }

    /**
     * @param  Request           $request
     * @return ResponseInterface
     */
    protected function relay(Request $request)
    {
        $relay = (new RelayBuilder($this->resolver))->newInstance($this->pipes);

        $response = $relay(
            $request->toPsr7(),
            $request->prepareResponse()->toPsr7()
        );

        return $response;
    }

    /**
     * @param array ...$pipes
     * @return Server
     */
    public function pipe(...$pipes)
    {
        $this->pipes = $pipes;

        return $this;
    }

    /**
     * @return Emitter
     */
    protected function getEmitter() : Emitter
    {
        return $this->emitter;
    }

    /**
     * @param  callable $resolver
     * @return Server
     */
    public function setResolver(callable $resolver)
    {
        $this->resolver = $resolver;

        return $this;
    }
}
