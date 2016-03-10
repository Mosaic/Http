<?php

namespace Mosaic\Http\Adapters\Psr7;

use Mosaic\Http\Response as ResponseContract;
use Psr\Http\Message\ResponseInterface;

class Response extends Message implements ResponseContract, ResponseInterface
{
    /**
     * @var ResponseInterface
     */
    protected $wrapped;

    /**
     * @param ResponseInterface $wrapper
     */
    public function __construct(ResponseInterface $wrapper)
    {
        parent::__construct($wrapper);
    }

    /**
     * Gets the response status code.
     * The status code is a 3-digit integer result code of the server's attempt
     * to understand and satisfy the request.
     *
     * @return int Status code.
     */
    public function status() : int
    {
        return $this->wrapped->getStatusCode();
    }

    /**
     * Gets the body of the message.
     *
     * @return string Returns the body as string.
     */
    public function body() : string
    {
        return (string) $this->wrapped->getBody();
    }

    /**
     * @return int|null
     */
    public function size()
    {
        return $this->wrapped->getBody()->getSize();
    }

    /**
     * @param string $header
     * @param string $value
     *
     * @return ResponseContract
     */
    public function addHeader(string $header, string $value) : ResponseContract
    {
        return new static($this->wrapped->withHeader($header, $value));
    }

    /**
     * @return string
     */
    public function reason() : string
    {
        return $this->wrapped->getReasonPhrase();
    }

    /**
     * @return string
     */
    public function protocol() : string
    {
        return $this->wrapped->getProtocolVersion();
    }

    /**
     * @return array
     */
    public function headers() : array
    {
        return $this->wrapped->getHeaders();
    }

    /**
     * {@inheritdoc}
     */
    public function getStatusCode()
    {
        return $this->wrapped->getStatusCode();
    }

    /**
     * {@inheritdoc}
     */
    public function withStatus($code, $reasonPhrase = '')
    {
        return new static($this->wrapped->withStatus($code, $reasonPhrase));
    }

    /**
     * {@inheritdoc}
     */
    public function getReasonPhrase()
    {
        return $this->wrapped->getReasonPhrase();
    }

    /**
     * @return ResponseInterface
     */
    public function toPsr7() : ResponseInterface
    {
        return $this;
    }

    /**
     * @return ResponseInterface
     */
    protected function getWrapped()
    {
        return $this->wrapped;
    }
}
