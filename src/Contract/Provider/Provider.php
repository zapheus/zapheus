<?php

namespace Zapheus\Contract\Provider;

use Zapheus\Contract\Container\Writable;

/**
 * @package Zapheus
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
interface Provider
{
    const CONFIG = 'Zapheus\Contract\Provider\Configuration';

    /**
     * Registers the bindings in the container.
     *
     * @param \Zapheus\Contract\Container\Writable $container
     *
     * @return \Zapheus\Contract\Container\Writable
     */
    public function register(Writable $container);
}
