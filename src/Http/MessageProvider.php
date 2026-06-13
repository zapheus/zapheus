<?php

namespace Zapheus\Http;

use Zapheus\Application;
use Zapheus\Contract\Container\Writable;
use Zapheus\Contract\Provider\Provider;
use Zapheus\Http\Message\FileFactory;
use Zapheus\Http\Message\RequestFactory;
use Zapheus\Http\Message\Response;

/**
 * @package Zapheus
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class MessageProvider implements Provider
{
    const REQUEST = Application::REQUEST;

    const RESPONSE = Application::RESPONSE;

    /**
     * Registers the bindings in the container.
     *
     * @param \Zapheus\Contract\Container\Writable $container
     *
     * @return \Zapheus\Contract\Container\Container
     */
    public function register(Writable $container)
    {
        $factory = new RequestFactory;

        $file     = new FileFactory;
        $response = new Response;

        $config = $container->get(Application::CONFIG);

        $files = $config->get('app.http.uploaded', $_FILES);

        $factory->cookies($config->get('app.http.cookies', $_COOKIE));

        $factory->data($config->get('app.http.post', $_POST));

        $factory->files($file->normalize($files));

        $factory->queries($config->get('app.http.get', $_GET));

        $factory->server($config->get('app.http.server', $_SERVER));

        $container->set(self::REQUEST, $factory->make());

        return $container->set(Application::RESPONSE, $response);
    }
}
