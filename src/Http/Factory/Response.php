<?php

namespace Zapheus\Http\Factory;

use Zapheus\Contract\Http\Message\Response as Contract;
use Zapheus\Http\Message\Response as Base;

/**
 * @package Zapheus
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class Response extends Message
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
        $response = new Base($this->code, $this->headers, $this->version);

        if ($this->stream)
        {
            $response->setStream($this->stream);
        }

        return $response;
    }
}
