
# Linna Framework Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/)
and this project adheres to [Semantic Versioning](http://semver.org/).

## [Unreleased] [v0.25.0](https://github.com/linna/framework/compare/v0.24.0...v0.25.0) - 20XX-XX-XX

### Added
* `Linna\Authentication\ProtectedController->protectWithRedirect()` method
* `Linna\Authentication\Exception\AuthenticationException` exception
* `Linna\Authorization\EnhancedUser->__constructor()`
* `Linna\Authorization\EnhancedUser->hasRole()` method
* `Linna\Authorization\EnhancedUser->hasRoleById()` method
* `Linna\Authorization\EnhancedUser->hasRoleByName()` method
* `Linna\Authorization\EnhancedUserMapperInterface->grantPermission()` method
* `Linna\Authorization\EnhancedUserMapperInterface->grantPermissionById()` method
* `Linna\Authorization\EnhancedUserMapperInterface->grantPermissionByName()` method
* `Linna\Authorization\EnhancedUserMapperInterface->revokePermission()` method
* `Linna\Authorization\EnhancedUserMapperInterface->revokePermissionById()` method
* `Linna\Authorization\EnhancedUserMapperInterface->rovekePermissionByName()` method
* `Linna\Authorization\EnhancedUserMapperInterface->addRole()` method
* `Linna\Authorization\EnhancedUserMapperInterface->addRoleById()` method
* `Linna\Authorization\EnhancedUserMapperInterface->addRoleByName()` method
* `Linna\Authorization\EnhancedUserMapperInterface->removeRole()` method
* `Linna\Authorization\EnhancedUserMapperInterface->removeRoleById()` method
* `Linna\Authorization\EnhancedUserMapperInterface->removeRoleByName()` method
* `Linna\Authorization\FetchByPermissionInterface` interface
* `Linna\Authorization\FetchByRoleInterface` interface
* `Linna\Authorization\FetchByUserInterface` interface
* `Linna\Authorization\PermissionTrait->canById()` method
* `Linna\Authorization\PermissionTrait->canByName()` method
* `Linna\Authorization\PermissionMapperInterface->permissionExistById()` method
* `Linna\Authorization\PermissionMapperInterface->permissionExistByName()` method
* `Linna\Authorization\Role->isUserInRole()` method
* `Linna\Authorization\Role->isUserInRoleById()` method
* `Linna\Authorization\Role->isUserInRoleByName()` method
* `Linna\Authorization\RoleMapperInterface->grantPermission()` method
* `Linna\Authorization\RoleMapperInterface->grantPermissionById()` method
* `Linna\Authorization\RoleMapperInterface->grantPermissionByName()` method
* `Linna\Authorization\RoleMapperInterface->revokePermission()` method
* `Linna\Authorization\RoleMapperInterface->revokePermissionById()` method
* `Linna\Authorization\RoleMapperInterface->revokePermissionByName()` method
* `Linna\Authorization\RoleMapperInterface->addUser()` method
* `Linna\Authorization\RoleMapperInterface->addUserById()` method
* `Linna\Authorization\RoleMapperInterface->addUserByName()` method
* `Linna\Authorization\RoleMapperInterface->removeUser()` method
* `Linna\Authorization\RoleMapperInterface->removeUserById()` method
* `Linna\Authorization\RoleMapperInterface->removeUserByName()` method
* `Linna\Authorization\RoleToUserMapperInterface` interface
* `Linna\DataMapper\FetchAllInterface` interface
* `Linna\DataMapper\FetchByNameInterface` interface
* `Linna\DataMapper\FetchLimitInterface` interface

### Changed
* `Linna\Authentication\LoginAttempt` default value added to properties
* `Linna\Authentication\ProtectedController` now throw `AuthenticationException` when try to access to protected resource without authentication
* `Linna\Authentication\ProtectedController->protect()` metod now accept as argument `Authentication` instance and http status code as `int`
* `Linna\Authentication\User` default value added to properties
* `Linna\Authentication\UserMapperInterface` extends `Linna\DataMapper\FetchByNameInterface`
* `Linna\Authorization\Authorization` default value added to properties
* `Linna\Authorization\Authorization->can` now accept Permission instance, permission id as integer or permission name as string
* `Linna\Authorization\EnhancedUserMapperInterface` extends `Linna\Authorization\FetchByPermissionInterface`
* `Linna\Authorization\EnhancedUserMapperInterface` extends `Linna\Authorization\FetchByRoleInterface`
* `Linna\Authorization\Permission` default value added to properties
* `Linna\Authorization\PermissionMapperInterface` extends `Linna\DataMapper\FetchByNameInterface`
* `Linna\Authorization\PermissionMapperInterface` extends `Linna\Authorization\FetchByRoleInterface`
* `Linna\Authorization\PermissionMapperInterface` extends `Linna\Authorization\FetchByUserInterface`
* `Linna\Authorization\PermissionTrait->can()` now accepts as argument `Linna\Authorization\Permission` instance
* `Linna\Authorization\RoleMapperInterface` extends `Linna\Authorization\FetchByPermissionInterface`
* `Linna\Authorization\RoleMapperInterface` extends `Linna\Authorization\FetchByUserInterface`
* `Linna\Authorizationn\PermissionTrait` default value added to properties
* `Linna\Authorizationn\Role` default value added to properties
* `Linna\Linna\DataMapper\DomainObjectAbstract->rId` public property added
* `Linna\Http` namespace renamed to `Linna\Router`
* `Linna\Mvc\FrontController` default value added to properties
* `Linna\Router\Router` default value added to properties
* `Linna\Session\MemcachedSessionHandler` default value added to properties
* `Linna\Session\Session` default value added to properties
* `Linna\Storage\AbstractConnector` default value added to properties
* `Linna\Storage\AbstractStorageFactory` default value added to properties
* `Linna\Storage\ExtendedPDO` default value added to properties

### Fixed
* Minor issues fixed

### Removed
* `Linna\Authorization\EnhancedUserMapperInterface->fetchUserByRole()` method
* `Linna\Authorization\EnhancedUserMapperInterface->fetchUserByPermission()` method
* `Linna\Authorization\EnhancedUserMapperInterface->grant()` method
* `Linna\Authorization\EnhancedUserMapperInterface->revoke()` method
* `Linna\Authorization\PermissionMapperInterface->fetchPermissionsByRole()` method
* `Linna\Authorization\PermissionMapperInterface->fetchPermissionsByUser()` method
* `Linna\Authorization\PermissionMapperInterface->fetchUserPermissionHashTable()` method
* `Linna\Authorization\PermissionMapperInterface->permissionExist()` method
* `Linna\Authorization\PermissionTrait->getPermissions()` method
* `Linna\Authorization\PermissionTrait->setPermissions()` method
* `Linna\Authorization\Role->getUsers()` method
* `Linna\Authorization\Role->setUsers()` method
* `Linna\Authorization\RoleMapperInterface->fetchUserInheritedPermissions()` method
* `Linna\Authorization\RoleMapperInterface->permissionGrant()` method
* `Linna\Authorization\RoleMapperInterface->permissionRevoke()` method
* `Linna\Authorization\RoleMapperInterface->userAdd()` method
* `Linna\Authorization\RoleMapperInterface->userRemove()` method

## [v0.24.0](https://github.com/linna/framework/compare/v0.23.1...v0.24.0) - 2018-09-01

### Added
* `Linna\Authentication\PasswordGenerator` class
* `Linna\DI\Container` constructor, now rules should be passed here
* void return type to methods
* Namespace for tests

### Changed
* Minimun PHP version: 7.1
* Exception messages
* `Linna\Authentication\Authenticate` renamed to `Linna\Authentication\Authentication`
* `Linna\Authentication\EnhancedAuthenticate` renamed to `Linna\Authentication\EnhancedAuthentication`
* `Linna\Authentication\EnhancedAuthenticateMapperInterface` renamed to `Linna\Authentication\EnhancedAuthenticationMapperInterface`
* `Linna\Authorization\Authorize` renamed to `Linna\Authorization\Authorization`
* `Linna\DI` namespace renamed to `Linna\Container`
* Tests updated

### Removed
* `Linna\Helper\Env` class, use instead [dotenv](https://github.com/linna/dotenv) package
* `Linna\Helper\Str` class
* `Linna\Http\RouterCached` class, caching will be added to [app](https://github.com/linna/app) package
* `Linna\DI\Container->setRules()` method

## [v0.23.1](https://github.com/linna/framework/compare/v0.23.0...v0.23.1) - 2017-11-01

### Fixed
* `Linna\Mvc\FrontController` view don't call default method

## [v0.23.0](https://github.com/linna/framework/compare/v0.22.0...v0.23.0) - 2017-11-01

### Added
* `Linna\Authentication\User->uuid` property
* `Linna\Mvc\Model->set()` method for set data to notify to observer
* `Linna\Mvc\Model->get()` method for retrive data to notify to observer
* `Linna\Helper\Env` class [#58](https://github.com/linna/framework/pull/58)
* `Linna\Helper\Eng::get()` static method
* `Linna\Helper\Str` class [#58](https://github.com/linna/framework/pull/58)
* `Linna\Helper\Str::startsWith()` static method
* `Linna\Helper\Str::endsWith()` static method

### Changed
* `Linna\Cache\DiskCache->__construct()` ttl option removed
* `Linna\Http\FastMapTrait` merged into `Linna\Http\Router` through magic `__call()`
* `Linna\Session\MysqlPdoSessionHandler->__construct()` now expect `Linna\Storage\ExtendedPDO` as parameter
* `Linna\Storage\PdoStorage` moved to `Linna\Storage\Connectors\PdoConnector`
* `Linna\Storage\MysqliStorage` moved to `Linna\Storage\Connectors\MysqliConnector`
* `Linna\Storage\MongoDbStorage` moved to `Linna\Storage\Connectors\MongoDBConnector`
* `Linna\Storage\StorageInterface` renamed to `Linna\Storage\ConnectorInterface`
* `Linna\Storage\StorageFactory->get()` now return the connection resource directly

### Fixed
* `Linna\Auth\Authenticate` login data doesn't update after login
* `Linna\Mvc\FrontController` action execution before and after

### Removed
* `Linna\Http\FastMapTrait`

## [v0.22.0](https://github.com/linna/framework/compare/v0.21.0...v0.22.0) - 2017-10-24

### Added
* `Linna\Autoloader->unregister()` method
* `Linna\Authentication\EnhancedAuthenticate` class
* `Linna\Authentication\EnhancedAuthenticateMapperInterface` interface
* `Linna\Authentication\LoginAttempt` class
* `Linna\Storage\ExtendedPDO->getLastOperationStatus()` method

### Changed
* `Linna\Auth` splitted into `Linna\Authentication` and `Linna\Authorization`
* `Linna\Auth\Authenticate` moved under namespace `Linna\Authentication`
* `Linna\Auth\Password` moved under namespace `Linna\Authentication`
* `Linna\Auth\ProtectedController` moved under namespace `Linna\Authentication`
* `Linna\Auth\User` moved under namespace `Linna\Authentication`
* `Linna\Auth\UserMapperInterface` moved under namespace `Linna\Authentication`
* `Linna\Auth\Authorize` moved under namespace `Linna\Authorization`
* `Linna\Auth\EnhancedUser` moved under namespace `Linna\Authorization`
* `Linna\Auth\EnhancedUserMapperInterface` moved under namespace `Linna\Authorization`
* `Linna\Auth\Permission` moved under namespace `Linna\Authorization`
* `Linna\Auth\PermissionMapperInterface` moved under namespace `Linna\Authorization`
* `Linna\Auth\PermissionTrait` moved under namespace `Linna\Authorization`
* `Linna\Auth\Role` moved under namespace `Linna\Authorization`
* `Linna\Auth\RoleMapperInterface` moved under namespace `Linna\Authorization`
* Tests updated

## [v0.21.0](https://github.com/linna/framework/compare/v0.20.2...v0.21.0) - 2017-10-04

### Added
* `Linna\Mvc\TemplateInterface->setData()` for set template data
* `Linna\Storage\ExtendedPDO` class
* `Linna\Storage\AbstractStorageFactory` class

### Changed
* `Linna\Http\Router` does not pass attribute `matches` to `Linna\Http\Route`
* `Linna\Mvc\View->render()` now call `Linna\Mvc\TemplateInterface->setData()`
* `Linna\Storage\PdoStorage` now return a `Linna\Storeage\ExtendedPDO` instance
* `Linna\Cache\DiskCache` now depends from `Linna\Storage\AbstractStorageFactory`
* `Linna\Storage\StorageFactory` now depends from `Linna\Storage\AbstractStorageFactory`
* `Linna\Storage\StorageFactory->getConnection()` changed name to `Linna\Storage\StorageFactory->get()`
* Tests updated

### Fixed
* `Linna\Http\Router` property `$router` docblock
* `Linna\Cache\MemcachedCache->set()` double cast to int
* `Linna\DataMapper\DomaninObjectAbstract->setId()` double cast to int

## [v0.20.2](https://github.com/linna/framework/compare/v0.20.1...v0.20.2) - 2017-07-25

### Added
* `Linna\Http\Router` option `rewriteModeOffRouter`

### Fixed
* `Linna\Http\Router` when work in write mode off
* tests updated

## [v0.20.1](https://github.com/linna/framework/compare/v0.20.0...v0.20.1) - 2017-07-17

### Fixed
* file permissions

## [v0.20.0](https://github.com/linna/framework/compare/v0.19.0...v0.20.0) - 2017-07-16

### Added
* `Linna\Http\RouteCollection` for create `Linna\Http\Route` objectc collections
* require [linna/typed-array > ^v1.0](https://github.com/linna/typed-array/releases)

### Changed
* tests updated

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
