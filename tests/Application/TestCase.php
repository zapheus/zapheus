<?php

namespace Zapheus\Application;

use Zapheus\Container\ReflectionContainer;
use Zapheus\Http\Message\ServerRequest;

/**
 * Application Test Case
 *
 * @package Zapheus
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class TestCase extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Zapheus\Application\ApplicationInterface
     */
    protected $application;

    /**
     * Sets up the application instance.
     *
     * @return void
     */
    public function setUp()
    {
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_SERVER['REQUEST_URI'] = '/';
        $_SERVER['SERVER_NAME'] = 'rougin.github.io';
        $_SERVER['SERVER_PORT'] = 8000;
    }

    /**
     * Creates a dummy server request instance.
     *
     * @param  string $method
     * @param  string $uri
     * @return \Zapheus\Application\ApplicationInterface
     */
    protected function request($method, $uri)
    {
        $interface = 'Zapheus\Http\Message\ServerRequestInterface';

        $_SERVER['REQUEST_METHOD'] = $method;
        $_SERVER['REQUEST_URI'] = $uri;
        $_SERVER['SERVER_NAME'] = 'rougin.github.io';
        $_SERVER['SERVER_PORT'] = 8000;

        $request = new ServerRequest($_SERVER);

        $this->application->delegate(new ReflectionContainer);

        $this->application->set($interface, $request);

        return $this->application;
    }
}
