<?php

namespace Zapheus;

use Zapheus\Container\Container;
use Zapheus\Contract\Container\Writable;
use Zapheus\Contract\Http\Message\Request;
use Zapheus\Contract\Http\Message\Response;
use Zapheus\Contract\Http\Server\Handler;
use Zapheus\Contract\Provider\Provider;
use Zapheus\Http\Server\RoutingHandler;
use Zapheus\Provider\Configuration;

/**
 * @package Zapheus
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class Application implements Handler, Writable
{
    const CONFIG = 'Zapheus\Contract\Provider\Configuration';

    const DISPATCHER = 'Zapheus\Contract\Routing\Dispatcher';

    const MIDDLEWARE = 'Zapheus\Contract\Http\Server\Dispatcher';

    const REQUEST = 'Zapheus\Contract\Http\Message\Request';

    const RESOLVER = 'Zapheus\Contract\Routing\Resolver';

    const RESPONSE = 'Zapheus\Contract\Http\Message\Response';

    const ROUTE_ATTRIBUTE = 'zapheus-route';

    const ROUTER = 'Zapheus\Contract\Routing\Router';

    /**
     * @var string[]
     */
    protected $providers = array();

    /**
     * @var \Zapheus\Contract\Container\Writable|null
     */
    protected $self = null;

    /**
     * Adds a new provider to be registered.
     *
     * @param \Zapheus\Contract\Provider\Provider $provider
     *
     * @return self
     */
    public function add(Provider $provider)
    {
        $self = $this->getContainer();

        $this->self = $provider->register($self);

        $this->providers[] = get_class($provider);

        return $this;
    }

    /**
     * Creates a new configuration based on given data.
     *
     * @param array<string, mixed>|string $data
     *
     * @return self
     */
    public function config($data)
    {
        // Force "$data" to become an array -------
        $items = is_array($data) ? $data : array();

        $config = new Configuration($items);
        // ----------------------------------------

        if (is_string($data))
        {
            $config->load($data);
        }

        return $this->set(self::CONFIG, $config);
    }

    /**
     * Emits the headers from the response instance.
     *
     * @param \Zapheus\Contract\Http\Message\Response $response
     *
     * @return \Zapheus\Contract\Http\Message\Response
     */
    public function emit(Response $response)
    {
        $code = $response->getStatusCode() . ' ' . $response->getReasonPhrase();

        $headers = $response->getHeaders();

        $version = $response->getProtocolVersion();

        foreach ($headers as $name => $values)
        {
            header($name . ': ' . implode(',', $values));
        }

        header(sprintf('HTTP/%s %s', $version, $code));

        return $response;
    }

    /**
     * Finds an entry of the container by its identifier and returns it.
     *
     * @param string $id
     *
     * @return mixed
     * @throws \Zapheus\Container\NotFoundException
     */
    public function get($id)
    {
        return $this->getContainer()->get($id);
    }

    /**
     * Returns the container instance, creating a default one if not set.
     *
     * @return \Zapheus\Contract\Container\Writable
     */
    public function getContainer()
    {
        if ($this->self)
        {
            return $this->self;
        }

        $container = new Container;

        if (! $container->has(Provider::CONFIG))
        {
            $config = new Configuration;

            $container->set(Provider::CONFIG, $config);
        }

        $this->self = $container;

        return $this->self;
    }

    /**
     * Dispatches the request and returns into a response.
     *
     * @param \Zapheus\Contract\Http\Message\Request $request
     *
     * @return \Zapheus\Contract\Http\Message\Response
     */
    public function handle(Request $request)
    {
        $handler = new RoutingHandler($this->getContainer());

        if (! $this->has(Application::MIDDLEWARE))
        {
            return $handler->handle($request);
        }

        /** @var \Zapheus\Contract\Http\Server\Dispatcher */
        $dispatch = $this->get(Application::MIDDLEWARE);

        return $dispatch->process($request, $handler);
    }

    /**
     * Returns true if the container can return an entry for the given identifier.
     *
     * @param string $id
     *
     * @return boolean
     */
    public function has($id)
    {
        return $this->getContainer()->has($id);
    }

    /**
     * Returns an array of registered providers.
     *
     * @return string[]
     */
    public function providers()
    {
        return $this->providers;
    }

    /**
     * Runs the application and returns the stream instance.
     *
     * @return \Zapheus\Contract\Http\Message\Stream
     */
    public function run()
    {
        $app = $this->getContainer();

        /** @var \Zapheus\Contract\Http\Message\Request */
        $request = $app->get(self::REQUEST);

        $response = $this->handle($request);

        return $this->emit($response)->getBody();
    }

    /**
     * Sets a new instance to the container.
     *
     * @param string $id
     * @param mixed  $concrete
     *
     * @return self
     * @throws \Zapheus\Container\ContainerException
     */
    public function set($id, $concrete)
    {
        $this->getContainer()->set($id, $concrete);

        return $this;
    }

    /**
     * Sets the container instance.
     *
     * @param \Zapheus\Contract\Container\Writable $container
     *
     * @return self
     */
    public function setContainer(Writable $container)
    {
        $this->self = $container;

        return $this;
    }
}
