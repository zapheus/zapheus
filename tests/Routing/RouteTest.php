<?php

namespace Zapheus\Routing;

use Zapheus\Testcase;

/**
 * @package Zapheus
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class RouteTest extends Testcase
{
    /**
     * @var \Zapheus\Routing\Route
     */
    protected $self;

    /**
     * @return void
     */
    public function test_passed_if_route_uri_is_returned()
    {
        $expect = '/test';

        $actual = $this->self->getUri();

        $this->assertEquals($expect, $actual);
    }

    /**
     * @return void
     */
    protected function doSetUp()
    {
        $this->self = new Route('GET', '/test', 'HailController@greet');
    }
}
