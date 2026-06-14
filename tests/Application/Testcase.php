<?php

namespace Zapheus\Application;

use Zapheus\Application;
use Zapheus\Http\Factory\Request;
use Zapheus\Testcase as Zapheus;

/**
 * @package Zapheus
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
abstract class Testcase extends Zapheus
{
    /**
     * @var \Zapheus\Application
     */
    protected $self;

    /**
     * @param object $instance
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
     * @param string $method
     * @param string $uri
     *
     * @return \Zapheus\Application
     */
    protected function request($method, $uri)
    {
        $interface = Application::REQUEST;

        // Simulate data as $_SERVER ----------
        $server = array('REQUEST_URI' => $uri);

        $server['REQUEST_METHOD'] = $method;

        $server['SERVER_NAME'] = 'roug.in';

        $server['SERVER_PORT'] = '8000';
        // ------------------------------------

        $factory = new Request;

        $factory->withServerParams($server);

        $request = $factory->make();

        $this->self->set($interface, $request);

        return $this->self;
    }
}
