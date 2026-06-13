<?php

namespace Zapheus\Routing;

use Zapheus\Container\Container;
use Zapheus\Fixture\Http\Controllers\HailController;
use Zapheus\Fixture\Http\Controllers\LaudController;
use Zapheus\Fixture\Http\Controllers\TestController;
use Zapheus\Fixture\Http\Middlewares\LastMiddleware;
use Zapheus\Testcase;

/**
 * @package Zapheus
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class ResolverTest extends Testcase
{
    /**
     * @var \Zapheus\Contract\Routing\Resolver
     */
    protected $self;

    /**
     * @return void
     */
    public function test_passed_if_route_handler_is_resolved()
    {
        $factory = new RouteFactory;

        $factory->uri('/test');

        $instance = 'Zapheus\Fixture\Http\Controllers\TestController';

        $expect = 'Hello, world and people';

        $factory->method('GET');

        /** @var callable */
        $cb = array($instance, 'greet');

        $factory->handler($cb);

        $factory->middlewares(array(new LastMiddleware));

        $route = $factory->make();

        $actual = $this->self->resolve($route);

        $this->assertEquals($expect, $actual);
    }

    /**
     * @return void
     */
    protected function doSetUp()
    {
        $container = new Container;

        $laud = new LaudController(new HailController);

        $test = new TestController($laud);

        $container->set(get_class($test), $test);

        $this->self = new Resolver($container);
    }
}
