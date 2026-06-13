<?php

namespace Zapheus\Http\Message;

use Zapheus\Http\Factory\Message as MessageFactory;
use Zapheus\Testcase;

/**
 * @package Zapheus
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class MessageTest extends Testcase
{
    /**
     * @var \Zapheus\Http\Factory\Message
     */
    protected $self;

    /**
     * @return void
     */
    public function test_passed_if_all_headers_retrieved()
    {
        $expect = array('names' => array('Rougin', 'Royce'));

        $this->self->headers($expect);

        $actual = $this->self->make()->headers();

        $this->assertEquals($expect, $actual);
    }

    /**
     * @return void
     */
    public function test_passed_if_custom_stream_is_set()
    {
        $stream = new Stream(fopen('php://temp', 'r+'));

        $stream->write('Hello, world');

        $this->self->stream($stream);

        $expect = $stream;

        $actual = $this->self->make()->stream();

        $this->assertEquals($expect, $actual);
    }

    /**
     * @return void
     */
    public function test_passed_if_header_value_retrieved()
    {
        $expect = array('Rougin', 'Royce');

        $this->self->header('names', $expect);

        $message = $this->self->make();

        $actual = $message->header('names');

        $this->assertEquals($expect, $actual);
    }

    /**
     * @return void
     */
    public function test_passed_if_stream_instance_returned()
    {
        $expect = 'Zapheus\Http\Message\Stream';

        $actual = $this->self->make()->stream();

        $this->assertInstanceOf($expect, $actual);
    }

    /**
     * @return void
     */
    public function test_passed_if_version_is_retrieved()
    {
        $this->self->version($expect = '2.0');

        $actual = $this->self->make()->version();

        $this->assertEquals($expect, $actual);
    }

    /**
     * @return void
     */
    protected function doSetUp()
    {
        $this->self = new MessageFactory;
    }
}
