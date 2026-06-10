<?php

namespace Zapheus\Http\Message;

use Zapheus\Testcase;

/**
 * Stream Test
 *
 * @package Zapheus
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class StreamTest extends Testcase
{
    /**
     * @var resource
     */
    protected $resource;

    /**
     * @var \Zapheus\Http\Message\StreamInterface
     */
    protected $stream;

    /**
     * Sets up the stream instance.
     *
     * @return void
     */
    protected function doSetUp()
    {
        $search = 'Http' . DIRECTORY_SEPARATOR . 'Message';

        $root = str_replace($search, 'Fixture', __DIR__);

        $file = (string) $root . '/Views/LoremIpsum.php';

        $this->resource = $resource = fopen($file, 'r');

        $this->stream = new Stream($this->resource);
    }

    /**
     * Tests StreamInterface::read.
     *
     * @return void
     */
    public function testReadMethod()
    {
        $expected = 'Lorem ipsum dolor sit amet';

        $result = $this->stream->read(26);

        $this->stream->close();

        $this->assertEquals($expected, $result);
    }

    /**
     * Tests StreamInterface::__toString.
     *
     * @return void
     */
    public function testToStringMagicMethod()
    {
        $expected = 'Lorem ipsum dolor sit amet';

        $result = (string) $this->stream;

        $this->stream->close();

        $this->assertEquals($expected, $result);
    }
}
