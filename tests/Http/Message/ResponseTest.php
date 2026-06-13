<?php

namespace Zapheus\Http\Message;

use Zapheus\Http\Factory\Response as ResponseFactory;
use Zapheus\Testcase;

/**
 * @package Zapheus
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class ResponseTest extends Testcase
{
    /**
     * @var \Zapheus\Http\Factory\Response
     */
    protected $self;

    /**
     * @return void
     */
    public function test_failed_if_status_code_is_invalid()
    {
        $this->doExpectException('InvalidArgumentException');

        $this->self->withStatus(99);
    }

    /**
     * @return void
     */
    public function test_passed_if_reason_phrase_retrieved()
    {
        $expect = 'Internal Server Error';

        $this->self->withStatus(500);

        $actual = $this->self->make()->getReasonPhrase();

        $this->assertEquals($expect, $actual);
    }

    /**
     * @return void
     */
    public function test_passed_if_status_code_retrieved()
    {
        $this->self->withStatus($expect = 404);

        $actual = $this->self->make()->getStatusCode();

        $this->assertEquals($expect, $actual);
    }

    /**
     * @return void
     */
    protected function doSetUp()
    {
        $factory = new ResponseFactory;

        $this->self = $factory->setResponse(new Response);
    }
}
