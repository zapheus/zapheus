<?php

namespace Zapheus\Application;

use Zapheus\Application;
use Zapheus\Http\Factory\Request;
use Zapheus\Testcase;

/**
 * @package Zapheus
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
abstract class AbstractTestCase extends Testcase
{
    /**
     * @var \Zapheus\Application
     */
    protected $self;

    /**
     * @return \Zapheus\Application
     */
    protected function application()
    {
        return new Application;
    }

    /**
     * @param mixed $instance
     *
     * @return false|string
     */
    protected function define($instance)
    {
        $class = get_class($instance);

        $this->self->set($class, $instance);

        return $class;
    }

    /**
     * @return void
     */
    protected function doSetUp()
    {
        $_SERVER['REQUEST_METHOD'] = 'GET';

        $_SERVER['REQUEST_URI'] = '/';

        $_SERVER['SERVER_NAME'] = 'roug.in';

        $_SERVER['SERVER_PORT'] = 8000;
    }

    /**
     * @param string $method
     * @param string $uri
     *
     * @return \Zapheus\Application
     */
    protected function request($method, $uri)
    {
        $interface = Application::REQUEST;

        $_SERVER['REQUEST_METHOD'] = $method;

        $_SERVER['REQUEST_URI'] = $uri;

        $_SERVER['SERVER_NAME'] = 'roug.in';

        $_SERVER['SERVER_PORT'] = 8000;

        $factory = new Request;

        $factory->withServerParams($_SERVER);

        $request = $factory->make();

        $this->self->set($interface, $request);

        return $this->self;
    }
}
