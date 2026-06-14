<?php

namespace Zapheus\Http;

use Zapheus\Application;
use Zapheus\Container\Container;
use Zapheus\Provider\Configuration;
use Zapheus\Testcase;

/**
 * @package Zapheus
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class MessageProviderTest extends Testcase
{
    /**
     * @var \Zapheus\Contract\Container\Writable
     */
    protected $container;

    /**
     * @var \Zapheus\Contract\Provider\Provider
     */
    protected $self;

    /**
     * @return void
     */
    public function test_passed_if_request_is_registered()
    {
        $container = $this->self->register($this->container);

        /** @var \Zapheus\Contract\Http\Message\Request */
        $request = $container->get(Application::REQUEST);

        $expect = 'roug.in';

        $server = $request->getServerParams();

        $actual = $server['SERVER_NAME'];

        $this->assertEquals($expect, $actual);
    }

    /**
     * @return void
     */
    protected function doSetUp()
    {
        $config    = new Configuration;
        $container = new Container;

        $server = array();

        $server['REQUEST_METHOD'] = 'GET';
        $server['REQUEST_URI'] = '/';
        $server['SERVER_NAME'] = 'roug.in';
        $server['SERVER_PORT'] = 8000;

        $config->set('app.http.server', $server);

        $container->set(MessageProvider::CONFIG, $config);

        $this->container = $container;

        $this->self = new MessageProvider;
    }
}
