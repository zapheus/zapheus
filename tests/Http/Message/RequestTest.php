<?php

namespace Zapheus\Http\Message;

use Zapheus\Testcase;

/**
 * @package Zapheus
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class RequestTest extends Testcase
{
    /**
     * @var \Zapheus\Http\Message\RequestFactory
     */
    protected $self;

    /**
     * @return void
     */
    public function test_passed_if_all_cookies_retrieved()
    {
        $expect = array('name' => 'Rougin');

        $expect['address'] = 'Tomorrowland';

        $this->self->cookies($expect);

        $actual = $this->self->make()->cookies();

        $this->assertEquals($expect, $actual);
    }

    /**
     * @return void
     */
    public function test_passed_if_attribute_is_retrieved()
    {
        $expect = 'Rougin Royce';

        $this->self->attribute('name', $expect);

        $request = $this->self->make();

        $actual = $request->attribute('name');

        $this->assertEquals($expect, $actual);
    }

    /**
     * @return void
     */
    public function test_passed_if_attributes_are_retrieved()
    {
        $expect = array('name' => 'Rougin Royce');

        $this->self->attributes($expect);

        $actual = $this->self->make()->attributes();

        $this->assertEquals($expect, $actual);
    }

    /**
     * @return void
     */
    public function test_passed_if_cookie_value_retrieved()
    {
        $this->self->cookie('address', $expect = 'ZS');

        $actual = $this->self->make()->cookie('address');

        $this->assertEquals($expect, $actual);
    }

    /**
     * @return void
     */
    public function test_passed_if_http_method_retrieved()
    {
        $this->self->method($expect = 'POST');

        $actual = $this->self->make()->method();

        $this->assertEquals($expect, $actual);
    }

    /**
     * @return void
     */
    public function test_passed_if_parsed_body_retrieved()
    {
        $expect = array('name' => 'Rougin R');

        $this->self->data($expect);

        $actual = $this->self->make()->data();

        $this->assertEquals($expect, $actual);
    }

    /**
     * @return void
     */
    public function test_passed_if_query_params_retrieved()
    {
        $expect = array('name' => 'Rougin');

        $expect['address'] = 'Tomorrowland';

        $this->self->queries($expect);

        $actual = $this->self->make()->queries();

        $this->assertEquals($expect, $actual);
    }

    /**
     * @return void
     */
    public function test_passed_if_request_target_retrieved()
    {
        $this->self->target($expect = 'o');

        $actual = $this->self->make()->target();

        $this->assertEquals($expect, $actual);
    }

    /**
     * @return void
     */
    public function test_passed_if_server_param_by_name()
    {
        $expect = 'roug.in';

        $request = $this->self->make();

        $actual = $request->server('SERVER_NAME');

        $this->assertEquals($expect, $actual);
    }

    /**
     * @return void
     */
    public function test_passed_if_server_params_retrieved()
    {
        $expect = $_SERVER;

        $request = $this->self->make();

        $actual = $request->server();

        $this->assertEquals($expect, $actual);
    }

    /**
     * @return void
     */
    public function test_passed_if_single_query_retrieved()
    {
        $this->self->query('name', $expect = 'ZS');

        $actual = $this->self->make()->query('name');

        $this->assertEquals($expect, $actual);
    }

    /**
     * @return void
     */
    public function test_passed_if_uploaded_files_retrieved()
    {
        $fixtures = __DIR__ . '/../../Fixture';

        $factory = new FileFactory;

        $factory->error(0);

        $factory->file($fixtures . '/Views/LoremIpsum.php');

        $factory->name('LoremIpsum.php');

        $expect = array('file' => array($factory->make()));

        /** @var array<string, mixed> */
        $items = $_FILES;

        $files = $factory->normalize($items);

        $this->self->files($files);

        $actual = $this->self->make()->files();

        $this->assertEquals($expect, $actual);
    }

    /**
     * @return void
     */
    public function test_passed_if_uri_instance_retrieved()
    {
        $expect = new Uri('https://roug.in');

        $this->self->uri($expect);

        $actual = $this->self->make()->uri();

        $this->assertEquals($expect, $actual);
    }

    /**
     * @return void
     */
    protected function doSetUp()
    {
        /** @var array<string, mixed> */
        $server = $_SERVER;

        $server['REQUEST_METHOD'] = 'GET';

        $server['REQUEST_URI'] = '/';

        $server['SERVER_NAME'] = 'roug.in';

        $server['SERVER_PORT'] = 8000;

        $server['HTTPS'] = 'off';

        $_FILES['file']['error'] = 0;

        $file = __DIR__ . '/../../Fixture/Views/LoremIpsum.php';

        $_FILES['file']['name'] = basename($file);

        $_FILES['file']['tmp_name'] = $file;

        $request = new Request('GET', '/', $_SERVER);

        $factory = (new RequestFactory)->setRequest($request);

        $factory->server($_SERVER);

        $this->self = $factory;
    }
}
