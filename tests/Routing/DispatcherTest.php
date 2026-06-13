<?php

namespace Zapheus\Routing;

use Zapheus\Container\ReflectionContainer;
use Zapheus\Fixture\Http\Controllers\HailController;
use Zapheus\Testcase;

/**
 * @package Zapheus
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class DispatcherTest extends Testcase
{
    /**
     * @var \Zapheus\Contract\Routing\Dispatcher
     */
    protected $self;

    /**
     * @return void
     */
    public function test_failed_if_route_is_not_found()
    {
        $this->doExpectException('UnexpectedValueException');

        $this->self->dispatch('GET', '/404');
    }

    /**
     * @return void
     */
    public function test_passed_if_dispatched_with_array_handler()
    {
        $expect = 'Hello, world';

        $route = $this->self->dispatch('GET', 'test/wow');

        $resolver = new Resolver(new ReflectionContainer);

        $actual = $resolver->resolve($route);

        $this->assertEquals($expect, $actual);
    }

    /**
     * @return void
     */
    public function test_passed_if_dispatched_with_closure()
    {
        $expect = 'Hello everyone! I am Royce';

        $route = $this->self->dispatch('GET', 'test/Royce');

        $resolver = new Resolver(new ReflectionContainer);

        $actual = $resolver->resolve($route);

        $this->assertEquals($expect, $actual);
    }

    /**
     * @return void
     */
    public function test_passed_if_dispatched_with_default_value()
    {
        $expect = 'Hello, my name is Royce and this is a test.';

        $route = $this->self->dispatch('GET', 'helloo/Royce');

        $resolver = new Resolver(new ReflectionContainer);

        $actual = $resolver->resolve($route);

        $this->assertEquals($expect, $actual);
    }

    /**
     * @return void
     */
    public function test_passed_if_route_is_dispatched()
    {
        $expect = 'Hello, world';

        $route = $this->self->dispatch('GET', '/greeeeet');

        $resolver = new Resolver(new ReflectionContainer);

        $actual = $resolver->resolve($route);

        $this->assertEquals($expect, $actual);
    }

    /**
     * @return void
     */
    public function test_passed_if_similar_routes_are_matched()
    {
        $expect = 'Hello everyone! I am Royce';

        $route = $this->self->dispatch('GET', 'test/Royce');

        $resolver = new Resolver(new ReflectionContainer);

        $actual = $resolver->resolve($route);

        $this->assertEquals($expect, $actual);
    }

    /**
     * @return void
     */
    protected function doSetUp()
    {
        $hail = new HailController;

        $router = new Router;

        // Add route as a string ------------
        $route = get_class($hail) . '@greet';

        $router->get('/greeeeet', $route);
        // ----------------------------------

        // Add route as a callable ------------
        /** @var callable */
        $cb = array(get_class($hail), 'greet');

        $router->get('/test/wow', $cb);
        // ------------------------------------

        // Add route as an anonymous function ------
        $fn = function ($name = 'Doe')
        {
            $name = sprintf('my name is %s', $name);

            $text = 'Hello, $1 and this is a test.';

            return str_replace('$1', $name, $text);
        };

        $router->get('/helloo/{name}', $fn);
        // -----------------------------------------

        // Add route as a simple callable ---------
        $fn = function ($name)
        {
            return 'Hello everyone! I am ' . $name;
        };

        $router->get('/test/{name}', $fn);
        // ----------------------------------------

        $this->self = new Dispatcher($router);
    }
}
