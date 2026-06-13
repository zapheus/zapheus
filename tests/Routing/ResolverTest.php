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
    public function test_passed_if_class_parameter_resolved_from_container()
    {
        $name = 'Zapheus\Fixture\Http\Controllers\HailController';

        $container = new Container;

        $container->set($name, new HailController);

        $laud = 'Zapheus\Fixture\Http\Controllers\LaudController';

        $container->set($laud, new LaudController(new HailController));

        $factory = new RouteFactory;

        /** @var callable */
        $cb = array($laud, 'greet');

        $factory->handler($cb);

        $factory->method('GET');

        $factory->uri('/test');

        $route = $factory->make();

        $resolver = new Resolver($container);

        $expect = 'Hello, world and people';

        $actual = $resolver->resolve($route);

        $this->assertEquals($expect, $actual);
    }

    /**
     * @return void
     */
    public function test_passed_if_class_resolved_from_reflection()
    {
        $factory = new RouteFactory;

        $factory->uri('/test');

        $factory->method('GET');

        $factory->handler('Zapheus\Fixture\Http\Controllers\HailController@greet');

        $route = $factory->make();

        $container = new Container;

        $resolver = new Resolver($container);

        $expect = 'Hello, world';

        $actual = $resolver->resolve($route);

        $this->assertEquals($expect, $actual);
    }

    /**
     * @return void
     */
    public function test_passed_if_class_type_parameter_resolved()
    {
        $factory = new RouteFactory;

        $factory->uri('/test');

        $factory->method('GET');

        $hail = 'Zapheus\Fixture\Http\Controllers\HailController';

        $laud = 'Zapheus\Fixture\Http\Controllers\LaudController';

        /** @var callable */
        $cb = array($laud, 'greet');

        $factory->handler($cb);

        $route = $factory->make();

        $container = new Container;

        $container->set($hail, new HailController);

        $container->set($laud, new LaudController(new HailController));

        $resolver = new Resolver($container);

        $expect = 'Hello, world and people';

        $actual = $resolver->resolve($route);

        $this->assertEquals($expect, $actual);
    }

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
