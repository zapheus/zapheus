<?php

namespace Zapheus\Http\Message;

/**
 * Response Interface
 *
 * @package Zapheus
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
interface ResponseInterface extends MessageInterface
{
    /**
     * Returns the response reason phrase associated with the status code.
     *
     * @return string
     */
    public function getReasonPhrase();

    /**
     * Returns the response status code.
     *
     * @return integer
     */
    public function getStatusCode();

    /**
     * Returns an instance with the specified status code and, optionally, reason phrase.
     *
     * @param  integer $code
     * @param  string  $reason
     * @return static
     *
     * @throws \InvalidArgumentException
     */
    public function withStatus($code, $reason = '');
}
