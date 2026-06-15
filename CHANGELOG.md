# Changelog

All notable changes to `Zapheus` will be documented in this file.

## 0.2.0 - Unreleased

### Added
- `getContainer` in `Application`
- HTTP factories in `Http\Factory`

### Changed
- `Application::run` returns `Stream` not `string`
- `Application` implements `Handler`, `Writable`
- Message - Conformed to [PSR standards](https://www.php-fig.org/psr/psr-7)
- Moved interfaces to `Zapheus\Contract`
- Routing - `Dispatcher` accepts `Router`
- Routing - `proteceted namespace` to `public setNamespace`
- Routing - Method names of `Route`
- Server - `container` to `setContainer` in `Dispatcher`

### Removed
- `$base_namespace` parameter in `Router`
- `Coordinator`, `Middlelayer` classes
- `Ropebridge`, `Mutator` classes
- `Route::result` static method
- Implicitly nullable type hints
- Second argument in `Resolver::resolve`

## 0.1.0 - 2018-04-23

### Added
- `Zapheus` framework
