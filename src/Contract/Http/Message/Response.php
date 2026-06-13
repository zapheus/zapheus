<?php

namespace Zapheus\Contract\Http\Message;

/**
 * @package Zapheus
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
interface Response extends Message
{
    /**
     * Gets the response status code.
     *
     * @return integer
     */
    public function getStatusCode();

    /**
     * Gets the response reason phrase associated with the status code.
     *
     * @return string
     */
    public function getReasonPhrase();
}
