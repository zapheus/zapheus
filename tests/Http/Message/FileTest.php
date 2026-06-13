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
    public function test_failed_if_move_after_already_moved()
    {
        $target = str_replace('HelloWorld', 'MovedFile', $this->filename);

        $file = $this->instance($this->filename);

        $file->moveTo($target);

        copy($target, $this->filename);

        $this->doExpectException('RuntimeException');

        $file->moveTo($target);

        unlink($target);
    }

    /**
     * @return void
     */
    public function test_failed_if_move_with_empty_target()
    {
        $this->doExpectException('InvalidArgumentException');

        $this->self->moveTo('');
    }

    /**
     * @return void
     */
    public function test_failed_if_move_with_upload_error()
    {
        $file = new File($this->filename, basename($this->filename), UPLOAD_ERR_CANT_WRITE);

        $target = str_replace('HelloWorld', 'MovedFile', $this->filename);

        $this->doExpectException('RuntimeException');

        $file->moveTo($target);
    }

    /**
     * @return void
     */
    public function test_failed_if_moved_before_stream()
    {
        $target = str_replace('HelloWorld', 'MovedFile', $this->filename);

        $file = $this->instance($this->filename);

        $file->moveTo($target);

        copy($target, $this->filename);

        $this->doExpectException('RuntimeException');

        $file->getStream();

        unlink($target);
    }

    /**
     * @return void
     */
    public function test_passed_if_file_error_is_retrieved()
    {
        $expect = UPLOAD_ERR_OK;

        $actual = $this->self->getError();

        $this->assertEquals($expect, $actual);
    }

    /**
     * @return void
     */
    public function test_passed_if_file_is_moved()
    {
        $target = str_replace('HelloWorld', 'MovedFile', $this->filename);

        $file = $this->instance($this->filename);

        $file->moveTo($target);

        $this->assertFileExists($target);

        unlink($target);
    }

    /**
     * @return void
     */
    public function test_passed_if_file_name_is_retrieved()
    {
        $expect = basename($this->filename);

        $actual = $this->self->getClientFilename();

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

        $actual = $this->self->getSize();

        $this->assertEquals($expect, $actual);
    }

    /**
     * @return void
     */
    public function test_passed_if_file_stream_is_retrieved()
    {
        $expect = 'Zapheus\Http\Message\Stream';

        $actual = $this->self->getStream();

        $this->assertInstanceof($expect, $actual);
    }

    /**
     * @return void
     */
    public function test_passed_if_file_type_is_retrieved()
    {
        $expect = mime_content_type($this->filename);

        $actual = $this->self->getClientMediaType();

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
     * @return \Zapheus\Http\Message\File
     */
    protected function instance($filename)
    {
        file_put_contents($filename, 'Hello world');

        $name = basename($filename);

        return new File($filename, $name, UPLOAD_ERR_OK);
    }
}
