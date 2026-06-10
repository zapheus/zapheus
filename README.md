# Zapheus

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]][link-license]
[![Build Status][ico-build]][link-build]
[![Coverage Status][ico-coverage]][link-coverage]
[![Total Downloads][ico-downloads]][link-downloads]

Inspired from PHP frameworks of all shape and sizes, Zapheus is a web application framework with a goal to be easy to use, educational, and fully extensible to the core. Whether a simple API project or a full enterprise application, Zapheus will try to adapt it according to developer's needs.

``` php
// Displays a "Hello World" text.

require 'vendor/autoload.php';

// Initializes the router application
$app = new Zapheus\Coordinator;

// Creates a HTTP route of GET /
$app->get('/', function ()
{
    return 'Hello world!';
});

// Handles the server request
echo $app->run();
```

## Installation

Install the `Zapheus` package via [Composer](https://getcomposer.org/):

``` bash
$ composer require zapheus/zapheus
```

## Basic usage

### Using `Coordinator`

``` php
require 'vendor/autoload.php';

// Initializes the router application
$app = new Zapheus\Coordinator;

// Creates a HTTP route of GET /
$app->get('/', function ()
{
    return 'Hello world!';
});

// Handles the server request
echo $app->run();
```

### Using `Middlelayer`

``` php
require 'vendor/autoload.php';

// Initializes the middleware application
$app = new Zapheus\Middlelayer;

// Initializes the router instance
$router = new Zapheus\Routing\Router;

// Creates a HTTP route of GET /
$router->get('/', function ()
{
    return 'Hello world!';
});

// Pipes the router middleware into the application
$app->pipe(function ($request, $next) use ($router)
{
    // Returns the request attribute value for a route
    $attribute = Zapheus\Application::ROUTE_ATTRIBUTE;

    // Returns the path from the URI instance
    $path = $request->uri()->path();

    // Returns the current HTTP method from the $_SERVER
    $method = $request->method();

    // Creates a new Routing\DispatcherInterface instance
    $dispatcher = new Zapheus\Routing\Dispatcher($router);

    // Dispatches the router against the current request
    $route = $dispatcher->dispatch($method, $path);

    // Sets the route attribute into the request in order to be
    // called inside the Application instance and return the response.
    $request = $request->push('attributes', $route, $attribute);

    // Go to the next middleware, if there are any
    return $next->handle($request);
});

// Handles the server request
echo $app->run();
```

### Run the application using PHP's built-in web server:

``` bash
$ php -S localhost:8000
```

Open your web browser and go to [http://localhost:8000](http://localhost:8000).

## Features

### No dependencies

Zapheus takes no dependencies from other frameworks or libraries. Each component is built with inspiration from the existing popular web frameworks to give developers the best development experience.

### Extensible

All of Zapheus' classes have their own easy to understand interfaces. It enables developers to extend or optimize the core functionalities easily.

### Interoperable

Even though Zapheus doesn't have dependencies from other libraries, it does have [bridge packages](https://github.com/zapheus?utf8=%E2%9C%93&q=bridge) to integrate your chosen libraries easily within the framework. These include the [PHP Standards Recommendations](https://www.php-fig.org/psr/) (PSR) like [PSR-07](https://github.com/zapheus/psr-07-bridge) and [PSR-11](https://github.com/zapheus/psr-11-bridge).

``` php
// Converts a Zend Diactoros instance (a PSR-07 implementation)
// into a Zapheus HTTP request using the PSR-07 Bridge package

use Zapheus\Bridge\Psr\Zapheus\Request;
use Zend\Diactoros\ServerRequestFactory;

// Psr\Http\Message\ServerRequestInterface
$psr = ServerRequestFactory::fromGlobals();

// Zapheus\Http\Message\RequestInterface
$request = new Request($psr);
```

### Framework-friendly

Frameworks like Laravel and Symfony have their way of integrating packages into their own ecosystem. With that, Zapheus will try to get them both work in the same application in order for the developers to utilize framework-specific packages to their arsenal.

``` php
// Registers a third-party Laravel Service Provider
// into Zapheus using the Illuminate Bridge package

use Acme\Providers\AuthServiceProvider;
use Acme\Providers\RoleServiceProvider;
use Illuminate\Container\Container;
use Zapheus\Bridge\Illuminate\IlluminateProvider;
use Zapheus\Container\Container as ZapheusContainer;

// A collection of Laravel Service Providers
$providers = array(AuthServiceProvider::class, RoleServiceProvider::class);

// Include the providers in the bridge instance
$provider = new IlluminateProvider($providers);

// Registers the bindings into a container
$container = $provider->register(new ZapheusContainer);

// Returns the Illuminate\Container\Container
$laravel = $container->get(Container::class);
```

## Changelog

Please see [CHANGELOG][link-changelog] for more recent changes and latest updates.

## Contributing

See [CONTRIBUTING][link-contributing] on how to contribute to the project.

## License

The MIT License (MIT). Please see [LICENSE][link-license] for more information.

[ico-build]: https://img.shields.io/github/actions/workflow/status/zapheus/zapheus/build.yml?style=flat-square
[ico-coverage]: https://img.shields.io/codecov/c/github/zapheus/zapheus?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/zapheus/zapheus.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-version]: https://img.shields.io/packagist/v/zapheus/zapheus.svg?style=flat-square

[link-build]: https://github.com/zapheus/zapheus/actions
[link-changelog]: https://github.com/zapheus/zapheus/blob/master/CHANGELOG.md
[link-contributing]: https://github.com/zapheus/zapheus/blob/master/CONTRIBUTING.md
[link-contributors]: https://github.com/zapheus/zapheus/contributors
[link-coverage]: https://app.codecov.io/gh/zapheus/zapheus
[link-downloads]: https://packagist.org/packages/zapheus/zapheus
[link-license]: https://github.com/zapheus/zapheus/blob/master/LICENSE.md
[link-packagist]: https://packagist.org/packages/zapheus/zapheus
