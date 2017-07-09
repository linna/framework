
# Linna Framework Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/) 
and this project adheres to [Semantic Versioning](http://semver.org/).

## [Unrelased] [v0.20.0](https://github.com/linna/framework/compare/v0.19.0...v0.20.0) - 2017-XX-XX

### Fixed
* `CHANGELOG.md` links url

## [v0.19.0](https://github.com/linna/framework/compare/v0.18.0...v0.19.0) - 2017-06-24

### Added
* `Linna\Cache\CacheFactory` for get cache resources

### Changed
* `Linna\DI\Container` test updated
* `Linna\DI\Resolver` merged with `Linna\DI\Container`

### Fixed
* `Linna\Session\Session` bug on cookie create

### Removed
* `Linna\Cache\Exception\InvalidArgumentException` reminiscence of PSR simple-cache
* `Linna\Auth\PermissionTrait->showPermissions()` method, use `getPermissions()` instead
* `Linna\Auth\Role->showUsers()` method, use `getUsers()` instead

## [v0.18.0](https://github.com/linna/framework/compare/v0.17.0...v0.18.0) - 2017-05-30

### Added
* `Linna\Storage\PostgresqlPdoStorage` for PostgreSql database connections.
* `Linna\Storage\StorageInterface` implementation must contain `public function __construct(array $options)`
* `Linna\Storage\PdoStorage` instead of `Linna\Storage\PostgresqlPdoStorage` and `Linna\Storage\MysqlPdoStorage`

### Changed
* Cache depends from [s3b4stian simple-cache](https://github.com/s3b4stian/simple-cache) instead of [Psr simple-cache](https://github.com/php-fig/simple-cache)
* `Linna\Mvc\FrontController->response()` return type added `:string`
* `Linna\Mvc\TemplateInterface->output()` return type added `:string`
* `Linna\Mvc\View->render()` return type added `:string`
* `Linna\Mvc\TemplateInterface->output()` changed name to `Linna\Mvc\TemplateInterface->getOutput()`

## [v0.17.0](https://github.com/linna/framework/compare/v0.16.0...v0.17.0) - 2017-05-05

### 5 Maggio, May 5
* [Napoleon death](https://en.wikipedia.org/wiki/Napoleon)
* [May 5](https://it.wikipedia.org/wiki/Il_cinque_maggio)

### Added
* `Linna\Http\Router` now can use REST routes
* `Linna\Http\Router` now return a `Linna\Http\NullRoute` when didn't find a route
* `Linna\Http\NullRoute` object
* `Linna\Shared\ClassOptionsTrait->setOptions` now throw an `\InvalidArgumentException` for bad option names

### Changed
* `Linna\Http\RouterCached` constructor parameters order changed
* `Linna\Storage\MysqlPdoObject` changed name to `Linna\Storage\MysqlPdoStorage` 
* `Linna\Storage\MysqliObject` changed name to `Linna\Storage\MysqliStorage`
* `Linna\Storage\MongoDbOject` changed name to `Linna\Storage\MongoDbStorage`
* `Linna\Storage\StorageObjectInterface` changed name to `Linna\Storage\StorageInterface`

## [v0.16.0](https://github.com/linna/framework/compare/v0.15.0...v0.16.0) - 2017-04-20

### Added
* `Linna\Http\Route->getName()` method
* `Linna\Http\Route->getMethod()` method
* `Linna\Http\Route->getUrl()` method

### Changed
* `Linna\Http\FastMapTrait->mapGet()` changed name to `Linna\Http\FastMapTrait->get()`
* `Linna\Http\FastMapTrait->mapPost()` changed name to `Linna\Http\FastMapTrait->post()`
* `Linna\Http\FastMapTrait->mapPut()` changed name to `Linna\Http\FastMapTrait->put()`
* `Linna\Http\FastMapTrait->mapPatch()` changed name to `Linna\Http\FastMapTrait->patch()` 
* `Linna\Http\FastMapTrait->mapDelete()` changed name to `Linna\Http\FastMapTrait->delete()`
* `Linna\Http\FrontController` moved to `Linna\Mvc\FrontController`
* `Linna\Http\Route->getArray()` changed name to `Linna\Http\Route->toArray()`
* `Linna\Http\Router` url evaluation improved with rewrite mode off
* `Linna\Mvc\FrontController` Route class dependency removed
* `Linna\Storage\MysqlPdoAdapter` changed name to `Linna\Storage\MysqlPdoObject` 
* `Linna\Storage\MysqliAdapter` changed name to `Linna\Storage\MysqliObject`
* `Linna\Storage\MongoDbAdapter` changed name to `Linna\Storage\MongoDbOject`
* `Linna\Storage\StorageInterface` changed name to `Linna\Storage\StorageObjectInterface`
* Varius internal code improvements
* Tests now cover 100% of code


## [v0.15.0](https://github.com/linna/framework/compare/v0.14.0...v0.15.0) - 2017-03-28

### Added
* `Linna\Http\FastMapTrait` trait
* `Linna\Http\Router->map()` method for register routes after router instance creation
* `Linna\Auth\Authorize` class
* `Linna\Auth\EnhancedUser` class
* `Linna\Auth\EnhancedUserMapperInterface` interface
* `Linna\Auth\Permission` class
* `Linna\Auth\PermissionMapperInterface` interface
* `Linna\Auth\PermissionTrait` trait
* `Linna\Auth\Role` class
* `Linna\Auth\RoleMapperInterface` interface
* `Linna\Auth\UserMapperInterface` interface
* `Linna\DataMapper\NullDomaninObject` class
* `Linna\Storage\StorageFactory` class
* `Linna\Cache\ActionMultipleTrait` added instead duplicate code in `Linna\Cache\MemcachedCache` and `Linna\Cache\DiskCache`
* `Linna\Shared` namespace
* `Linna\Shared\ClassOptionsTrait` trait, provide methods for set options

### Changed
* Documentation [moved](https://github.com/linna/docs)
* `Linna\Http\Router` return `false` when there isn't an error route configured
* `Linna\Auth\Login` changed name to `Linna\Auth\Authenticate`
* `Linna\Storage\AdapterInterface` changed name to `Linna\Storage\StorageInterface`

### Removed
* Deprecated property `private $expire` in `Linna\Auth\Login`

## [v0.14.0](https://github.com/linna/framework/compare/v0.13.0...v0.14.0) - 2017-02-27

### Added
* `Linna\Cache` namespace
* `Linna\Cache\MemcachedCache` class, provide [PSR-16](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-16-simple-cache.md) implementation
* `Linna\Cache\DiskCache` class, provide [PSR-16](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-16-simple-cache.md) implementation
* `Linna\Cache\Exception\InvalidArgumentException` exception
* Tests for `Linna\Cache`
* `Linna\Session\Session` can store value using array or property syntax
* `Linna\Session\Session->status` property added, indicate session status
* `$options` parameter added to `Linna\Auth\Password->__construct()`
* `Linna\Http\Router->validate()` type return bool

### Changed
* Session tests updated
* `Linna\Http\RouterCached` now require `CacheInterface` instead of `Memcached`
* `Linna\DI\DIContainer` changed name to `Linna\DI\Container`
* `Linna\DI\DIResolver` changed name to `Linna\DI\Resolver`

## [v0.13.0](https://github.com/linna/framework/compare/v0.12.0...v0.13.0) - 2017-02-19

### Added
* `Linna\Http\FrontController` can execute actions before and after main controller action
* When extend `Linna\Mvc\Controller` it's possible declare global before() and after() valid for all controller actions
* When extend `Linna\Mvc\Controller` it's possible declare specific before() and after() methods for one controller action

### Changed
* Optimized `Linna\DI\DIResolver` memory usage
* Optimized `Linna\Http\Router->validate()`
* `Linna\Auth\Login` change name to `Linna\Auth\Authenticate`
* Tests updated

### Fixed
* Abstract methods in `Linna\DI\ArrayAccessTrait` and `Linna\DI\PropertyAccessTrait` undeclared arguments

## [v0.12.0](https://github.com/linna/framework/compare/v0.11.0...v0.12.0) - 2017-02-14

### Changed
* `Linna\DI\DIContainer` switched from `Interop\Container\ContainerInterface` to [PSR-11](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-11-container.md)
* `Linna\DI\Exception\Container` change name to `Linna\DI\Exception\ContainerException`
* `Linna\DI\Exception\NotFound` change name to `Linna\DI\Exception\NotFoundException`
* `Linna\Autoloader` now not throw exceptions 
* `Linna\Autoloader` tests updated

## [v0.11.0](https://github.com/linna/framework/compare/v0.10.0...v0.11.0) - 2017-02-11

### Added
* Tests for `Linna\Storage\MysqliAdapter`
* Tests for `Linna\Storage\MongoDbAdapter`

### Changed
* `Linna\DI\DIResolver` tests updated
* `Linna\DI\DIContainer` tests updated
* `Linna\DI\DIResolver` now implements `Interop\Container\ContainerInterface`
* `Linna\DI\DIResolver` now possible access data with array syntax or with methods

## [v0.10.0](https://github.com/linna/framework/compare/v0.9.1...v0.10.0) - 2017-02-03

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
