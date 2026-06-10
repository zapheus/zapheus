<?php

namespace Zapheus\Http\Server;

use Zapheus\Http\Message\RequestInterface;

/**
 * Error Handler
 *
 * @package Zapheus
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class ErrorHandler implements HandlerInterface
{
    /**
     * @var string
     */
    protected $default = 'Last middleware did not return a "%s" instance';

    /**
     * @var string
     */
    protected $response = 'Zapheus\Http\Message\ResponseInterface';

    /**
     * @var string
     */
    protected $message;

    /**
     * Initializes the handler instance.
     *
     * @param string|null $message
     */
    public function __construct($message = null)
    {
        $this->message = $message ?: $this->default;
    }

    /**
     * Handles the request and returns a response.
     *
     * @param \Zapheus\Http\Message\RequestInterface $request
     *
     * @return \Zapheus\Http\Message\ResponseInterface
     */
    public function handle(RequestInterface $request)
    {
        $message = sprintf($this->message, $this->response);

        throw new \LogicException($message);
    }
}
