<?php

namespace Mosaic\Http\Tests;

use Mosaic\Http\Emitter;
use Mosaic\Http\Server;
use Mosaic\Http\WebServer;
use PHPUnit_Framework_TestCase;

class ServerTest extends PHPUnit_Framework_TestCase
{
    use MocksTheStandardLibrary;

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
        $this->emitter = \Mockery::mock(Emitter::class);

        $this->server  = new WebServer($this->emitter);
    }

    public function tearDown()
    {
        \Mockery::close();
    }
}
