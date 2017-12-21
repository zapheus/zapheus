<?php

namespace Zapheus\Provider;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Zapheus\Container\WritableInterface;

/**
 * Silex Provider
 *
 * @package Zapheus
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class SilexProvider implements ProviderInterface
{
    /**
     * @var string
     */
    protected $container = 'Pimple\Container';

    /**
     * @var \Pimple\ServiceProviderInterface
     */
    protected $provider;

    /**
     * Initializes the provider instance.
     *
     * @param \Pimple\ServiceProviderInterface $provider
     */
    public function __construct(ServiceProviderInterface $provider)
    {
        $this->provider = $provider;
    }

    /**
     * Registers the bindings in the container.
     *
     * @param  \Zapheus\Container\WritableInterface $container
     * @return \Zapheus\Container\ContainerInterface
     */
    public function register(WritableInterface $container)
    {
        $pimple = $this->container($container);

        $this->provider->register($pimple);

        $container->set($this->container, $pimple);

        return $container;
    }

    /**
     * Returns a \Illuminate\Container\Container instance.
     *
     * @param  \Zapheus\Container\WritableInterface $container
     * @return \Illuminate\Container\Container
     */
    protected function container(WritableInterface $container)
    {
        if ($container->has($this->container) === false) {
            $config = $container->get(self::CONFIG);

            $pimple = $this->defaults(new Container);

            $silex = $config->get('silex', array(), true);

            foreach ($silex as $key => $value) {
                $exists = isset($pimple[$key]);

                $exists && $pimple[$key] = $value;
            }

            return $pimple;
        }

        return $container->get($this->container);
    }

    /**
     * Returns a Pimple container with default parameters.
     *
     * @param  \Pimple\Container $pimple
     * @return \Pimple\Container
     */
    protected function defaults(Container $pimple)
    {
        $pimple['request.http_port'] = 80;

        $pimple['request.https_port'] = 443;

        $pimple['debug'] = false;

        $pimple['charset'] = 'UTF-8';

        $pimple['logger'] = null;

        return $pimple;
    }
}
