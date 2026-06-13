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
    public function test_failed_if_header_name_is_invalid()
    {
        $this->doExpectException('InvalidArgumentException');

        $this->self->withHeader('inv@lid', array('test'));
    }

    /**
     * @return void
     */
    public function test_passed_if_all_headers_retrieved()
    {
        $expect = array('names' => array('Rougin', 'Royce'));

        $this->self->withHeader('names', array('Rougin', 'Royce'));

        $actual = $this->self->make()->getHeaders();

        $this->assertEquals($expect, $actual);
    }

    /**
     * @return void
     */
    public function test_passed_if_custom_stream_is_set()
    {
        $stream = new Stream(fopen('php://temp', 'r+'));

        $stream->write('Hello, world');

        $this->self->withBody($stream);

        $expect = $stream;

        $actual = $this->self->make()->getBody();

        $this->assertEquals($expect, $actual);
    }

    /**
     * @return void
     */
    public function test_passed_if_header_value_retrieved()
    {
        $expect = array('Rougin', 'Royce');

        $this->self->withHeader('names', $expect);

        $message = $this->self->make();

        $actual = $message->getHeader('names');

        $this->assertEquals($expect, $actual);
    }

    /**
     * @return void
     */
    public function test_passed_if_headers_are_case_insensitive()
    {
        $expect = array('Rougin');

        $this->self->withHeader('Content-Type', array('Rougin'));

        $message = $this->self->make();

        $actual = $message->getHeader('content-type');

        $this->assertEquals($expect, $actual);
    }

    /**
     * @return void
     */
    public function test_passed_if_stream_instance_returned()
    {
        $expect = 'Zapheus\Http\Message\Stream';

        $actual = $this->self->make()->getBody();

        $this->assertInstanceOf($expect, $actual);
    }

    /**
     * @return void
     */
    public function test_passed_if_version_is_retrieved()
    {
        $this->self->withProtocolVersion($expect = '2.0');

        $actual = $this->self->make()->getProtocolVersion();

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
