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
     * @var string
     */
    protected $reason = '';

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

        $this->code = $response->getStatusCode();

        return $this;
    }

    /**
     * Return an instance with the specified HTTP status code and reason phrase.
     *
     * @param integer $code
     * @param string  $reasonPhrase
     *
     * @return self
     * @throws \InvalidArgumentException
     */
    public function withStatus($code, $reasonPhrase = '')
    {
        if ($code < 100 || $code > 599)
        {
            $text = 'Status code must be an integer between 100 and 599.';

            throw new \InvalidArgumentException($text);
        }

        $this->code = $code;

        $this->reason = $reasonPhrase;

        return $this;
    }
}
