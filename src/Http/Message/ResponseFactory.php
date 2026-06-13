<?php

namespace Zapheus\Http\Message;

use Zapheus\Contract\Http\Message\Response as Contract;

/**
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
     * Sets the response instance and copies its properties.
     *
     * @param \Zapheus\Contract\Http\Message\Response $response
     *
     * @return self
     */
    public function setResponse(Contract $response)
    {
        parent::setMessage($response);

        $this->code = $response->code();

        return $this;
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
     * @return \Zapheus\Contract\Http\Message\Response
     */
    public function make()
    {
        $response = new Response($this->code, $this->headers, $this->version);

        if ($this->stream)
        {
            $response->setStream($this->stream);
        }

        return $response;
    }
}
