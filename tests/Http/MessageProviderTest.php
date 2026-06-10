<?php

namespace Zapheus\Http;

use Zapheus\Application;
use Zapheus\Container\Container;
use Zapheus\Provider\Configuration;
use Zapheus\Testcase;

/**
 * Message Provider Test
 *
 * @package Zapheus
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class MessageProviderTest extends Testcase
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
     * Tests ProviderInterface::register.
     *
     * @return void
     */
    public function testRegisterMethod()
    {
        $container = $this->provider->register($this->container);

        $request = $container->get(Application::REQUEST);

        $expected = 'roug.in';

        $server = $request->server();

        $result = $server['SERVER_NAME'];

        $this->assertEquals($expected, $result);
    }

    /**
     * Sets up the provider instance.
     *
     * @return void
     */
    protected function doSetUp()
    {
        list($config, $container) = array(new Configuration, new Container);

        $server = array();

        $server['REQUEST_METHOD'] = 'GET';
        $server['REQUEST_URI'] = '/';
        $server['SERVER_NAME'] = 'roug.in';
        $server['SERVER_PORT'] = 8000;

        $config->set('app.http.server', $server);

        $container->set(MessageProvider::CONFIG, $config);

        $this->container = $container;

        $this->provider = new MessageProvider;
    }
}
