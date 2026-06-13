# Upgrading Guide

Below are the significant changes when upgrading from specified versions due to backward compatibility breaks.

## From `v0.1.0` to `v0.2.0`

### Removal of `Coordinator` and `Middlelayer`

The `Coordinator` and `Middlelayer` classes have been removed. Use `Application` instead with `RoutingProvider` or `ServerProvider`:

``` diff
+use Zapheus\Routing\Router;
+use Zapheus\Routing\RoutingProvider;

-$app = new Coordinator;
+$app = new Application;

+$router = new Router;
+
-$app->get('/', function ()
+$router->get('/', function ()
 {
     return 'Hello world!';
 });
+
+$app->add(new RoutingProvider($router));
```

``` diff
+use Zapheus\Http\ServerProvider;

-$app = new Middlelayer;
+$app = new Application;

-$app->pipe(function ($request, $next) use ($router)
-{
-    // router middleware logic
-    return $next->handle($request);
-});
+$app->add(new ServerProvider($middlewares));
```

### Moved interfaces to `Contract`

All interfaces have been moved to the `Zapheus\Contract\*` namespace and the `Interface` suffix has been dropped:

``` diff
-use Zapheus\Routing\RouteInterface;
+use Zapheus\Contract\Routing\Route;

-class MyRoute implements RouteInterface
+class MyRoute implements Route
```

| v0.1.0 | v0.2.0 |
|---|---|
| `Zapheus\Container\ContainerInterface` | `Zapheus\Contract\Container\Container` |
| `Zapheus\Container\WritableInterface` | `Zapheus\Contract\Container\Writable` |
| `Zapheus\Provider\ProviderInterface` | `Zapheus\Contract\Provider\Provider` |
| `Zapheus\Provider\ConfigurationInterface` | `Zapheus\Contract\Provider\Configuration` |
| `Zapheus\Routing\RouterInterface` | `Zapheus\Contract\Routing\Router` |
| `Zapheus\Routing\RouteInterface` | `Zapheus\Contract\Routing\Route` |
| `Zapheus\Routing\ResolverInterface` | `Zapheus\Contract\Routing\Resolver` |
| `Zapheus\Routing\DispatcherInterface` | `Zapheus\Contract\Routing\Dispatcher` |
| `Zapheus\Renderer\RendererInterface` | `Zapheus\Contract\Renderer\Renderer` |
| `Zapheus\Http\Server\HandlerInterface` | `Zapheus\Contract\Http\Server\Handler` |
| `Zapheus\Http\Server\MiddlewareInterface` | `Zapheus\Contract\Http\Server\Middleware` |
| `Zapheus\Http\Server\DispatcherInterface` | `Zapheus\Contract\Http\Server\Dispatcher` |
| `Zapheus\Http\Message\MessageInterface` | `Zapheus\Contract\Http\Message\Message` |
| `Zapheus\Http\Message\RequestInterface` | `Zapheus\Contract\Http\Message\Request` |
| `Zapheus\Http\Message\ResponseInterface` | `Zapheus\Contract\Http\Message\Response` |
| `Zapheus\Http\Message\StreamInterface` | `Zapheus\Contract\Http\Message\Stream` |
| `Zapheus\Http\Message\UriInterface` | `Zapheus\Contract\Http\Message\Uri` |
| `Zapheus\Http\Message\FileInterface` | `Zapheus\Contract\Http\Message\File` |

### PSR-07/PSR-15 method renames

Contract interfaces have been updated to follow [PSR-07 (HTTP Message)](https://www.php-fig.org/psr/psr-7/) and [PSR-15 (HTTP Server)](https://www.php-fig.org/psr/psr-15/) method naming conventions. All getter methods now use the `get` prefix.

#### `Message` methods

| v0.1.0 | v0.2.0 |
|---|---|
| `$message->version()` | `$message->getProtocolVersion()` |
| `$message->headers()` | `$message->getHeaders()` |
| `$message->header($name)` | `$message->getHeader($name)` |
| — | `$message->hasHeader($name)` |
| — | `$message->getHeaderLine($name)` |
| `$message->stream()` | `$message->getBody()` |

#### `Request` methods

| v0.1.0 | v0.2.0 |
|---|---|
| `$request->method()` | `$request->getMethod()` |
| `$request->target()` | `$request->getRequestTarget()` |
| `$request->uri()` | `$request->getUri()` |
| `$request->server()` | `$request->getServerParams()` |
| `$request->cookies()` | `$request->getCookieParams()` |
| `$request->queries()` | `$request->getQueryParams()` |
| `$request->files()` | `$request->getUploadedFiles()` |
| `$request->data()` | `$request->getParsedBody()` |
| `$request->attributes()` | `$request->getAttributes()` |
| `$request->attribute($name)` | `$request->getAttribute($name, $default = null)` |
| `$request->cookie($name)` | REMOVED (use `getCookieParams()`) |
| `$request->query($name)` | REMOVED (use `getQueryParams()`) |
| `$request->server($name)` | REMOVED (use `getServerParams()` + array access) |

#### `Response` methods

| v0.1.0 | v0.2.0 |
|---|---|
| `$response->code()` | `$response->getStatusCode()` |
| `$response->reason()` | `$response->getReasonPhrase()` |

#### `Uri` methods

| v0.1.0 | v0.2.0 |
|---|---|
| `$uri->scheme()` | `$uri->getScheme()` |
| `$uri->authority()` | `$uri->getAuthority()` |
| `$uri->user()` | `$uri->getUserInfo()` |
| `$uri->host()` | `$uri->getHost()` |
| `$uri->port()` | `$uri->getPort()` |
| `$uri->path()` | `$uri->getPath()` |
| `$uri->query()` | `$uri->getQuery()` |
| `$uri->fragment()` | `$uri->getFragment()` |

#### `Stream` methods

| v0.1.0 | v0.2.0 |
|---|---|
| `$stream->contents()` | `$stream->getContents()` |

#### `File` methods

| v0.1.0 | v0.2.0 |
|---|---|
| `$file->error()` | `$file->getError()` |
| `$file->move($target)` | `$file->moveTo($targetPath)` |
| `$file->name()` | `$file->getClientFilename()` |
| `$file->size()` | `$file->getSize()` |
| `$file->stream()` | `$file->getStream()` |
| `$file->type()` | `$file->getClientMediaType()` |

### Removal of Implicitly Nullable Type Hints

To ensure compatibility with PHP 5.3 and avoid [deprecation warnings](https://php.watch/versions/8.4/implicitly-marking-parameter-type-nullable-deprecated) in PHP 8.4, all implicitly nullable type hints (`TypeHint $param = null`) have been removed. Constructor parameters are now provided via setter methods.

#### `Zapheus\Application`

``` diff
-public function __construct(Writable $container = null)
+/**
+ * Sets the container instance.
+ *
+ * @param \Zapheus\Contract\Container\Writable $container
+ *
+ * @return self
+ */
+public function setContainer(Writable $container)
```

#### `Zapheus\Http\Message\Message`

``` diff
-public function __construct(array $headers = array(), Stream $stream = null, $version = '1.1')
+public function __construct(array $headers = array(), $version = '1.1')

+/**
+ * Sets the stream instance.
+ *
+ * @param \Zapheus\Contract\Http\Message\Stream $stream
+ *
+ * @return self
+ */
+public function setStream(Stream $stream)
```

#### `Zapheus\Http\Message\Response`

``` diff
-public function __construct($code = 200, array $headers = array(), Stream $stream = null, $version = '1.1')
+public function __construct($code = 200, array $headers = array(), $version = '1.1')
```

#### `Zapheus\Http\Message\Request`

``` diff
-public function __construct($method, $target, array $server = array(), array $cookies = array(), $data = null, array $files = array(), array $queries = array(), array $attributes = array(), Uri $uri = null, array $headers = array(), Stream $stream = null, $version = '1.1')
+public function __construct($method, $target, array $server = array(), array $cookies = array(), $data = null, array $files = array(), array $queries = array(), array $attributes = array(), array $headers = array(), $version = '1.1')

+/**
+ * Sets the URI instance.
+ *
+ * @param \Zapheus\Contract\Http\Message\Uri $uri
+ *
+ * @return self
+ */
+public function setUri(Uri $uri)
```

#### `Zapheus\Http\Server\Dispatcher`

``` diff
-public function __construct(array $stack = array(), Container $container = null)
+public function __construct(array $stack = array())
```

Use the existing `container()` setter:

``` php
use Zapheus\Http\Server\Dispatcher;

$dispatcher = (new Dispatcher($stack))
    ->container($container);
```

#### `Zapheus\Routing\RoutingProvider`

``` diff
-public function __construct(Router $router = null)
+/**
+ * Sets the router instance.
+ *
+ * @param \Zapheus\Contract\Routing\Router $router
+ *
+ * @return self
+ */
+public function setRouter(Router $router)
```

#### `Zapheus\Http\Server\ErrorHandler`

``` diff
-public function __construct($message = null)
+/**
+ * Sets the error message.
+ *
+ * @param string $message
+ *
+ * @return self
+ */
+public function setMessage($message)
```

#### Factories with `with*` methods

Factory classes now use `with*` methods (PSR-07 convention) instead of short fluent setters. All factories support chaining via `set` method to load existing instances, followed by the `with` method to mutate state then use `make` method to build:

``` diff
+use Zapheus\Http\Factory\Request as RequestFactory;
+use Zapheus\Http\Factory\Response as ResponseFactory;
+use Zapheus\Http\Factory\Uri as UriFactory;
+use Zapheus\Routing\RouteFactory;

-$factory = new RouteFactory($route);
+$factory = new RouteFactory;
+$factory->setRoute($route);

-$factory = new RequestFactory($request);
+$factory = new RequestFactory;
+$factory->setRequest($request);

-$factory = new ResponseFactory($response);
+$factory = new ResponseFactory;
+$factory->setResponse($response);

-$factory = new UriFactory($uri);
+$factory = new UriFactory;
+$factory->setUri($uri);
```

Building from scratch with `with` method chaining:

``` diff
+use Zapheus\Http\Factory\Request as RequestFactory;

-$factory = new Zapheus\Http\Factory\Request;
-$factory->server($_SERVER);
-$factory->method('POST');
-$factory->target('/api');
-$factory->data(array('name' => 'Zapheus'));
-$request = $factory->make();

+$factory = new RequestFactory;
+$request = $factory
+    ->withServerParams($_SERVER)
+    ->withMethod('POST')
+    ->withRequestTarget('/api')
+    ->withParsedBody(array('name' => 'Zapheus'))
+    ->make();
```

``` diff
+use Zapheus\Http\Factory\Response as ResponseFactory;
+use Zapheus\Http\Message\Stream;

-$factory = new Zapheus\Http\Factory\Response;
-$factory->code(404);
-$factory->write('Not Found');
-$response = $factory->make();

+$factory = new ResponseFactory;
+$stream = new Stream(fopen('php://temp', 'r+'));
+$stream->write('Not Found');
+$response = $factory
+    ->withStatus(404)
+    ->withBody($stream)
+    ->make();
```

### Removal of `Ropebridge` and `Mutator`

The `Ropebridge` and `Mutator` / `MutatorInterface` classes have been removed. Use `Application::set()` and `Http\Factory\*` instead:

``` diff
+use Zapheus\Application;
+use Zapheus\Contract\Routing\Router as RouterContract;

-$bridge = new Zapheus\Ropebridge;
-$bridge->set('router', $router);
-
-$app = new Zapheus\Application;
-$app->rope($bridge);

+$app = new Application;
+
+$app->set(Application::ROUTER, $router);
```

``` diff
+use Zapheus\Http\Factory\Message as MessageFactory;

 $message = new Zapheus\Http\Message\Message;
-$mutator = new Mutator;
-
-$mutator->set('headers', array());
-$mutator->set('version', '1.1');
-$mutated = $mutator->with('stream', $stream);

+$factory = new MessageFactory;
+$message = $factory
+    ->withHeader('Content-Type', 'application/json')
+    ->withProtocolVersion('1.1')
+    ->withBody($stream)
+    ->make();
```

### Route and Dispatcher constructor changes

#### `Zapheus\Routing\Route`

The static `Route::result()` method has been removed. Use `Dispatcher::dispatch()` directly:

``` diff
-$result = $dispatcher->dispatch('GET', '/test/Zapheus');
-$route = Zapheus\Routing\Route::result($result, array('name' => 'Zapheus'));
+$route = $dispatcher->dispatch('GET', '/test/Zapheus');
```

Use `RouteFactory` for building routes:

``` diff
+use Zapheus\Routing\RouteFactory;

-$route = new Route('GET', '/test/{name}', $handler, $middlewares, array('name' => 'Zapheus'));
+$factory = new RouteFactory;
+$route = $factory
+    ->method('GET')
+    ->uri('/test/{name}')
+    ->handler($handler)
+    ->middlewares($middlewares)
+    ->make();
```

#### `Zapheus\Routing\Router`

The second constructor parameter (`$base_namespace`) has been removed:

``` diff
+use Zapheus\Routing\Router;

-$router = new Zapheus\Routing\Router(array(), 'Acme\\Controllers');
+$router = new Router;
+$router->get('/', 'Acme\\Controllers\\HomeController@index');
```

#### `Zapheus\Routing\Dispatcher`

The constructor now accepts a `Router` instance instead of an array of `Route` objects:

``` diff
+use Zapheus\Routing\Router;
+use Zapheus\Routing\Dispatcher;

-$routes = array(new Route('GET', '/', $handler));
-$dispatcher = new Zapheus\Routing\Dispatcher($routes);
+$router = new Router;
+$router->get('/', $handler);
+$dispatcher = new Dispatcher($router);
```

### Application API changes

`Application` now implements `Handler` and `Writable`. The `run()` method returns a `Stream` instead of a string, and `getContainer()` is now public.

``` diff
+use Zapheus\Contract\Http\Message\Response;

 public function emit(Response $response)
 {
-    $code = $response->code() . ' ' . $response->reason();
-    $version = $response->version();
+    $code = $response->getStatusCode() . ' ' . $response->getReasonPhrase();
+    $version = $response->getProtocolVersion();

     header(sprintf('HTTP/%s %s', $version, $code));
 }
```

``` diff
-// getContainer() was protected
+// getContainer() is now public
+$container = $app->getContainer();
```

``` diff
+// run() returns Stream, cast to string with __toString()
 echo $app->run();
+echo $app->run()->__toString();
```

### Resolver changes

`Resolver::resolve()` now reads parameters from `$route->parameters()` directly. The second `$parameters` argument has been removed:

``` diff
+use Zapheus\Routing\Resolver;

 $resolver = new Resolver($container);

-$result = $resolver->resolve($route, array('name' => 'Zapheus'));
+$result = $resolver->resolve($route);
```

The `Resolver` now uses `Zapheus\Container\Parameter` internally for cross-version reflection compatibility across PHP 5.3–8.x.
