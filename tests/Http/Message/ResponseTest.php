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
    public function test_passed_if_reason_phrase_retrieved()
    {
        $expect = 'Internal Server Error';

        $this->self->code(500);

        $actual = $this->self->make()->reason();

        $this->assertEquals($expect, $actual);
    }

    /**
     * @return void
     */
    public function test_passed_if_status_code_retrieved()
    {
        $this->self->code($expect = 404);

        $actual = $this->self->make()->code();

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
