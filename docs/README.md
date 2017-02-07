---
layout: default
title: Documentation
---

# Introduction

Hello! This is the Linna Framework API Documentation. Below there is explained how every component of Framework works.<br/>
If you wish start to utilize the Framework for build an app, look [Linna App](https://github.com/s3b4stian/linna-app).

# API Documentation

### Http
* [Router](router.md)
* [Route Object](route.md)
* (To do) [Route Examples](routeExamples.md)
* [Front Controller](frontController.md)

### Session
* [Session](session.md)
* [Session Handler](sessionHandler.md)

### Auth
* [Login](login.md)
* [Password](password.md)
* (To do) [Protected Controller](protectedController.md)

### Model View Controller
* [How pattern is implemented?](mvcImplement.md)
* [Model](model.md)
* (Incomplete) [View](view.md)
* [Controller](controller.md)
* (To do) [TemplateInterface](templateInterface.md)

### Dependency Injection
* [DI Container](diContainer.md)
* [DI Resolver](diResolver.md)

### Storage
* (To do) [Adapter](adapter.md)

### Data Mapper
* (To do) [Domain Object Abstract](domainObjectAbstract.md)
* (To do) [Domain Object Interface](domainObjectInterface.md)
* (To do) [Mapper Abstract](mapperAbstract.md)

# Namespaces with every component

*[C] Class [I] Interface [T] Trait*

Linna\
- [C] Autoloader

Linna\Auth\
- [C] Login
- [C] Password
- [T] ProtectedController

Linna\DataMapper\
- [C] DomainObjectInterface
- [C] DomainObjectAbstract
- [C] MapperAbstract

Linna\DI\
- [C] DIContainer
- [C] DIResolver

Linna\Http\
- [I] RouteInterface
- [C] FrontController
- [C] Route
- [C] Router

Linna\Mvc\
- [I] TemplateInterface
- [C] Model
- [C] View
- [C] Controller

Linna\Session\
- [C] DatabaseSessionHandler
- [C] MemcachedSessionHandler
- [C] Session

Linna\Storage\
- [I] AdapterInterface
- [C] MongoDbAdapter
- [C] MysqlPdoAdapter