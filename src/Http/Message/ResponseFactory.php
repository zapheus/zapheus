<?php

namespace Zapheus\Http\Message;

/**
 * Response Factory
 *
 * @package Zapheus
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class ResponseFactory extends MessageFactory
{
    /**
     * @var integer
     */
    protected $code = 200;

    /**
     * Initializes the response instance.
     *
     * @param \Zapheus\Http\Message\ResponseInterface|null $response
     */
    public function __construct(ResponseInterface $response = null)
    {
        parent::__construct($response);

        if ($response === null)
        {
            return;
        }

        $this->code = $response->code();
    }

    /**
     * Sets the HTTP code.
     *
     * @param integer $code
     *
     * @return self
     */
    public function code($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Creates the response instance.
     *
     * @return \Zapheus\Http\Message\ResponseInterface
     */
    public function make()
    {
        return new Response($this->code, $this->headers, $this->stream, $this->version);
    }
}
