<?php

namespace Zapheus\Http\Server;

use Zapheus\Fixture\Http\Middlewares\LastMiddleware;
use Zapheus\Fixture\Http\Middlewares\JsonMiddleware;
use Zapheus\Http\Message\RequestFactory;
use Zapheus\Http\Message\ResponseFactory;
use Zapheus\Http\Message\Stream;
use Zapheus\Testcase;

/**
 * Dispatcher Test
 *
 * @package Zapheus
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class DispatcherTest extends Testcase
{
    /**
     * @var \Zapheus\Http\Server\DispatcherInterface
     */
    protected $dispatcher;

    /**
     * @var \Zapheus\Http\Message\RequestInterface
     */
    protected $request;

    /**
     * Tests DispatcherInterface::dispatch.
     *
     * @return void
     */
    public function testDispatchMethod()
    {
        $this->dispatcher->pipe(new LastMiddleware);

        $expected = array('application/json');

        $response = $this->dispatcher->dispatch($this->request);

        $result = $response->header('Content-Type');

        $this->assertEquals($expected, $result);
    }

    /**
     * Tests DispatcherInterface::dispatch with closures.
     *
     * @return void
     */
    public function testDispatchMethodWithClosures()
    {
        $this->dispatcher->pipe(function ($request, $next)
        {
            $response = $next($request);

            $stream = new Stream(fopen('php://temp', 'r+'));

            $stream->write($response->stream() . ' world');

            $factory = new ResponseFactory($response);

            return $factory->stream($stream)->make();
        });

        $this->dispatcher->pipe(function ($request, $next)
        {
            $response = $next($request);

            $response->stream()->write('Hello');

            return $response;
        });

        $this->dispatcher->pipe(new LastMiddleware);

        $expected = 'Hello world';

        $response = $this->dispatcher->dispatch($this->request);

        $result = (string) $response->stream();

        $this->assertEquals($expected, $result);
    }

    /**
     * Tests DispatcherInterface::dispatch with \LogicException.
     *
     * @return void
     */
    public function testDispatchMethodWithLogicException()
    {
        $this->doSetExpectedException('LogicException');

        $this->dispatcher->dispatch($this->request);
    }

    /**
     * Tests DispatcherInterface::dispatch with string.
     *
     * @return void
     */
    public function testDispatchMethodWithString()
    {
        $this->dispatcher->pipe('Zapheus\Fixture\Http\Middlewares\LastMiddleware');

        $expected = array('application/json');

        $response = $this->dispatcher->dispatch($this->request);

        $result = $response->header('Content-Type');

        $this->assertEquals($expected, $result);
    }

    /**
     * Sets up the dispatcher instance.
     *
     * @return void
     */
    protected function doSetUp()
    {
        list($items, $server) = array(array(), array());

        $items[] = new JsonMiddleware;

        $this->dispatcher = new Dispatcher($items);

        $server['REQUEST_METHOD'] = 'GET';
        $server['REQUEST_URI'] = '/';
        $server['SERVER_NAME'] = 'roug.in';
        $server['SERVER_PORT'] = 8000;

        $factory = new RequestFactory;

        $factory->server($server);

        $this->request = $factory->make();
    }
}
