<?php

namespace Mosaic\Http\Adapters\Psr7;

use Mosaic\Common\Arr;
use Mosaic\Http\Request as RequestContract;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\UriInterface;

class Request extends Message implements RequestContract, ServerRequestInterface
{
    /**
     * @var ServerRequestInterface
     */
    protected $wrapped;

    /**
     * Psr7Request constructor.
     *
     * @param ServerRequestInterface $wrapper
     */
    public function __construct(ServerRequestInterface $wrapper)
    {
        $this->wrapped = $wrapper;
    }

    /**
     * {@inheritdoc}
     */
    public function header(string $key = null, $default = null)
    {
        return Arr::unwrap($this->wrapped->getHeader($key), $default);
    }

    /**
     * {@inheritdoc}
     */
    public function method() : string
    {
        return $this->wrapped->getMethod();
    }

    /**
     * {@inheritdoc}
     */
    public function get(string $key, $default = null)
    {
        return Arr::get($this->all(), $key, $default);
    }

    /**
     * {@inheritdoc}
     */
    public function all()
    {
        if ($this->isMethod('GET')) {
            return $this->getQueryParams();
        }

        return array_merge($this->getParsedBody(), $this->getQueryParams());
    }

    /**
     * {@inheritdoc}
     */
    public function only($keys)
    {
        $params = [];

        foreach ((array) $keys as $key) {
            $params[$key] = $this->get($key);
        }

        return $params;
    }

    /**
     * {@inheritdoc}
     */
    public function except($keys)
    {
        $allKeys = array_keys($this->all());

        return $this->only(array_diff($allKeys, (array) $keys));
    }

    /**
     * {@inheritdoc}
     */
    public function exists($key)
    {
        return array_reduce((array) $key, function ($carry, $item) {
            return $carry && array_key_exists($item, $this->all());
        }, true);
    }

    /**
     * {@inheritdoc}
     */
    public function has($key)
    {
        return array_reduce((array) $key, function ($carry, $item) {
            return $carry && !$this->isEmptyString($item);
        }, true);
    }

    /**
     * {@inheritdoc}
     */
    public function server(string $key = null, $default = null)
    {
        if ($key === null) {
            return $this->wrapped->getServerParams();
        }

        return Arr::get($this->wrapped->getServerParams(), $key, $default);
    }

    /**
     * {@inheritdoc}
     */
    public function segments()
    {
        $segments = explode('/', trim($this->getUri()->getPath(), '/'));

        return array_values(array_filter($segments, function ($segment) {
            return trim($segment) !== '';
        }));
    }

    /**
     * {@inheritdoc}
     */
    public function segment($index, $default = null)
    {
        return Arr::get($this->segments(), $index, $default);
    }

    /**
     * {@inheritdoc}
     */
    public function file(string $key = null, $default = null)
    {
        return Arr::get($this->getUploadedFiles(), $key, $default);
    }

    /**
     * {@inheritdoc}
     */
    public function hasFile(string $key)
    {
        return array_key_exists($key, $this->wrapped->getUploadedFiles());
    }

    /**
     * {@inheritdoc}
     */
    public function cookies()
    {
        return $this->wrapped->getCookieParams();
    }

    /**
     * {@inheritdoc}
     */
    public function cookie(string $key = null, $default = null)
    {
        return Arr::get($this->getCookieParams(), $key, $default);
    }

    /**
     * {@inheritdoc}
     */
    public function getRequestTarget()
    {
        return $this->wrapped->getRequestTarget();
    }

    /**
     * {@inheritdoc}
     */
    public function withRequestTarget($requestTarget)
    {
        return new static($this->wrapped->withRequestTarget($requestTarget));
    }

    /**
     * {@inheritdoc}
     */
    public function getMethod()
    {
        return $this->wrapped->getMethod();
    }

    /**
     * {@inheritdoc}
     */
    public function withMethod($method)
    {
        return new static($this->wrapped->withMethod($method));
    }

    /**
     * {@inheritdoc}
     */
    public function getUri()
    {
        return $this->wrapped->getUri();
    }

    /**
     * {@inheritdoc}
     */
    public function withUri(UriInterface $uri, $preserveHost = false)
    {
        return new static($this->wrapped->withUri($uri, $preserveHost));
    }

    /**
     * {@inheritdoc}
     */
    public function getServerParams()
    {
        return $this->wrapped->getServerParams();
    }

    /**
     * {@inheritdoc}
     */
    public function getCookieParams()
    {
        return $this->wrapped->getCookieParams();
    }

    /**
     * {@inheritdoc}
     */
    public function withCookieParams(array $cookies)
    {
        return new static($this->wrapped->withCookieParams($cookies));
    }

    /**
     * {@inheritdoc}
     */
    public function getQueryParams()
    {
        return $this->wrapped->getQueryParams();
    }

    /**
     * {@inheritdoc}
     */
    public function withQueryParams(array $query)
    {
        return new static($this->wrapped->withQueryParams($query));
    }

    /**
     * {@inheritdoc}
     */
    public function getUploadedFiles()
    {
        return $this->wrapped->getUploadedFiles();
    }

    /**
     * {@inheritdoc}
     */
    public function withUploadedFiles(array $uploadedFiles)
    {
        return new static($this->wrapped->withUploadedFiles($uploadedFiles));
    }

    /**
     * {@inheritdoc}
     */
    public function getParsedBody()
    {
        return $this->wrapped->getParsedBody();
    }

    /**
     * {@inheritdoc}
     */
    public function withParsedBody($data)
    {
        return new static($this->wrapped->withParsedBody($data));
    }

    /**
     * {@inheritdoc}
     */
    public function getAttributes()
    {
        return $this->wrapped->getAttributes();
    }

    /**
     * {@inheritdoc}
     */
    public function getAttribute($name, $default = null)
    {
        return $this->wrapped->getAttribute($name, $default);
    }

    /**
     * {@inheritdoc}
     */
    public function withAttribute($name, $value)
    {
        return new static($this->wrapped->withAttribute($name, $value));
    }

    /**
     * {@inheritdoc}
     */
    public function withoutAttribute($name)
    {
        return new static($this->wrapped->withoutAttribute($name));
    }

    /**
     * @param string $method
     *
     * @return bool
     */
    private function isMethod(string $method)
    {
        return strcasecmp($method, $this->method()) === 0;
    }

    /**
     * @param string $key
     *
     * @return bool
     */
    private function isEmptyString(string $key)
    {
        $item = $this->get($key);

        if (is_array($item)) {
            return empty($item);
        }

        return trim($item) === '';
    }

    /**
     * @return string
     */
    public function uri() : string
    {
        return (string) $this->wrapped->getUri();
    }

    /**
     * @return string
     */
    public function path() : string
    {
        return (string) $this->wrapped->getUri()->getPath();
    }

    /**
     * @return ServerRequestInterface
     */
    public function toPsr7() : ServerRequestInterface
    {
        return $this;
    }

    /**
     * @return ServerRequestInterface
     */
    protected function getWrapped()
    {
        return $this->wrapped;
    }
}
