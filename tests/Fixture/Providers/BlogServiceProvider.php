<?php

namespace Slytherium\Fixture\Providers;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Slytherium\Fixture\Http\Controllers\BlogController;

/**
 * Blog Service Provider
 *
 * @package Slytherium
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class BlogServiceProvider implements ServiceProviderInterface
{
    /**
     * Registers services on the given container.
     *
     * @param \Pimple\Container $pimple
     */
    public function register(Container $pimple)
    {
        $blog = new BlogController($pimple['user']);

        $pimple['blog'] = $blog;
    }
}