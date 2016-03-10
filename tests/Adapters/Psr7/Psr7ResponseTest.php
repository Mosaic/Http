<?php

namespace Mosaic\Http\Tests\Adapters\Psr7;

use Mosaic\Http\Adapters\Psr7\Response;
use Mosaic\Http\Response as ResponseContract;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\StreamInterface;

class Psr7ResponseTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Response
     */
    private $response;

    /**
     * @var ServerRequestInterface|\Mockery\MockInterface
     */
    private $wrappedMock;

    protected function setUp()
    {
        $this->response = new Response($this->wrappedMock = \Mockery::mock(ResponseInterface::class));
    }

    public function test_it_implements_the_Mosaic_response_interface()
    {
        $this->assertInstanceOf(ResponseContract::class, $this->response);
    }

    public function test_it_is_psr7_compatible()
    {
        $this->assertInstanceOf(ResponseInterface::class, $this->response);
    }

    public function test_can_get_status()
    {
        $this->wrappedMock->shouldReceive('getStatusCode')->once()->andReturn(200);

        $this->assertEquals(200, $this->response->status());
    }

    public function test_can_get_body()
    {
        $this->wrappedMock->shouldReceive('getBody')->once()->andReturn('Body');

        $this->assertEquals('Body', $this->response->body());
    }

    public function test_can_get_body_size()
    {
        $stream = \Mockery::mock(StreamInterface::class);
        $stream->shouldReceive('getSize')->once()->andReturn(1);

        $this->wrappedMock->shouldReceive('getBody')->once()->andReturn($stream);

        $this->assertEquals(1, $this->response->size());
    }

    public function test_immutability_of_adding_headers()
    {
        $this->wrappedMock->shouldReceive('withHeader')->andReturn(\Mockery::mock(ResponseInterface::class));

        $this->assertNotEquals($this->response, $this->response->addHeader('header', 'value'));
    }

    public function test_can_check_if_header_exists()
    {
        $this->wrappedMock->shouldReceive('hasHeader')->once()->andReturn(true);

        $this->assertTrue($this->response->hasHeader('header'));
    }

    public function test_can_reason_phrase()
    {
        $this->wrappedMock->shouldReceive('getReasonPhrase')->once()->andReturn('OK');

        $this->assertEquals('OK', $this->response->reason());
    }

    public function test_can_protocol_version()
    {
        $this->wrappedMock->shouldReceive('getProtocolVersion')->once()->andReturn('version');

        $this->assertEquals('version', $this->response->protocol());
    }

    public function test_can_get_response_headers()
    {
        $this->wrappedMock->shouldReceive('getHeaders')->once()->andReturn([
            'header' => 'value'
        ]);

        $this->assertEquals([
            'header' => 'value'
        ], $this->response->headers());
    }

    public function test_can_get_reason_phrase()
    {
        $this->wrappedMock->shouldReceive('getReasonPhrase')->once()->andReturn('OK');

        $this->assertEquals('OK', $this->response->getReasonPhrase());
    }

    public function test_can_get_status_code()
    {
        $this->wrappedMock->shouldReceive('getStatusCode')->once()->andReturn(200);

        $this->assertEquals(200, $this->response->getStatusCode());
    }

    public function test_immutability_of_changing_status()
    {
        $this->wrappedMock->shouldReceive('withStatus')->with(200, 'OK')->andReturn(\Mockery::mock(ResponseInterface::class));

        $this->assertNotEquals($this->response, $this->response->withStatus(200, 'OK'));
    }

    public function test_can_get_psr7_compatible_response()
    {
        $this->assertInstanceOf(ResponseInterface::class, $this->response->toPsr7());
    }

    public function tearDown()
    {
        \Mockery::close();
    }
}
