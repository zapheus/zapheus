<?php

namespace Zapheus\Http;

use Zapheus\Application;
use Zapheus\Container\Container;
use Zapheus\Fixture\Http\Middlewares\JsonMiddleware;
use Zapheus\Fixture\Http\Middlewares\LastMiddleware;
use Zapheus\Provider\Configuration;
use Zapheus\Testcase;

/**
 * @package Zapheus
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class ServerProviderTest extends Testcase
{
    /**
     * @var \Zapheus\Contract\Container\Writable
     */
    protected $app;

    /**
     * @var \Zapheus\Contract\Provider\Provider
     */
    protected $self;

    /**
     * @return void
     */
    public function test_passed_if_middleware_is_registered()
    {
        $self = new MessageProvider;

        $app = $self->register($this->app);

        $app = $this->self->register($app);

        /** @var \Zapheus\Contract\Http\Server\Dispatcher */
        $dispatch = $app->get(Application::MIDDLEWARE);

        /** @var \Zapheus\Contract\Http\Message\Request */
        $request = $app->get(Application::REQUEST);

        $expect = array('application/json');

        $response = $dispatch->dispatch($request);

        $actual = $response->header('Content-Type');

        $this->assertEquals($expect, $actual);
    }

    /**
     * @return void
     */
    protected function doSetUp()
    {
        $config = new Configuration;

        $app = new Container;

        $items = array(new JsonMiddleware, new LastMiddleware);

        $config->set('app.middlewares', $items);

        $server = array('SERVER_PORT' => 8000);

        $server['REQUEST_METHOD'] = 'GET';

        $server['REQUEST_URI'] = '/';

        $server['SERVER_NAME'] = 'roug.in';

        $config->set('app.http.server', $server);

        $app->set(ServerProvider::CONFIG, $config);

        $this->app = $app;

        $this->self = new ServerProvider;
    }
}
