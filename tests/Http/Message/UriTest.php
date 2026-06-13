<?php

namespace Zapheus\Http\Message;

use Zapheus\Testcase;

/**
 * @package Zapheus
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class UriTest extends Testcase
{
    /**
     * @var \Zapheus\Http\Message\UriFactory
     */
    protected $self;

    /**
     * @return void
     */
    public function test_passed_if_authority_is_retrieved()
    {
        $expect = 'me@roug.in:400';

        $actual = $this->self->make()->authority();

        $this->assertEquals($expect, $actual);
    }

    /**
     * @return void
     */
    public function test_passed_if_fragment_is_retrieved()
    {
        $this->self->fragment($expect = 'test');

        $actual = $this->self->make()->fragment();

        $this->assertEquals($expect, $actual);
    }

    /**
     * @return void
     */
    public function test_passed_if_host_is_retrieved()
    {
        $expect = 'google.com';

        $this->self->host($expect);

        $actual = $this->self->make()->host();

        $this->assertEquals($expect, $actual);
    }

    /**
     * @return void
     */
    public function test_passed_if_path_is_retrieved()
    {
        $expect = '/test';

        $this->self->path($expect);

        $actual = $this->self->make()->path();

        $this->assertEquals($expect, $actual);
    }

    /**
     * @return void
     */
    public function test_passed_if_port_is_retrieved()
    {
        $expect = 500;

        $this->self->port($expect = 500);

        $actual = $this->self->make()->port();

        $this->assertEquals($expect, $actual);
    }

    /**
     * @return void
     */
    public function test_passed_if_query_string_retrieved()
    {
        $expect = 'type=user';

        $this->self->query($expect);

        $actual = $this->self->make()->query();

        $this->assertEquals($expect, $actual);
    }

    /**
     * @return void
     */
    public function test_passed_if_scheme_is_retrieved()
    {
        $this->self->scheme($expect = 'http');

        $actual = $this->self->make()->scheme();

        $this->assertEquals('http', $actual);
    }

    /**
     * @return void
     */
    public function test_passed_if_to_string_returns_uri()
    {
        $expect = 'https://me@roug.in:400/about';

        $actual = $this->self->make();

        $this->assertEquals($expect, $actual);
    }

    /**
     * @return void
     */
    public function test_passed_if_user_info_retrieved()
    {
        $expect = 'username';

        $this->self->user($expect);

        $actual = $this->self->make()->user();

        $this->assertEquals($expect, $actual);
    }

    /**
     * @return void
     */
    public function test_passed_if_user_info_with_password()
    {
        $expect = 'username:password';

        $this->self->user('username', 'password');

        $actual = $this->self->make()->user();

        $this->assertEquals($expect, $actual);
    }

    /**
     * @return void
     */
    protected function doSetUp()
    {
        $this->self = new UriFactory; // to pass null

        $url = 'https://me@roug.in:400/about';

        $this->self->setUri(new Uri($url));
    }
}
