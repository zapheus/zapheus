<?php

namespace Zapheus\Http;

use Zapheus\Application;
use Zapheus\Container\WritableInterface;
use Zapheus\Http\Message\FileFactory;
use Zapheus\Http\Message\RequestFactory;
use Zapheus\Http\Message\Response;
use Zapheus\Provider\ProviderInterface;

/**
 * Message Provider
 *
 * @package Zapheus
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class MessageProvider implements ProviderInterface
{
    const REQUEST = Application::REQUEST;

    const RESPONSE = Application::RESPONSE;

    /**
     * Registers the bindings in the container.
     *
     * @param \Zapheus\Container\WritableInterface $container
     *
     * @return \Zapheus\Container\ContainerInterface
     */
    public function register(WritableInterface $container)
    {
        $factory = new RequestFactory;

        list($file, $response) = array(new FileFactory, new Response);

        $config = $container->get(Application::CONFIG);

        $files = $config->get('app.http.uploaded', (array) $_FILES);

        $factory->cookies($config->get('app.http.cookies', $_COOKIE));

        $factory->data($config->get('app.http.post', $_POST));

        $factory->files($file->normalize((array) $files));

        $factory->queries($config->get('app.http.get', $_GET));

        $factory->server($config->get('app.http.server', $_SERVER));

        $container->set(self::REQUEST, $factory->make());

        return $container->set(Application::RESPONSE, $response);
    }
}
