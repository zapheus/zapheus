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
     * Retrieves the message's request target.
     *
     * @return string
     */
    public function getRequestTarget();

    /**
     * Retrieves the HTTP method of the request.
     *
     * @return string
     */
    public function getMethod();

    /**
     * Retrieves the URI instance.
     *
     * @return \Zapheus\Contract\Http\Message\Uri
     */
    public function getUri();

    /**
     * Retrieve server parameters.
     *
     * @return array<string, string>
     */
    public function getServerParams();

    /**
     * Retrieve cookies.
     *
     * @return array<string, string>
     */
    public function getCookieParams();

    /**
     * Retrieve query string arguments.
     *
     * @return array<string, mixed>
     */
    public function getQueryParams();

    /**
     * Retrieve normalized file upload data.
     *
     * @return array<string, \Zapheus\Contract\Http\Message\File[]>
     */
    public function getUploadedFiles();

    /**
     * Retrieve any parameters provided in the request body.
     *
     * @return array<string, mixed>|object|null
     */
    public function getParsedBody();

    /**
     * Retrieve attributes derived from the request.
     *
     * @return array<string, mixed>
     */
    public function getAttributes();

    /**
     * Retrieve a single derived request attribute.
     *
     * @param string $name
     * @param mixed  $default
     *
     * @return mixed
     */
    public function getAttribute($name, $default = null);
}
