<?php

namespace Zapheus\Http\Message;

use Zapheus\Testcase;

/**
 * @package Zapheus
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class FileTest extends Testcase
{
    /**
     * @var string
     */
    protected $filename;

    /**
     * @var \Zapheus\Http\Message\File
     */
    protected $self;

    /**
     * @return void
     */
    public function test_passed_if_file_error_is_retrieved()
    {
        $expect = UPLOAD_ERR_OK;

        $actual = $this->self->error();

        $this->assertEquals($expect, $actual);
    }

    /**
     * @return void
     */
    public function test_passed_if_file_is_moved()
    {
        $target = str_replace('HelloWorld', 'MovedFile', $this->filename);

        $this->self->move($target);

        $this->assertFileExists($target);

        unlink($target);
    }

    /**
     * @return void
     */
    public function test_passed_if_file_name_is_retrieved()
    {
        $expect = basename($this->filename);

        $actual = $this->self->name();

        $this->assertEquals($expect, $actual);
    }

    /**
     * @return void
     */
    public function test_passed_if_file_path_is_retrieved()
    {
        $expect = $this->filename;

        $actual = $this->self->file();

        $this->assertEquals($expect, $actual);
    }

    /**
     * @return void
     */
    public function test_passed_if_file_size_is_retrieved()
    {
        $expect = filesize($this->filename);

        $actual = $this->self->size();

        $this->assertEquals($expect, $actual);
    }

    /**
     * @return void
     */
    public function test_passed_if_file_stream_is_retrieved()
    {
        $expect = 'Zapheus\Http\Message\Stream';

        $actual = $this->self->stream();

        $this->assertInstanceof($expect, $actual);
    }

    /**
     * @return void
     */
    public function test_passed_if_file_type_is_retrieved()
    {
        $expect = mime_content_type($this->filename);

        $actual = $this->self->type();

        $this->assertEquals($expect, $actual);
    }

    /**
     * @return void
     */
    protected function doSetUp()
    {
        $name = __DIR__ . '/../../Fixture/Views/HelloWorld.php';

        $this->filename = $name;

        $this->self = $this->instance($name);
    }

    /**
     * @param string $filename
     *
     * @return \File
     */
    protected function instance($filename)
    {
        file_put_contents($filename, 'Hello world');

        $name = basename($filename);

        return new File($filename, $name, UPLOAD_ERR_OK);
    }
}
