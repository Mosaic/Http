<?php

namespace Mosaic\Http\Adapters\Psr7;

use Psr\Http\Message\MessageInterface;
use Psr\Http\Message\StreamInterface;

class Message implements MessageInterface
{
    /**
     * {@inheritdoc}
     */
    public function getProtocolVersion()
    {
        return $this->wrapped->getProtocolVersion();
    }

    /**
     * {@inheritdoc}
     */
    public function withProtocolVersion($version)
    {
        return new static($this->wrapped->withProtocolVersion($version));
    }

    /**
     * {@inheritdoc}
     */
    public function getHeaders()
    {
        return $this->wrapped->getHeaders();
    }

    /**
     * {@inheritdoc}
     */
    public function hasHeader($name)
    {
        return $this->wrapped->hasHeader($name);
    }

    /**
     * {@inheritdoc}
     */
    public function getHeader($name)
    {
        return $this->wrapped->getHeader($name);
    }

    /**
     * {@inheritdoc}
     */
    public function getHeaderLine($name)
    {
        return $this->wrapped->getHeaderLine($name);
    }

    /**
     * {@inheritdoc}
     */
    public function withHeader($name, $value)
    {
        return new static($this->wrapped->withHeader($name, $value));
    }

    /**
     * {@inheritdoc}
     */
    public function withAddedHeader($name, $value)
    {
        return new static($this->wrapped->withAddedHeader($name, $value));
    }

    /**
     * {@inheritdoc}
     */
    public function withoutHeader($name)
    {
        return new static($this->wrapped->withoutHeader($name));
    }

    /**
     * {@inheritdoc}
     */
    public function getBody()
    {
        return $this->wrapped->getBody();
    }

    /**
     * {@inheritdoc}
     */
    public function withBody(StreamInterface $body)
    {
        return new static($this->wrapped->withBody($body));
    }
}
