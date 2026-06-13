<?php

namespace Zapheus\Http\Message;

use Zapheus\Http\Factory\Uri as UriFactory;
use Zapheus\Testcase;

/**
 * @package Zapheus
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class UriTest extends Testcase
{
    /**
     * @var \Zapheus\Http\Factory\Uri
     */
    protected $self;

    /**
     * @return void
     */
    public function test_passed_if_authority_is_retrieved()
    {
        $expect = 'me@roug.in:400';

        $actual = $this->self->make()->getAuthority();

        $this->assertEquals($expect, $actual);
    }

    /**
     * @return void
     */
    public function test_passed_if_fragment_is_retrieved()
    {
        $this->self->withFragment($expect = 'test');

        $actual = $this->self->make()->getFragment();

        $this->assertEquals($expect, $actual);
    }

    /**
     * @return void
     */
    public function test_passed_if_host_is_retrieved()
    {
        $expect = 'google.com';

        $this->self->withHost($expect);

        $actual = $this->self->make()->getHost();

        $this->assertEquals($expect, $actual);
    }

    /**
     * @return void
     */
    public function test_passed_if_path_is_retrieved()
    {
        $expect = '/test';

        $this->self->withPath($expect);

        $actual = $this->self->make()->getPath();

        $this->assertEquals($expect, $actual);
    }

    /**
     * @return void
     */
    public function test_passed_if_port_is_retrieved()
    {
        $this->self->withPort($expect = 500);

        $actual = $this->self->make()->getPort();

        $this->assertEquals($expect, $actual);
    }

    /**
     * @return void
     */
    public function test_passed_if_query_string_retrieved()
    {
        $expect = 'type=user';

        $this->self->withQuery($expect);

        $actual = $this->self->make()->getQuery();

        $this->assertEquals($expect, $actual);
    }

    /**
     * @return void
     */
    public function test_passed_if_scheme_is_retrieved()
    {
        $this->self->withScheme($expect = 'http');

        $actual = $this->self->make()->getScheme();

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

        $this->self->withUserInfo($expect);

        $actual = $this->self->make()->getUserInfo();

        $this->assertEquals($expect, $actual);
    }

    /**
     * @return void
     */
    public function test_passed_if_user_info_with_password()
    {
        $expect = 'username:password';

        $this->self->withUserInfo('username', 'password');

        $actual = $this->self->make()->getUserInfo();

        $this->assertEquals($expect, $actual);
    }

    /**
     * @return void
     */
    protected function doSetUp()
    {
        $this->self = new UriFactory;

        $url = 'https://me@roug.in:400/about';

        $this->self->setUri(new Uri($url));
    }
}
