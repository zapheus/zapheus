# Upgrading Guide

Below are the significant changes when upgrading from specified versions due to backward compatibility breaks.

## From `v0.1.0` to `v0.2.0`

### Removed `Coordinator` and `Middlelayer`

The `Coordinator` and `Middlelayer` classes have been removed. Use `Application` instead with `RoutingProvider` or `ServerProvider`:

``` diff
+use Zapheus\Routing\Router;
+use Zapheus\Routing\RoutingProvider;

-$app = new Coordinator;
+$app = new Application;

+$router = new Router;

-$app->get('/', function ()
+$router->get('/', function ()
 {
     return 'Hello world!';
 });

+$app->add(new RoutingProvider($router));
```

``` diff
+use Zapheus\Http\ServerProvider;

-$app = new Middlelayer;
+$app = new Application;

+$middlewares = array();

-$app->pipe(function ($request, $next) use ($router)
+$middlewares[] = function ($request, $next) use ($router)
 {
     // router middleware logic
     return $next->handle($request);
 });

+$app->add(new ServerProvider($middlewares));
```

### Removed `Ropebridge` and `Mutator`

The `Ropebridge` and `Mutator` / `MutatorInterface` classes have been removed. Use `Application::set` and `Http\Factory\*` instead:

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

### API changes to `Application`

`Application` now implements `Handler` and `Writable`. The `run` method returns a `Stream` instead of a string, and `getContainer` is now public.

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

### Moved interfaces to `Contract`

All interfaces have been moved to the `Zapheus\Contract\*` namespace and the `Interface` suffix has been dropped:

``` diff
-use Zapheus\Routing\RouteInterface;
+use Zapheus\Contract\Routing\Route;

-class MyRoute implements RouteInterface
+class MyRoute implements Route
```

| Before | After |
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

Contract interfaces have been updated to follow [PSR-07 (HTTP Message)](https://www.php-fig.org/psr/psr-7/) and [PSR-15 (HTTP Server)](https://www.php-fig.org/psr/psr-15/) method naming conventions. All getter methods now use the `get` prefix:

**Zapheus\Http\Message\File**

``` diff
 /**
  * Retrieve a stream representing the uploaded file.
  *
  * @return \Zapheus\Contract\Http\Message\Stream
  * @throws \RuntimeException
  */
-public function stream();
+public function getStream();

 /**
  * Move the uploaded file to a new location.
  *
  * @param string $targetPath
  *
  * @return void
  * @throws \InvalidArgumentException
  * @throws \RuntimeException
  */
-public function move($target);
+public function moveTo($targetPath);

 /**
  * Retrieve the file size.
  *
  * @return integer|null
  */
-public function size();
+public function getSize();

 /**
  * Retrieve the error associated with the uploaded file.
  *
  * @return integer
  */
-public function error();
+public function getError();

 /**
  * Retrieve the filename sent by the client.
  *
  * @return string|null
  */
-public function name();
+public function getClientFilename();

 /**
  * Retrieve the media type sent by the client.
  *
  * @return string|null
  */
-public function type();
+public function getClientMediaType();
```

**Zapheus\Http\Message\Message**

``` diff
 /**
  * Retrieves the HTTP protocol version as a string.
  *
  * @return string
  */
-public function version();
+public function getProtocolVersion();

 /**
  * Retrieves all message header values.
  *
  * @return array<string, string[]>
  */
-public function headers();
+public function getHeaders();

 /**
  * Retrieves a message header value by the given case-insensitive name.
  *
  * @param string $name
  *
  * @return string[]
  */
-public function header($name);
+public function getHeader($name);

+/**
+ * Checks if a header exists by the given case-insensitive name.
+ *
+ * @param string $name
+ *
+ * @return boolean
+ */
+public function hasHeader($name);

+/**
+ * Retrieves a comma-separated string of the values for a single header.
+ *
+ * @param string $name
+ *
+ * @return string
+ */
+public function getHeaderLine($name);

 /**
  * Gets the body of the message.
  *
  * @return \Zapheus\Contract\Http\Message\Stream
  */
-public function stream();
+public function getBody();
```

**Zapheus\Http\Message\Request**

``` diff
 /**
  * Retrieves the message's request target.
  *
  * @return string
  */
-public function target();
+public function getRequestTarget();

 /**
  * Retrieves the HTTP method of the request.
  *
  * @return string
  */
-public function method();
+public function getMethod();

 /**
  * Retrieves the URI instance.
  *
  * @return \Zapheus\Contract\Http\Message\Uri
  */
-public function uri();
+public function getUri();

 /**
  * Retrieve server parameters.
  *
  * @return array<string, string>
  */
-public function server();
+public function getServerParams();

 /**
  * Retrieve cookies.
  *
  * @return array<string, string>
  */
-public function cookies();
+public function getCookieParams();

 /**
  * Retrieve query string arguments.
  *
  * @return array<string, mixed>
  */
-public function queries();
+public function getQueryParams();

 /**
  * Retrieve normalized file upload data.
  *
  * @return array<string, \Zapheus\Contract\Http\Message\File[]>
  */
-public function files();
+public function getUploadedFiles();

 /**
  * Retrieve any parameters provided in the request body.
  *
  * @return array<string, mixed>|object|null
  */
-public function data();
+public function getParsedBody();

 /**
  * Retrieve attributes derived from the request.
  *
  * @return array<string, mixed>
  */
-public function attributes();
+public function getAttributes();

 /**
  * Retrieve a single derived request attribute.
  *
  * @param string $name
  * @param mixed  $default
  *
  * @return mixed
  */
-public function attribute($name);
+public function getAttribute($name, $default = null);

-/**
- * Returns the cookie from the request.
- *
- * @param string $name
- *
- * @return string
- */
-public function cookie($name);

-/**
- * Returns the query from the request.
- *
- * @param string $name
- *
- * @return string
- */
-public function query($name);

-/**
- * Returns the server parameter from the request.
- *
- * @param string $name
- *
- * @return string
- */
-public function server($name);
```

**Zapheus\Http\Message\Response**

``` diff
 /**
  * Gets the response status code.
  *
  * @return integer
  */
-public function code();
+public function getStatusCode();

 /**
  * Gets the response reason phrase associated with the status code.
  *
  * @return string
  */
-public function reason();
+public function getReasonPhrase();
```

**Zapheus\Http\Message\Stream**

``` diff
 /**
  * Returns the remaining contents in a string.
  *
  * @return string
  */
-public function contents();
+public function getContents();
```

**Zapheus\Http\Message\Uri**

``` diff
 /**
  * Retrieve the scheme component of the URI.
  *
  * @return string
  */
-public function scheme();
+public function getScheme();

 /**
  * Retrieve the authority component of the URI.
  *
  * @return string
  */
-public function authority();
+public function getAuthority();

 /**
  * Retrieve the user information component of the URI.
  *
  * @return string
  */
-public function user();
+public function getUserInfo();

 /**
  * Retrieve the host component of the URI.
  *
  * @return string
  */
-public function host();
+public function getHost();

 /**
  * Retrieve the port component of the URI.
  *
  * @return integer|null
  */
-public function port();
+public function getPort();

 /**
  * Retrieve the path component of the URI.
  *
  * @return string
  */
-public function path();
+public function getPath();

 /**
  * Retrieve the query string of the URI.
  *
  * @return string
  */
-public function query();
+public function getQuery();

 /**
  * Retrieve the fragment component of the URI.
  *
  * @return string
  */
-public function fragment();
+public function getFragment();
```

### Removed implicitly nullable type hints

To ensure compatibility with PHP 5.3 and avoid [deprecation warnings](https://php.watch/versions/8.4/implicitly-marking-parameter-type-nullable-deprecated) in PHP 8.4, all implicitly nullable type hints (`TypeHint $param = null`) have been removed. Constructor parameters are now provided via setter methods:

`Zapheus\Application`

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

`Zapheus\Http\Message\Message`

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

`Zapheus\Http\Message\Response`

``` diff
-public function __construct($code = 200, array $headers = array(), Stream $stream = null, $version = '1.1')
+public function __construct($code = 200, array $headers = array(), $version = '1.1')
```

`Zapheus\Http\Message\Request`

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

`Zapheus\Http\Server\Dispatcher`

``` diff
-public function __construct(array $stack = array(), Container $container = null)
+public function __construct(array $stack = array())
```

Use the existing `setContainer` setter:

``` php
use Zapheus\Http\Server\Dispatcher;

$dispatch = new Dispatcher($stack);

$dispatch->setContainer($container);
```

`Zapheus\Routing\RoutingProvider`

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

`Zapheus\Http\Server\ErrorHandler`

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

### Removed `Route::result` method

The static `Route::result` method has been removed. Use `Dispatcher::dispatch` directly:

``` diff
-$result = $dispatcher->dispatch('GET', '/test/Zapheus');
-$route = Zapheus\Routing\Route::result($result, array('name' => 'Zapheus'));
+$route = $dispatcher->dispatch('GET', '/test/Zapheus');
```

Also use `RouteFactory` for building routes:

``` diff
+use Zapheus\Routing\RouteFactory;

-$route = new Route('GET', '/test/{name}', $handler, $middlewares, array('name' => 'Zapheus'));
+$factory = new RouteFactory;
+$route = $factory
+    ->setMethod('GET')
+    ->setUri('/test/{name}')
+    ->setHandler($handler)
+    ->setMiddlewares($middlewares)
+    ->make();
```

### Updated method in `Route`

`Route` getter methods have been updated to use the `get` prefix:

``` diff
 /**
  * Returns the handler.
  *
  * @return array<class-string, string>|callable|string
  */
-public function handler();
+public function getHandler();
```

``` diff
 /**
  * Returns the HTTP method.
  *
  * @return string
  */
-public function method();
+public function getMethod();
```

``` diff
 /**
  * Returns an array of middlewares.
  *
  * @return array<integer, \Zapheus\Contract\Http\Server\Middleware>
  */
-public function middlewares();
+public function getMiddlewares();
```

``` diff
 /**
  * Returns the parameters if any.
  *
  * @return array<string, string>
  */
-public function parameters();
+public function getParams();
```

``` diff
 /**
  * Returns a regular expression from URI.
  *
  * @return string
  */
-public function regex();
+public function getRegex();
```

``` diff
 /**
  * Returns the URI.
  *
  * @return string
  */
-public function uri();
+public function getUri();
```

### Removed `$base_namespace` in `Router`

The second constructor parameter (`$base_namespace`) has been removed:

``` diff
+use Zapheus\Routing\Router;

-$router = new Zapheus\Routing\Router(array(), 'Acme\\Controllers');
+$router = new Router;

+$route = 'Acme\\Controllers\\HomeController@index';
+$router->get('/', $route);
```

### `Dispatcher` now accepts `Router`

The constructor now accepts a `Router` instance instead of an array of `Route` objects:

``` diff
+use Zapheus\Routing\Router;
+use Zapheus\Routing\Dispatcher;

-$routes = array(new Route('GET', '/', $handler));
-$dispatcher = new Zapheus\Routing\Dispatcher($routes);
+$router = new Router;
+$router->get('/', $handler);
+$dispatch = new Dispatcher($router);
```

### API changes in `Resolver`

`Resolver::resolve` now reads parameters from `$route->getParams` directly. The second `$parameters` argument has been removed:

``` diff
+use Zapheus\Routing\Resolver;

 $resolver = new Resolver($container);

-$result = $resolver->resolve($route, array('name' => 'Zapheus'));
+$result = $resolver->resolve($route);
```

The `Resolver` now uses `Zapheus\Container\Parameter` internally for cross-version reflection compatibility across PHP 5.3–8.x.
