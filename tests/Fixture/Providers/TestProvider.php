<?php

namespace Zapheus\Fixture\Providers;

use Zapheus\Contract\Container\Writable;
use Zapheus\Contract\Provider\Provider;

/**
 * @package Zapheus
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class TestProvider implements Provider
{
    /**
     * @param \Zapheus\Contract\Container\Writable $container
     *
     * @return \Zapheus\Contract\Container\Container
     */
    public function register(Writable $container)
    {
        return $container;
    }
}
