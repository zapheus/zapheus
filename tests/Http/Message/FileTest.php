<?php

namespace Zapheus\Http\Message;

use Zapheus\Testcase;

/**
 * File Test
 *
 * @package Zapheus
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class FileTest extends Testcase
{
    /**
     * @var \Zapheus\Http\Message\FileInterface
     */
    protected $file;

    /**
     * @var string
     */
    protected $filename;

    /**
     * Sets up the uploaded file instance.
     *
     * @return void
     */
    protected function doSetUp()
    {
        $name = __DIR__ . '/../../Fixture/Views/HelloWorld.php';

        $this->filename = $name;

        $this->file = $this->instance($name);
    }

    /**
     * Tests FileInterface::error.
     *
     * @return void
     */
    public function testErrorMethod()
    {
        $expected = UPLOAD_ERR_OK;

        $result = $this->file->error();

        $this->assertEquals($expected, $result);
    }

    /**
     * Tests FileInterface::file.
     *
     * @return void
     */
    public function testFileMethod()
    {
        $expected = $this->filename;

        $result = $this->file->file();

        $this->assertEquals($expected, $result);
    }

    /**
     * Tests FileInterface::move.
     *
     * @return void
     */
    public function testMoveMethod()
    {
        $target = str_replace('HelloWorld', 'MovedFile', $this->filename);

        $this->file->move($target);

        $this->assertFileExists($target);

        unlink($target);
    }

    /**
     * Tests FileInterface::name.
     *
     * @return void
     */
    public function testNameMethod()
    {
        $expected = basename($this->filename);

        $result = $this->file->name();

        $this->assertEquals($expected, $result);
    }

    /**
     * Tests FileInterface::size.
     *
     * @return void
     */
    public function testSizeMethod()
    {
        $expected = filesize($this->filename);

        $result = $this->file->size();

        $this->assertEquals($expected, $result);
    }

    /**
     * Tests FileInterface::stream.
     *
     * @return void
     */
    public function testStreamMethod()
    {
        $expected = 'Zapheus\Http\Message\Stream';

        $result = $this->file->stream();

        $this->assertInstanceof($expected, $result);
    }

    /**
     * Tests FileInterface::type.
     *
     * @return void
     */
    public function testTypeMethod()
    {
        $expected = mime_content_type($this->filename);

        $result = $this->file->type();

        $this->assertEquals($expected, $result);
    }

    /**
     * Creates a new \File instance.
     *
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
