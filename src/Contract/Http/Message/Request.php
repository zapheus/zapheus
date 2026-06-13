<?php

namespace Zapheus\Contract\Http\Message;

/**
 * @package Zapheus
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
interface Request extends Message
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
     * @return array<string, mixed>
     */
    public function attributes();

    /**
     * Returns the specified cookie from request.
     *
     * @param string $name
     *
     * @return array<string, string>
     */
    public function cookie($name);

    /**
     * Returns the cookies from the request.
     *
     * @return array<string, string>
     */
    public function cookies();

    /**
     * Returns any parameters provided in the request body.
     *
     * @return array<string, mixed>|object|null
     */
    public function data();

    /**
     * Returns normalized file upload data.
     *
     * @return \Zapheus\Contract\Http\Message\File[]
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
     * @return array<string, mixed>
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
     * @return array<string, mixed>
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
     * @return \Zapheus\Contract\Http\Message\Uri
     */
    public function uri();
}
