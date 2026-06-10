<?php

namespace Zapheus\Http\Message;

use Zapheus\Testcase;

/**
 * Request Test
 *
 * @package Zapheus
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class RequestTest extends Testcase
{
    /**
     * @var \Zapheus\Http\Message\RequestFactory
     */
    protected $factory;

    /**
     * Tests RequestInterface::attribute.
     *
     * @return void
     */
    public function testAttributeMethod()
    {
        $expected = (string) 'Rougin Royce';

        $this->factory->attribute('name', $expected);

        $request = $this->factory->make();

        $result = $request->attribute('name');

        $this->assertEquals($expected, $result);
    }

    /**
     * Tests RequestInterface::attributes.
     *
     * @return void
     */
    public function testAttributesMethod()
    {
        $expected = array('name' => 'Rougin Royce');

        $this->factory->attributes($expected);

        $result = $this->factory->make()->attributes();

        $this->assertEquals($expected, $result);
    }

    /**
     * Tests RequestInterface::cookie.
     *
     * @return void
     */
    public function testCookieMethod()
    {
        $this->factory->cookie('address', $expected = 'ZS');

        $result = $this->factory->make()->cookie('address');

        $this->assertEquals($expected, $result);
    }

    /**
     * Tests RequestInterface::cookies.
     *
     * @return void
     */
    public function testCookiesMethod()
    {
        $expected = array('name' => 'Rougin');

        $expected['address'] = 'Tomorrowland';

        $this->factory->cookies((array) $expected);

        $result = $this->factory->make()->cookies();

        $this->assertEquals($expected, $result);
    }

    /**
     * Tests RequestInterface::data.
     *
     * @return void
     */
    public function testDataMethod()
    {
        $expected = array('name' => 'Rougin R');

        $this->factory->data((array) $expected);

        $result = $this->factory->make()->data();

        $this->assertEquals($expected, $result);
    }

    /**
     * Tests RequestInterface::files.
     *
     * @return void
     */
    public function testFilesMethod()
    {
        $fixtures = __DIR__ . '/../../Fixture';

        $factory = new FileFactory;

        $factory->error(0);

        $factory->file($fixtures . '/Views/LoremIpsum.php');

        $factory->name('LoremIpsum.php');

        $expected = array('file' => array($factory->make()));

        $files = $factory->normalize($_FILES);

        $this->factory->files((array) $files);

        $result = $this->factory->make()->files();

        $this->assertEquals($expected, $result);
    }

    /**
     * Tests RequestInterface::method.
     *
     * @return void
     */
    public function testMethodMethod()
    {
        $this->factory->method($expected = 'POST');

        $result = $this->factory->make()->method();

        $this->assertEquals($expected, $result);
    }

    /**
     * Tests RequestInterface::queries.
     *
     * @return void
     */
    public function testQueriesMethod()
    {
        $expected = array('name' => 'Rougin');

        $expected['address'] = 'Tomorrowland';

        $this->factory->queries((array) $expected);

        $result = $this->factory->make()->queries();

        $this->assertEquals($expected, $result);
    }

    /**
     * Tests RequestInterface::query.
     *
     * @return void
     */
    public function testQueryMethod()
    {
        $this->factory->query('name', $expected = 'ZS');

        $result = $this->factory->make()->query('name');

        $this->assertEquals($expected, $result);
    }

    /**
     * Tests RequestInterface::server.
     *
     * @return void
     */
    public function testServerMethod()
    {
        $expected = (array) $_SERVER;

        $request = $this->factory->make();

        $result = $request->server();

        $this->assertEquals($expected, $result);
    }

    /**
     * Tests RequestInterface::server with a specified name.
     *
     * @return void
     */
    public function testServerMethodWithSpecifiedName()
    {
        $expected = (string) 'roug.in';

        $request = $this->factory->make();

        $result = $request->server('SERVER_NAME');

        $this->assertEquals($expected, $result);
    }

    /**
     * Tests RequestInterface::target.
     *
     * @return void
     */
    public function testTargetMethod()
    {
        $this->factory->target($expected = 'o');

        $result = $this->factory->make()->target();

        $this->assertEquals($expected, $result);
    }

    /**
     * Tests RequestInterface::uri.
     *
     * @return void
     */
    public function testUriMethod()
    {
        $expected = new Uri('https://roug.in');

        $this->factory->uri($expected);

        $result = $this->factory->make()->uri();

        $this->assertEquals($expected, $result);
    }

    /**
     * Sets up the request instance.
     *
     * @return void
     */
    protected function doSetUp()
    {
        $_SERVER['REQUEST_METHOD'] = 'GET';

        $_SERVER['REQUEST_URI'] = '/';

        $_SERVER['SERVER_NAME'] = 'roug.in';

        $_SERVER['SERVER_PORT'] = 8000;

        $_SERVER['HTTPS'] = 'off';

        $_FILES['file']['error'] = 0;

        $file = __DIR__ . '/../../Fixture/Views/LoremIpsum.php';

        $_FILES['file']['name'] = basename($file);

        $_FILES['file']['tmp_name'] = (string) $file;

        $request = new Request('GET', '/', $_SERVER);

        $factory = new RequestFactory($request);

        $factory->server($_SERVER);

        $this->factory = $factory;
    }
}
