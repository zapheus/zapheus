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
        $data = array('Rougin', 'Royce');

        $expect = array('names' => $data);

        $this->self->withHeader('names', $data);

        $actual = $this->self->make()->getHeaders();

        $this->assertEquals($expect, $actual);
    }

    /**
     * @return void
     */
    public function test_passed_if_custom_stream_is_set()
    {
        /** @var resource */
        $file = fopen('php://temp', 'r+');

        $stream = new Stream($file);

        $stream->write('Hello, world');

        $this->self->withBody($stream);

        $expect = $stream;

        $actual = $this->self->make()->getBody();

        $this->assertEquals($expect, $actual);
    }

    /**
     * @return void
     */
    public function test_passed_if_header_existence_checked()
    {
        $this->self->withHeader('names', array('Rougin'));

        $message = $this->self->make();

        $this->assertTrue($message->hasHeader('names'));

        $this->assertFalse($message->hasHeader('missing'));
    }

    /**
     * @return void
     */
    public function test_passed_if_header_is_added()
    {
        $expect = array('Rougin', 'Royce');

        $this->self->withHeader('names', array('Rougin'));

        $this->self->withAddedHeader('names', 'Royce');

        $actual = $this->self->make()->getHeader('names');

        $this->assertEquals($expect, $actual);
    }

    /**
     * @return void
     */
    public function test_passed_if_header_is_appended()
    {
        $expect = array('Rougin');

        $this->self->withAddedHeader('names', 'Rougin');

        $actual = $this->self->make()->getHeader('names');

        $this->assertEquals($expect, $actual);
    }

    /**
     * @return void
     */
    public function test_passed_if_header_is_removed()
    {
        $expect = array();

        $this->self->withHeader('names', array('Rougin'));

        $this->self->withoutHeader('names');

        $actual = $this->self->make()->getHeader('names');

        $this->assertEquals($expect, $actual);
    }

    /**
     * @return void
     */
    public function test_passed_if_header_line_retrieved()
    {
        $expect = 'Rougin,Royce';

        $this->self->withHeader('names', array('Rougin', 'Royce'));

        $actual = $this->self->make()->getHeaderLine('names');

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
