<?php

namespace Zapheus\Provider;

use Zapheus\Container\WritableInterface;

/**
 * Provider Interface
 *
 * @package Zapheus
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
interface ProviderInterface
{
    const CONFIG = 'Zapheus\Provider\ConfigurationInterface';

    /**
     * Registers the bindings in the container.
     *
     * @param \Zapheus\Container\WritableInterface $container
     *
     * @return \Zapheus\Container\ContainerInterface
     */
    public function register(WritableInterface $container);
}
