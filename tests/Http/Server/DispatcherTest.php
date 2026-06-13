<?php

namespace Zapheus\Http\Server;

use Zapheus\Fixture\Http\Middlewares\LastMiddleware;
use Zapheus\Fixture\Http\Middlewares\JsonMiddleware;
use Zapheus\Http\Message\RequestFactory;
use Zapheus\Http\Message\ResponseFactory;
use Zapheus\Http\Message\Stream;
use Zapheus\Testcase;

/**
 * @package Zapheus
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class DispatcherTest extends Testcase
{
    /**
     * @var \Zapheus\Contract\Http\Message\Request
     */
    protected $request;

    /**
     * @var \Zapheus\Contract\Http\Server\Dispatcher
     */
    protected $self;

    /**
     * @return void
     */
    public function test_failed_if_no_response_returned()
    {
        $this->doExpectException('LogicException');

        $this->self->dispatch($this->request);
    }

    /**
     * @return void
     */
    public function test_passed_if_closure_middleware_dispatches()
    {
        $this->self->pipe(function ($request, $next)
        {
            $response = $next($request);

            $stream = new Stream(fopen('php://temp', 'r+'));

            $stream->write($response->stream() . ' world');

            $factory = (new ResponseFactory)->setResponse($response);

            return $factory->stream($stream)->make();
        });

        $this->self->pipe(function ($request, $next)
        {
            $response = $next($request);

            $response->stream()->write('Hello');

            return $response;
        });

        $this->self->pipe(new LastMiddleware);

        $expect = 'Hello world';

        $response = $this->self->dispatch($this->request);

        $actual = (string) $response->stream();

        $this->assertEquals($expect, $actual);
    }

    /**
     * @return void
     */
    public function test_passed_if_middleware_dispatches()
    {
        $this->self->pipe(new LastMiddleware);

        $expect = array('application/json');

        $response = $this->self->dispatch($this->request);

        $actual = $response->header('Content-Type');

        $this->assertEquals($expect, $actual);
    }

    /**
     * @return void
     */
    public function test_passed_if_string_middleware_dispatches()
    {
        $this->self->pipe('Zapheus\Fixture\Http\Middlewares\LastMiddleware');

        $expect = array('application/json');

        $response = $this->self->dispatch($this->request);

        $actual = $response->header('Content-Type');

        $this->assertEquals($expect, $actual);
    }

    /**
     * @return void
     */
    protected function doSetUp()
    {
        $items  = array();
        $server = array();

        $items[] = new JsonMiddleware;

        $this->self = new Dispatcher($items);

        $server['REQUEST_METHOD'] = 'GET';
        $server['REQUEST_URI'] = '/';
        $server['SERVER_NAME'] = 'roug.in';
        $server['SERVER_PORT'] = 8000;

        $factory = new RequestFactory;

        $factory->server($server);

        $this->request = $factory->make();
    }
}
