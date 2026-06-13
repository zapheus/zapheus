<?php

namespace Zapheus\Http\Message;

use Zapheus\Testcase;

/**
 * @package Zapheus
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class StreamTest extends Testcase
{
    /**
     * @var false|resource
     */
    protected $resource;

    /**
     * @var \Zapheus\Contract\Http\Message\Stream
     */
    protected $self;

    /**
     * @return void
     */
    public function test_passed_if_stream_contents_read()
    {
        $expect = 'Lorem ipsum dolor sit amet';

        $actual = $this->self->read(26);

        $this->self->close();

        $this->assertEquals($expect, $actual);
    }

    /**
     * @return void
     */
    public function test_passed_if_stream_to_string_works()
    {
        $expect = 'Lorem ipsum dolor sit amet';

        $actual = (string) $this->self;

        $this->self->close();

        $this->assertEquals($expect, $actual);
    }

    /**
     * @return void
     */
    protected function doSetUp()
    {
        $search = 'Http' . DIRECTORY_SEPARATOR . 'Message';

        $root = str_replace($search, 'Fixture', __DIR__);

        $file = $root . '/Views/LoremIpsum.php';

        $this->resource = $resource = fopen($file, 'r');

        $this->self = new Stream($this->resource);
    }
}
