<?php

namespace Mosaic\Http\Adapters\Psr7;

use Psr\Http\Message\MessageInterface;
use Psr\Http\Message\StreamInterface;

abstract class Message implements MessageInterface
{
    /**
     * {@inheritdoc}
     */
    public function getProtocolVersion()
    {
        return $this->getWrapped()->getProtocolVersion();
    }

    /**
     * {@inheritdoc}
     */
    public function withProtocolVersion($version)
    {
        return new static($this->getWrapped()->withProtocolVersion($version));
    }

    /**
     * {@inheritdoc}
     */
    public function getHeaders()
    {
        return $this->getWrapped()->getHeaders();
    }

    /**
     * {@inheritdoc}
     */
    public function hasHeader($name)
    {
        return $this->getWrapped()->hasHeader($name);
    }

    /**
     * {@inheritdoc}
     */
    public function getHeader($name)
    {
        return $this->getWrapped()->getHeader($name);
    }

    /**
     * {@inheritdoc}
     */
    public function getHeaderLine($name)
    {
        return $this->getWrapped()->getHeaderLine($name);
    }

    /**
     * {@inheritdoc}
     */
    public function withHeader($name, $value)
    {
        return new static($this->getWrapped()->withHeader($name, $value));
    }

    /**
     * {@inheritdoc}
     */
    public function withAddedHeader($name, $value)
    {
        return new static($this->getWrapped()->withAddedHeader($name, $value));
    }

    /**
     * {@inheritdoc}
     */
    public function withoutHeader($name)
    {
        return new static($this->getWrapped()->withoutHeader($name));
    }

    /**
     * {@inheritdoc}
     */
    public function getBody()
    {
        return $this->getWrapped()->getBody();
    }

    /**
     * {@inheritdoc}
     */
    public function withBody(StreamInterface $body)
    {
        return new static($this->getWrapped()->withBody($body));
    }

    /**
     * @return mixed
     */
    abstract protected function getWrapped();
}
