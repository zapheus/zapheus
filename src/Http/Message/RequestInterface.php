<?php

namespace Zapheus\Http\Message;

/**
 * Request Interface
 *
 * @package Zapheus
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
interface RequestInterface extends MessageInterface
{
    /**
     * Returns an instance with the specified derived request attribute.
     *
     * @param string $name
     *
     * @return mixed
     */
    public function attribute($name);

    /**
     * Returns an array of attributes derived from the request.
     *
     * @return array
     */
    public function attributes();

    /**
     * Returns the specified cookie from request.
     *
     * @param string $name
     *
     * @return array
     */
    public function cookie($name);

    /**
     * Returns the cookies from the request.
     *
     * @return array
     */
    public function cookies();

    /**
     * Returns any parameters provided in the request body.
     *
     * @return array|object|null
     */
    public function data();

    /**
     * Returns normalized file upload data.
     *
     * @return \Zapheus\Http\Message\UploadedFileInterface[]
     */
    public function files();

    /**
     * Returns the HTTP method of the request.
     *
     * @return string
     */
    public function method();

    /**
     * Returns the query string arguments.
     *
     * @return array
     */
    public function queries();

    /**
     * Returns the specified query string argument.
     *
     * @param string $name
     *
     * @return mixed
     */
    public function query($name);

    /**
     * Returns the server parameter/s.
     *
     * @param string|null $name
     *
     * @return array
     */
    public function server($name = null);

    /**
     * Returns the message's request target.
     *
     * @return string
     */
    public function target();

    /**
     * Returns the URI instance.
     *
     * @return \Zapheus\Http\Message\Uri
     */
    public function uri();
}
