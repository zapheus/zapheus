<?php

namespace Zapheus\Http\Message;

use Zapheus\Testcase;

/**
 * Message Test
 *
 * @package Zapheus
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class MessageTest extends Testcase
{
    /**
     * @var \Zapheus\Http\Message\MessageFactory
     */
    protected $factory;

    /**
     * Tests MessageInterface::header.
     *
     * @return void
     */
    public function testHeaderMethod()
    {
        $expected = array('Rougin', 'Royce');

        $this->factory->header('names', $expected);

        $message = $this->factory->make();

        $result = $message->header('names');

        $this->assertEquals($expected, $result);
    }

    /**
     * Tests MessageInterface::headers.
     *
     * @return void
     */
    public function testHeadersMethod()
    {
        $expected = array('names' => array('Rougin', 'Royce'));

        $this->factory->headers($expected);

        $result = $this->factory->make()->headers();

        $this->assertEquals($expected, $result);
    }

    /**
     * Tests MessageInterface::stream.
     *
     * @return void
     */
    public function testStreamMethod()
    {
        $expected = 'Zapheus\Http\Message\Stream';

        $result = $this->factory->make()->stream();

        $this->assertInstanceOf($expected, $result);
    }

    /**
     * Tests MessageInterface::stream with another stream instance.
     *
     * @return void
     */
    public function testStreamMethodWithAnotherStreamInstance()
    {
        $stream = new Stream(fopen('php://temp', 'r+'));

        $stream->write('Hello, world');

        $this->factory->stream($stream);

        $expected = $stream;

        $result = $this->factory->make()->stream();

        $this->assertEquals($expected, $result);
    }

    /**
     * Tests MessageInterface::version.
     *
     * @return void
     */
    public function testVersionMethod()
    {
        $this->factory->version($expected = '2.0');

        $result = $this->factory->make()->version();

        $this->assertEquals($expected, $result);
    }

    /**
     * Sets up the factory instance.
     *
     * @return void
     */
    protected function doSetUp()
    {
        $this->factory = new MessageFactory;
    }
}
