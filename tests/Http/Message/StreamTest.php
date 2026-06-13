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
     * @var \Zapheus\Http\Message\Stream
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

        $actual = $this->self->__toString();

        $this->self->close();

        $this->assertEquals($expect, $actual);
    }

    /**
     * @return void
     */
    protected function doSetUp()
    {
        $slash = DIRECTORY_SEPARATOR;

        $search = 'Http' . $slash . 'Message';

        $root = str_replace($search, 'Fixture', __DIR__);

        $file = $root . '/Views/LoremIpsum.php';

        /** @var resource */
        $resource = fopen($file, 'r');

        $this->self = new Stream($resource);
    }
}
