<?php

namespace Mosaic\Http;

use InvalidArgumentException;
use Mosaic\Http\Emitters\SapiEmitter;
use Mosaic\Http\Server as ServerContract;

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
     * @param Emitter $emitter
     */
    public function __construct(Emitter $emitter = null)
    {
        $this->emitter = $emitter ?: new SapiEmitter;
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

        $response = array_reduce(array_reverse($this->pipes), function ($next, $pipe) use ($request) {
            return $pipe($request, function () use ($next) {
                return $next;
            });
        });

        if (is_callable($terminate)) {
            $terminate($request, $response);
        }

        $this->getEmitter()->emit($response, $bufferLevel);
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
}
