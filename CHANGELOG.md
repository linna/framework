
# Linna Framework Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/)
and this project adheres to [Semantic Versioning](http://semver.org/).

## [Unreleased] [v0.28.0](https://github.com/linna/framework/compare/v0.27.0...v0.28.0)

### Added
* `Linna\Cache\RedisCache` class to cache data on Redis
* Tests using Postgresql in GithubActions

#### Authorization
* `Linna\Authorization\ExistsByIdInterface` interface
* `Linna\Authorization\ExistsByNameInterface` interface
* `Linna\Authorization\PermissionExtended` class
* `Linna\Authorization\PermissionExtendedMapperInterface` interface
* `Linna\Authorization\RoleExtended` class
* `Linna\Authorization\RoleExtendedMapperInterface` interface
* `Linna\Authorization\RoleTrait` trait
* `Linna\Authorization\UserExtended` class
* `Linna\Authorization\UserExtendedMapperInterface` interface
* `Linna\Authorization\UserTrait` trait

#### Crypto
* `Linna\Crypto` namespace
* `Linna\Crypto\KeyPair` class
* `Linna\Crypto\PublicKeyCrypto` class
* `Linna\Crypto\SecretKeyCrypto` class

#### Data Mapper
* `Linna\DataMapper\DomainObjectAbstract->hasId()` method, use it to check if a domain object has the id set
* `Linna\DataMapper\DomainObjectAbstract->hasNotId()` method, the opposite of `hadId()`
* `Linna\DataMapper\Exception` namespace
* `Linna\DataMapper\Exception\NullDomainObjectException` exception

#### Session
* `Linna\Session\EncryptedSessionHandler` class, a decorator to provide a encryption layer to other handlers
* `Linna\Session\PdoSessionHandler` class to unify `MysqlPdoSessionHandler` PgsqlPdoSessionHandler`
* `Linna\Session\PdoSessionHandlerQueryInterface` interface
* `Linna\Session\PdoSessionHandlerMysqlQuery` class
* `Linna\Session\PdoSessionHandlerPostgreQuery` class

### Changed

#### Authentication
* `Linna\Authentication\Password` now use internally libosudium functions for password hashing
* `Linna\Authentication\Password->__construct()` now has `int $opsLimit` and `int $memLimit` with default values set to `2` and `67108864` as arguments
* `Linna\Authentication\User` moved under the namespace `Linna\Authorization`

#### Cache
* Tests with Paratest
* Cache TTL handling improved
* `Linna\Cache\MemcachedCache->__construct()` now requires only options about memcached server/servers

#### Data Mapper
* The id of a `DomainObjectAbstract` is now `int|string` to use both a numeric id and a string uuid, this change has been reflected on all classes that use these values
* `Linna\DataMapper\NullDomainObject` now throw a `NullDomainObjectException` when try to set or get the object id
* `Linna\DataMapper\UUID4` class renamed to `Uuid4` to respect class naming concention

#### Mvc
* `Linna\Mvc\Model` is now an `abstract` class
* `Linna\Mvc\View` is now an `abstract` class
* `Linna\Mvc\Controller` is now an `abstract` class

### Fixed

#### Authentication
* `Linna\Authentication\Authentication->Login()` method now is safe against timing attacks

#### Session
* `Linna\Session\Session` double cookie header when new session starts

### Removed

#### Authorization
* `Linna\Authorization\RoleToUserMapperInterface` class

#### Session
* `Linna\Session\MysqlPdoSessionHandler` class
* `Linna\Session\PgsqlPdoSessionHandler` class


## [v0.27.0](https://github.com/linna/framework/compare/v0.26.0...v0.27.0) - 2022-09-17

### Added

#### Authentication
* `Linna\Authentication\Exception\AuthenticationException` now extend `Linna\Router\Exception\RedirectException`

#### Authorization
* `Linna\Authorization\Exception` namespace
* `Linna\Authorization\Exception\AuthorizationException` exception

#### Container
* `Linna\Container\Container::RULE_INTERFACE` public constant, use it in rules for resolve interfaces
* `Linna\Container\Container::RULE_ARGUMENT` public constant, use it in rules for resolve other arguments

#### Data Mapper
* `Linna\DataMapper\DomainObjectAbstract->created` public property
* `Linna\DataMapper\DomainObjectAbstract->lastUpdate` public property
* `Linna\DataMapper\DomainObjectAbstract->id` protected property accessible via `__get()` method

#### Router
* `Linna\Router\Router->parseQueryStringOnRewriteModeOn` protected property
* `parseQueryStringOnRewriteModeOn` as valid option for constructor

#### Session
* `Linna\Session\PgsqlSessionHandler` class
* `Linna\Session\Session->getSessionName()` method
* `Linna\Session\Session->getSessionId()` method
* `Linna\Session\Session->getStatus()` method

#### Shared
* `Linna\Shared\AbstractAccessTrait` trait
* `Linna\Shared\ArrayAccessTrait` trait
* `Linna\Shared\PropertyAccessTrait` trait
* `Linna\Shared\AbstractStorageFactory` class

### Changed

* PHP 8.1 required
* Constructor property promotion used when possible
* Readonly properties used when possible

#### Authentication
* `Linna\Authentication\Exception\AuthenticationException` now extend `Linna\Router\Exception\RedirectException`
* `Linna\Authentication\ProtectedControllerTrait->protect()` now have as second argument `string $route` instead of `int $httpResponseCode = 403`
* `Linna\Authentication\ProtectedControllerTrait->protectWithRedirect()` now have a third argument `string $route`

#### Cache
* Now drivers use [Psr\SimpleCache](https://github.com/php-fig/simple-cache/releases/tag/3.0.0) instead of my typed verion

#### Container
* `Linna\Container\Container` now it is possible resolve classes with interface as parameter
* `Linna\Container\Container` minor code optimizations

#### Data Mapper
* `Linna\DataMapper\MapperAbstract->save()` update after insert
* `Linna\DataMapper\DomainObjectAbstract->id` when not set has value `-1` instead of `0`
* `Linna\DataMapper\DomainObjectAbstract->setId` argument renamed `objectId` to `id`
* `Linna\DataMapper\DomainObjectAbstract->rId` public property removed
* `Linna\DataMapper\DomainObjectAbstract->objectId` protected property renamed to `id`

#### Mvc
* `Linna\Mvc\FrontController` now search for default `entryPoint` method instead of `index` if `$route` has not `action` set
* `Linna\Mvc\FrontController` renamed as `Linna\Mvc\ModelViewController`

#### Router
* `badRoute` it is no longer a valid option for constructor
* `Linna\Router\Route` as data-transfer object, all properties are read-only, all methods removed
* `Linna\Router\Route` now used named arguments in constructor, array options no longer supported
* `Linna\Router\Router` now used named arguments in constructor, array options no longer supported

#### Session
* All properties now are `private`, class more incapsulated

### Fixed

#### Container
* `Linna\Container\Container` error when a class without `__construct` method is encountered

#### Data Mapper
* `Linna\DataMapper\MapperAbstract->save()` update after insert

#### Session
* `Linna\Session\Session` session die because it does not refresh expiration time on client, also if present user interaction, with browser

### Removed

#### Mvc
* `Linna\Mvc\View->__construct()` no longer require `Model` as first parameter, useless reference

#### Router
* `Linna\Router\Router->badRoute` protected property

#### Storage
* `Linna\Storage\AbstractStorageFactory` class, moved to `Linna\Shared` namespace


## [v0.26.0](https://github.com/linna/framework/compare/v0.25.0...v0.26.0) - 2019-08-05

### Added

#### Data Mapper
* `Linna\DataMapper\UUID4` class

#### Router
* `Linna\Router\Route->allowed` property
* `Linna\Router\Route->getAllowed()` method
* `Linna\Router\Router` chars accepted in route params now are 0-9 A-Z a-z ._-
* `Linna\Router\Exception` namespace
* `Linna\Router\Exception\RedirectException` exception

### Changed
* minimum php version 7.2
* tests updated

#### Router
* `Linna\Router\Router->__construct()` now require a `Linna\Router\RouteCollection` as first argument

#### Storage
* `Linna\Storage\ConnectorsInterface->getResource()` now has return type `object`
* `Linna\Storage\Connectors\PdoConnector->getResource()` now has return type `object`
* `Linna\Storage\Connectors\MysqliConnector->getResource()` now has return type `object`
* `Linna\Storage\Connectors\MongoDBConnector->getResource()` now has return type `object`

### Fixed
* `Linna\Authentication\User->changePassword()` typo error in method name
* `Linna\Mvc\FrontController->runView()` error when `$this->routeAction` value called as method and not declared on view class


## [v0.25.0](https://github.com/linna/framework/compare/v0.24.0...v0.25.0) - 2019-01-13

### Added

#### Authentication
* `Linna\Authentication\ProtectedController->protectWithRedirect()` method
* `Linna\Authentication\Exception\AuthenticationException` exception

#### Authorization
* `Linna\Authorization\EnhancedUser->__construct()`
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

#### Data Mapper
* `Linna\DataMapper\FetchAllInterface` interface
* `Linna\DataMapper\FetchByNameInterface` interface
* `Linna\DataMapper\FetchLimitInterface` interface

### Changed

#### Authentication
* `Linna\Authentication\LoginAttempt` default value added to properties
* `Linna\Authentication\Password->__construct()` now accept as agument `int $algo` and `array $options`
* `Linna\Authentication\ProtectedController` now throw `AuthenticationException` when try to access to protected resource without authentication
* `Linna\Authentication\ProtectedController->protect()` metod now accept as argument `Authentication` instance and http status code as `int`
* `Linna\Authentication\ProtectedController` renamed to `Linna\Authentication\ProtectedControllerTrait`
* `Linna\Authentication\User` default value added to properties
* `Linna\Authentication\UserMapperInterface` extends `Linna\DataMapper\FetchByNameInterface`

#### Authorization
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

#### Data Mapper
* `Linna\Linna\DataMapper\DomainObjectAbstract->rId` public property added

#### Router
* `Linna\Http` namespace renamed to `Linna\Router`
* `Linna\Router\Route` memory usage improvement
* `Linna\Router\Route` all properties now are public
* `Linna\Router\Router` default value added to properties
* `Linna\Router\Router` memory usage improvement
* `Linna\Router\Router->map()` now accept as argument instance of `RouteInterface` instead of `array`

#### Mvc
* `Linna\Mvc\FrontController` default value added to properties
* `Linna\Mvc\FrontController->__construct()` now accept `RouteInterface` instance as last argument instead of `$action` and `$param`
* `Linna\Mvc\View->__construct()` now need `Model` and `TemplateInterface` as arguments

#### Session
* `Linna\Session\MemcachedSessionHandler` default value added to properties
* `Linna\Session\Session` default value added to properties
* `Linna\Session\Session` memory usage improvement

#### Storage
* `Linna\Storage\AbstractConnector` default value added to properties
* `Linna\Storage\AbstractStorageFactory` default value added to properties
* `Linna\Storage\ExtendedPDO` default value added to properties

### Fixed
* Minor issues fixed

### Removed

#### Authorization
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

#### Router
* `Linna\Router\NullRoute->toArray()` method
* `Linna\Router\RouteCollection->toArray()` method, use `->getArrayCopy()` instead
* `Linna\Router\RouteInterface->toArray()` method

#### Shared
* `Linna\Shared\ClassOptionsTrait` trait


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
