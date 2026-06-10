<?php

namespace Zapheus\Http;

use Zapheus\Application;
use Zapheus\Container\Container;
use Zapheus\Fixture\Http\Middlewares\JsonMiddleware;
use Zapheus\Fixture\Http\Middlewares\LastMiddleware;
use Zapheus\Provider\Configuration;
use Zapheus\Testcase;

/**
 * Server Provider Test
 *
 * @package Zapheus
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class ServerProviderTest extends Testcase
{
    /**
     * @var \Zapheus\Container\WritableInterface
     */
    protected $container;

    /**
     * @var \Zapheus\Provider\ProviderInterface
     */
    protected $provider;

    /**
     * Sets up the provider instance.
     *
     * @return void
     */
    protected function doSetUp()
    {
        list($config, $container) = array(new Configuration, new Container);

        $middlewares = array(new JsonMiddleware, new LastMiddleware);

        $config->set('app.middlewares', $middlewares);

        $server = array('SERVER_PORT' => 8000);

        $server['REQUEST_METHOD'] = 'GET';

        $server['REQUEST_URI'] = '/';

        $server['SERVER_NAME'] = 'roug.in';

        $config->set('app.http.server', $server);

        $container->set(ServerProvider::CONFIG, $config);

        $this->container = $container;

        $this->provider = new ServerProvider;
    }

    /**
     * Tests ProviderInterface::register.
     *
     * @return void
     */
    public function testRegisterMethod()
    {
        $message = new MessageProvider;

        $container = $message->register($this->container);

        $container = $this->provider->register($container);

        $dispatcher = $container->get(Application::MIDDLEWARE);

        $request = $container->get(Application::REQUEST);

        $expected = array('application/json');

        $response = $dispatcher->dispatch($request);

        $result = $response->header('Content-Type');

        $this->assertEquals($expected, $result);
    }
}
