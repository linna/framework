
# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/) 
and this project adheres to [Semantic Versioning](http://semver.org/).

## [v0.13.0](https://github.com/s3b4stian/linna-framework/compare/v0.12.0...v0.13.0) - 2017-XX-XX

### Added
* `Linna\Http\FrontController->runController()` now can execute methods before() and after() run a controller
* When extend `Linna\Mvc\Controller` it's possible declare before() and after() methods

## [v0.12.0](https://github.com/s3b4stian/linna-framework/compare/v0.11.0...v0.12.0) - 2017-02-14

### Changed
* `Linna\DI\DIContainer` switched from `Interop\Container\ContainerInterface` to [PSR-11](https://github.com/container-interop/fig-standards/blob/master/proposed/container.md)
* `Linna\DI\Exception\Container` change name to `Linna\DI\Exception\ContainerException`
* `Linna\DI\Exception\NotFound` change name to `Linna\DI\Exception\NotFoundException`
* `Linna\Autoloader` now not throw exceptions 
* `Linna\Autoloader` tests updated

## [v0.11.0](https://github.com/s3b4stian/linna-framework/compare/v0.10.0...v0.11.0) - 2017-02-11

### Added
* Tests for `Linna\Storage\MysqliAdapter`
* Tests for `Linna\Storage\MongoDbAdapter`

### Changed
* `Linna\DI\DIResolver` tests updated
* `Linna\DI\DIContainer` tests updated
* `Linna\DI\DIResolver` now implements `Interop\Container\ContainerInterface`
* `Linna\DI\DIResolver` now possible access data with array syntax or with methods

## [v0.10.0](https://github.com/s3b4stian/linna-framework/compare/v0.9.1...v0.10.0) - 2017-02-03

### Added
* Added `Linna\Storage\MysqlPdoAdapter` class
* Added `Linna\Storage\MysqliAdapter` class
* Added `Linna\Storage\MongoDbAdapter` class
* Added possibility for `Linna\DI\DIResolver` to resolve classes with no class instance parameters
* Added `Linna\DI\DIResolver->rules()` method for store rules for parameters that are no class instance

### Changed
* `Linna\DI\DIResolver` internal optimization
* `Linna\DI\DIResolver->resolve()` method now resolve using \ or not before class name
* Namespace `Linna\Database` turned in `Linna\Storage`
* Class `Linna\Session\DatabaseSessionHandler` turned in `Linna\Session\MysqlPdoSessionHandler`
* Trait `Linna\Auth\ProtectedController` updated
* Tests updated

### Removed
* Removed class `Linna\Database\Database` 
