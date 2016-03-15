<?php

namespace Mosaic\Http;

use Psr\Http\Message\ServerRequestInterface;

interface Request
{
    /**
     * @return ServerRequestInterface
     */
    public function toPsr7() : ServerRequestInterface;

    /**
     * @return Response
     */
    public function prepareResponse() : Response;

    /**
     * Retrieve a header from the request.
     *
     * @param string $key
     * @param mixed  $default
     *
     * @return string|array
     */
    public function header(string $key = null, $default = null);

    /**
     * Get the request method.
     *
     * @return string
     */
    public function method() : string;

    /**
     * @return string
     */
    public function uri() : string;

    /**
     * @return string
     */
    public function path() : string;

    /**
     * Gets a "parameter" value.
     *
     * @param string $key
     * @param mixed  $default
     *
     * @return mixed
     */
    public function get(string $key, $default = null);

    /**
     * Get all of the input and files for the request.
     *
     * @return array
     */
    public function all();

    /**
     * Get a subset of the items from the input data.
     *
     * @param string[]|string $keys
     *
     * @return array
     */
    public function only($keys);

    /**
     * Get all of the input except for a specified array of items.
     *
     * @param string[]|string $keys
     *
     * @return array
     */
    public function except($keys);

    /**
     * Determine if the request contains a given input item key.
     *
     * @param string[]|string $key
     *
     * @return bool
     */
    public function exists($key);

    /**
     * Determine if the request contains a non-empty value for an input item.
     *
     * @param string[]|string $key
     *
     * @return bool
     */
    public function has($key);

    /**
     * Retrieve a server variable from the request.
     *
     * @param string $key
     * @param mixed  $default
     *
     * @return string|array
     */
    public function server(string $key = null, $default = null);

    /**
     * Get all of the segments for the request path.
     *
     * @return string[]
     */
    public function segments();

    /**
     * Get a segment from the URI.
     *
     * @param int         $index
     * @param string|null $default
     *
     * @return string|null
     */
    public function segment($index, $default = null);

    /**
     * Retrieve a file from the request.
     *
     * @param string $key
     * @param mixed  $default
     *
     * @return mixed
     */
    public function file(string $key = null, $default = null);

    /**
     * Determine if the uploaded data contains a file.
     *
     * @param string $key
     *
     * @return bool
     */
    public function hasFile(string $key);

    /**
     * Retrieve cookies from request
     *
     * @return string[]
     */
    public function cookies();

    /**
     * Retrieve a cookie from the request.
     *
     * @param string $key
     * @param mixed  $default
     *
     * @return string|array
     */
    public function cookie(string $key = null, $default = null);
}
