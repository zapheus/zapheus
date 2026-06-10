<?php

namespace Zapheus\Http\Message;

/**
 * Response Interface
 *
 * @package Zapheus
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
interface ResponseInterface extends MessageInterface
{
    /**
     * Returns the response status code.
     *
     * @return integer
     */
    public function code();

    /**
     * Returns the response reason phrase associated with the status code.
     *
     * @return string
     */
    public function reason();
}
