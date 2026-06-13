# Upgrading Guide

Below are the significant changes when upgrading from specified versions due to backward compatibility breaks.

## From `v0.1.0` to `v0.2.0`

### Removal of `Coordinator` and `Middlelayer`

The `Coordinator` and `Middlelayer` classes have been removed. Use `Application` with `RoutingProvider` or `ServerProvider` instead:

``` diff
-namespace Zapheus;
+namespace Acme\App;

-$app = new Coordinator;
+$app = new Application;

-$app->get('/', function ()
-{
-    return 'Hello world!';
-});
+$router = new Zapheus\Routing\Router;
+
+$router->get('/', function ()
+{
+    return 'Hello world!';
+});
+
+$app->add(new Zapheus\Routing\RoutingProvider($router));
```

``` diff
-$app = new Middlelayer;
+$app = new Application;

-$app->pipe(function ($request, $next) use ($router)
-{
-    // router middleware logic
-    return $next->handle($request);
-});
+$app->add(new Zapheus\Http\ServerProvider($middlewares));
```

### Moved interfaces to `Contract`

All interfaces have been moved to the `Zapheus\Contract\*` namespace and the `Interface` suffix has been dropped:

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

``` diff
-use Zapheus\Routing\RouteInterface;
+use Zapheus\Contract\Routing\Route;

-class MyRoute implements RouteInterface
+class MyRoute implements Route
```

### PSR-7/PSR-15 method renames

Contract interfaces have been updated to follow PSR-7 (HTTP Message) and PSR-15 (HTTP Server) method naming conventions. All getter methods now use the `get` prefix.

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
+public function __construct()

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
$dispatcher = (new Zapheus\Http\Server\Dispatcher($stack))
    ->container($container);
```

#### `Zapheus\Routing\RoutingProvider`

``` diff
-public function __construct(Router $router = null)
+public function __construct()

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
+public function __construct()

+/**
+ * Sets the error message.
+ *
+ * @param string $message
+ *
+ * @return self
+ */
+public function setMessage($message)
```
