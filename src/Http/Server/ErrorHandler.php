<?php

namespace Zapheus\Http\Server;

use Zapheus\Contract\Http\Message\Request;
use Zapheus\Contract\Http\Server\Handler;

/**
 * @package Zapheus
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class ErrorHandler implements Handler
{
    /**
     * @var string
     */
    protected $default = 'Last middleware did not return a "%s" instance';

    /**
     * @var string
     */
    protected $response = 'Zapheus\Contract\Http\Message\Response';

    /**
     * @var string
     */
    protected $message;

    /**
     * Initializes the handler instance.
     */
    public function __construct()
    {
        $this->message = $this->default;
    }

    /**
     * Sets the exception message format.
     *
     * @param string $message
     *
     * @return self
     */
    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Handles the request and returns a response.
     *
     * @param \Zapheus\Contract\Http\Message\Request $request
     *
     * @return \Zapheus\Contract\Http\Message\Response
     */
    public function handle(Request $request)
    {
        $message = sprintf($this->message, $this->response);

        throw new \LogicException($message);
    }
}
