<?php

namespace Zapheus\Http;

use Zapheus\Application;
use Zapheus\Contract\Container\Writable;
use Zapheus\Contract\Provider\Provider;
use Zapheus\Http\Factory\File;
use Zapheus\Http\Factory\Request;
use Zapheus\Http\Factory\Response;

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
     * @return \Zapheus\Contract\Container\Writable
     */
    public function register(Writable $container)
    {
        $factory = new Request;

        $file = new File;

        $response = new Response;

        /** @var \Zapheus\Contract\Provider\Configuration */
        $config = $container->get(Application::CONFIG);

        /** @var array<string, array<string, string[]>> */
        $fileItems = $config->get('app.http.uploaded', $_FILES);

        $normalized = $file->normalize($fileItems);

        $factory->withUploadedFiles($normalized);

        /** @var array<string, string> */
        $cookieItems = $config->get('app.http.cookies', $_COOKIE);

        $factory->withCookieParams($cookieItems);

        /** @var array<string, mixed>|object|null */
        $parsed = $config->get('app.http.post', $_POST);

        $factory->withParsedBody($parsed);

        /** @var array<string, string> */
        $params = $config->get('app.http.get', $_GET);

        $factory->withQueryParams($params);

        /** @var array<string, string> */
        $server = $config->get('app.http.server', $_SERVER);

        $factory->withServerParams($server);

        $container->set(self::REQUEST, $factory->make());

        $class = Application::RESPONSE;

        return $container->set($class, $response->make());
    }
}
