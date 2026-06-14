<?php

namespace Zapheus\Http\Message;

use Zapheus\Http\Factory\File as FileFactory;
use Zapheus\Http\Factory\Request as RequestFactory;
use Zapheus\Testcase;

/**
 * @package Zapheus
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class RequestTest extends Testcase
{
    /**
     * @var \Zapheus\Http\Factory\Request
     */
    protected $self;

    /**
     * @return void
     */
    public function test_failed_if_body_is_invalid_type()
    {
        $this->doExpectException('InvalidArgumentException');

        $this->self->withParsedBody('string');
    }

    /**
     * @return void
     */
    public function test_failed_if_method_is_empty()
    {
        $this->doExpectException('InvalidArgumentException');

        $this->self->withMethod('');
    }

    /**
     * @return void
     */
    public function test_failed_if_uploaded_file_is_invalid()
    {
        $this->doExpectException('InvalidArgumentException');

        $this->self->withUploadedFiles(array('test' => array('not_a_file')));
    }

    /**
     * @return void
     */
    public function test_passed_if_all_cookies_retrieved()
    {
        $expect = array('name' => 'Rougin');

        $expect['address'] = 'Tomorrowland';

        $this->self->withCookieParams($expect);

        $actual = $this->self->make()->getCookieParams();

        $this->assertEquals($expect, $actual);
    }

    /**
     * @return void
     */
    public function test_passed_if_attribute_is_removed()
    {
        $this->self->withAttribute('key', 'value');

        $this->self->withoutAttribute('key');

        $request = $this->self->make();

        $actual = $request->getAttribute('key');

        $this->assertNull($actual);
    }

    /**
     * @return void
     */
    public function test_passed_if_attribute_is_retrieved()
    {
        $expect = 'Rougin Royce';

        $this->self->withAttribute('name', $expect);

        $request = $this->self->make();

        $actual = $request->getAttribute('name');

        $this->assertEquals($expect, $actual);
    }

    /**
     * @return void
     */
    public function test_passed_if_attributes_are_retrieved()
    {
        $expect = array('name' => 'Rougin Royce');

        $this->self->withAttributes($expect);

        $actual = $this->self->make()->getAttributes();

        $this->assertEquals($expect, $actual);
    }

    /**
     * @return void
     */
    public function test_passed_if_http_headers_from_server()
    {
        $expect = array('application/json');

        $server = array('REQUEST_METHOD' => 'GET');

        $server['REQUEST_URI'] = '/';

        $server['SERVER_NAME'] = 'roug.in';

        $server['SERVER_PORT'] = 8000;

        $server['HTTP_CONTENT_TYPE'] = 'application/json';

        $factory = new RequestFactory;

        $factory->withServerParams($server);

        $request = $factory->make();

        $actual = $request->getHeader('Content-Type');

        $this->assertEquals($expect, $actual);
    }

    /**
     * @return void
     */
    public function test_passed_if_http_method_retrieved()
    {
        $this->self->withMethod($expect = 'POST');

        $actual = $this->self->make()->getMethod();

        $this->assertEquals($expect, $actual);
    }

    /**
     * @return void
     */
    public function test_passed_if_parsed_body_retrieved()
    {
        $expect = array('name' => 'Rougin R');

        $this->self->withParsedBody($expect);

        $actual = $this->self->make()->getParsedBody();

        $this->assertEquals($expect, $actual);
    }

    /**
     * @return void
     */
    public function test_passed_if_query_params_retrieved()
    {
        $expect = array('name' => 'Rougin');

        $expect['address'] = 'Tomorrowland';

        $this->self->withQueryParams($expect);

        $actual = $this->self->make()->getQueryParams();

        $this->assertEquals($expect, $actual);
    }

    /**
     * @return void
     */
    public function test_passed_if_request_target_retrieved()
    {
        $this->self->withRequestTarget($expect = 'o');

        $actual = $this->self->make()->getRequestTarget();

        $this->assertEquals($expect, $actual);
    }

    /**
     * @return void
     */
    public function test_passed_if_server_param_by_name()
    {
        $request = $this->self->make();

        $server = $request->getServerParams();

        $actual = $server['SERVER_NAME'];

        $expect = 'roug.in';

        $this->assertEquals($expect, $actual);
    }

    /**
     * @return void
     */
    public function test_passed_if_server_params_retrieved()
    {
        /** @var array<string, mixed> */
        $expect = $_SERVER;

        $expect['REQUEST_METHOD'] = 'GET';

        $expect['REQUEST_URI'] = '/';

        $expect['SERVER_NAME'] = 'roug.in';

        $expect['SERVER_PORT'] = 8000;

        $request = $this->self->make();

        $actual = $request->getServerParams();

        $this->assertEquals($expect, $actual);
    }

    /**
     * @return void
     */
    public function test_passed_if_server_with_https()
    {
        $server = array('REQUEST_METHOD' => 'GET');

        $server['REQUEST_URI'] = '/';

        $server['SERVER_NAME'] = 'roug.in';

        $server['SERVER_PORT'] = 443;

        $server['HTTPS'] = 'on';

        $factory = new RequestFactory;

        $factory->withServerParams($server);

        $request = $factory->make();

        $expect = 'https';

        $actual = $request->getUri()->getScheme();

        $this->assertEquals($expect, $actual);
    }

    /**
     * @return void
     */
    public function test_passed_if_uploaded_files_retrieved()
    {
        $fixtures = __DIR__ . '/../../Fixture';

        $factory = new FileFactory;

        $factory->withError(0);

        $factory->withFile($fixtures . '/Views/LoremIpsum.php');

        $factory->withClientFilename('LoremIpsum.php');

        $expect = array('file' => array($factory->make()));

        /** @var array<string, mixed> */
        $items = $_FILES;

        $files = $factory->normalize($items);

        $this->self->withUploadedFiles($files);

        $actual = $this->self->make()->getUploadedFiles();

        $this->assertEquals($expect, $actual);
    }

    /**
     * @return void
     */
    public function test_passed_if_uri_from_https()
    {
        $server = array('REQUEST_METHOD' => 'GET');

        $server['REQUEST_URI'] = '/';

        $server['SERVER_NAME'] = 'roug.in';

        $server['SERVER_PORT'] = 443;

        $server['HTTPS'] = 'on';

        $request = new Request('GET', '/', $server);

        $expect = 'https';

        $actual = $request->getUri()->getScheme();

        $this->assertEquals($expect, $actual);
    }

    /**
     * @return void
     */
    public function test_passed_if_uri_instance_retrieved()
    {
        $expect = new Uri('https://roug.in');

        $this->self->withUri($expect);

        $actual = $this->self->make()->getUri();

        $this->assertEquals($expect, $actual);
    }

    /**
     * @return void
     */
    protected function doSetUp()
    {
        // Simulate the $_SERVER data -----
        /** @var array<string, string> */
        $server = $_SERVER;

        $server['REQUEST_METHOD'] = 'GET';

        $server['REQUEST_URI'] = '/';

        $server['SERVER_NAME'] = 'roug.in';

        $server['SERVER_PORT'] = '8000';
        // --------------------------------

        // Simulate a file upload in $_FILES -----------
        $_FILES['file']['error'] = 0;

        $path = __DIR__ . '/../..';

        $file = $path . '/Fixture/Views/LoremIpsum.php';

        $_FILES['file']['name'] = basename($file);

        $_FILES['file']['tmp_name'] = $file;
        // ---------------------------------------------

        $request = new Request('GET', '/', $server);

        $factory = new RequestFactory;

        $factory = $factory->setRequest($request);

        $factory->withServerParams($server);

        $this->self = $factory;
    }
}
