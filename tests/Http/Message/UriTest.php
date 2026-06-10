<?php

namespace Zapheus\Http\Message;

use Zapheus\Testcase;

/**
 * URI Test
 *
 * @package Zapheus
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class UriTest extends Testcase
{
    /**
     * @var \Zapheus\Http\Message\UriFactory
     */
    protected $factory;

    /**
     * Sets up the URI instance.
     *
     * @return void
     */
    protected function doSetUp()
    {
        $this->factory = new UriFactory; // to pass null

        $url = 'https://me@roug.in:400/about';

        $this->factory = new UriFactory(new Uri($url));
    }

    /**
     * Tests UriInterface::__toString.
     *
     * @return void
     */
    public function testToStringMagicMethod()
    {
        $expected = 'https://me@roug.in:400/about';

        $result = (string) $this->factory->make();

        $this->assertEquals($expected, $result);
    }

    /**
     * Tests UriInterface::authority.
     *
     * @return void
     */
    public function testAuthorityMethod()
    {
        $expected = 'me@roug.in:400';

        $result = $this->factory->make()->authority();

        $this->assertEquals($expected, $result);
    }

    /**
     * Tests UriInterface::fragment.
     *
     * @return void
     */
    public function testFragmentMethod()
    {
        $this->factory->fragment($expected = 'test');

        $result = $this->factory->make()->fragment();

        $this->assertEquals($expected, $result);
    }

    /**
     * Tests UriInterface::host.
     *
     * @return void
     */
    public function testHostMethod()
    {
        $expected = 'google.com';

        $this->factory->host((string) $expected);

        $result = $this->factory->make()->host();

        $this->assertEquals($expected, $result);
    }

    /**
     * Tests UriInterface::path.
     *
     * @return void
     */
    public function testPathMethod()
    {
        $expected = '/test';

        $this->factory->path((string) $expected);

        $result = $this->factory->make()->path();

        $this->assertEquals($expected, $result);
    }

    /**
     * Tests UriInterface::port.
     *
     * @return void
     */
    public function testPortMethod()
    {
        $expected = 500;

        $this->factory->port($expected = 500);

        $result = $this->factory->make()->port();

        $this->assertEquals($expected, $result);
    }

    /**
     * Tests UriInterface::query.
     *
     * @return void
     */
    public function testQueryMethod()
    {
        $expected = 'type=user';

        $this->factory->query((string) $expected);

        $result = $this->factory->make()->query();

        $this->assertEquals($expected, $result);
    }

    /**
     * Tests UriInterface::scheme.
     *
     * @return void
     */
    public function testSchemeMethod()
    {
        $this->factory->scheme($expected = 'http');

        $result = $this->factory->make()->scheme();

        $this->assertEquals('http', $result);
    }

    /**
     * Tests UriInterface::user.
     *
     * @return void
     */
    public function testUserMethod()
    {
        $expected = 'username';

        $this->factory->user($expected);

        $result = $this->factory->make()->user();

        $this->assertEquals($expected, $result);
    }

    /**
     * Tests UriInterface::user with a password.
     *
     * @return void
     */
    public function testUserMethodWithPassword()
    {
        $expected = 'username:password';

        $this->factory->user('username', 'password');

        $result = $this->factory->make()->user();

        $this->assertEquals($expected, $result);
    }
}
