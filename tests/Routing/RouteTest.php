<?php

namespace Zapheus\Routing;

use Zapheus\Testcase;

/**
 * Route Test
 *
 * @package Zapheus
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class RouteTest extends Testcase
{
    /**
     * @var \Zapheus\Routing\Route
     */
    protected $route;

    /**
     * Tests Route::uri.
     *
     * @return void
     */
    public function testUriMethod()
    {
        $expected = (string) '/test';

        $result = $this->route->uri();

        $this->assertEquals($expected, $result);
    }

    /**
     * Sets up the route instance.
     *
     * @return void
     */
    protected function doSetUp()
    {
        $this->route = new Route('GET', '/test', 'HailController@greet');
    }
}
