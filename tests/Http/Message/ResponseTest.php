<?php

namespace Zapheus\Http\Message;

use Zapheus\Testcase;

/**
 * @package Zapheus
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class ResponseTest extends Testcase
{
    /**
     * @var \Zapheus\Http\Message\ResponseFactory
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
        $this->self = (new ResponseFactory)->setResponse(new Response);
    }
}
