<?php

namespace Zapheus\Http\Server;

use Zapheus\Fixture\Http\Middlewares\LastMiddleware as FixtureLastMiddleware;
use Zapheus\Fixture\Http\Middlewares\JsonMiddleware;
use Zapheus\Http\Factory\Request;
use Zapheus\Http\Factory\Response;
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
            /** @var \Zapheus\Contract\Http\Message\Response */
            $response = $next($request);

            /** @var resource */
            $file = fopen('php://temp', 'r+');

            $stream = new Stream($file);

            $stream->write($response->getBody() . ' world');

            $maker = new Response;

            $maker->setResponse($response);

            return $maker->withBody($stream)->make();
        });

        $this->self->pipe(function ($request, $next)
        {
            /** @var \Zapheus\Contract\Http\Message\Response */
            $response = $next($request);

            $response->getBody()->write('Hello');

            return $response;
        });

        $this->self->pipe(new FixtureLastMiddleware);

        $expect = 'Hello world';

        $response = $this->self->dispatch($this->request);

        $actual = $response->getBody();

        $this->assertEquals($expect, $actual);
    }

    /**
     * @return void
     */
    public function test_passed_if_middleware_dispatches()
    {
        $this->self->pipe(new FixtureLastMiddleware);

        $expect = array('application/json');

        $response = $this->self->dispatch($this->request);

        $actual = $response->getHeader('Content-Type');

        $this->assertEquals($expect, $actual);
    }

    /**
     * @return void
     */
    public function test_passed_if_string_middleware_dispatches()
    {
        $last = 'Zapheus\Fixture\Http\Middlewares\LastMiddleware';

        $this->self->pipe($last);

        $expect = array('application/json');

        $response = $this->self->dispatch($this->request);

        $actual = $response->getHeader('Content-Type');

        $this->assertEquals($expect, $actual);
    }

    /**
     * @return void
     */
    protected function doSetUp()
    {
        $items = array(new JsonMiddleware);

        $this->self = new Dispatcher($items);

        // Simulate data as $_SERVER ---------
        $server = array('REQUEST_URI' => '/');

        $server['REQUEST_METHOD'] = 'GET';

        $server['SERVER_NAME'] = 'roug.in';

        $server['SERVER_PORT'] = '8000';
        // -----------------------------------

        $factory = new Request;

        $factory->withServerParams($server);

        $this->request = $factory->make();
    }
}
