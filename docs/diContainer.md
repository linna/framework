---
layout: default
title: DI Container
current_menu: diContainer
---

# DI Container
DI Container is a small class that will help you to manage object dependecies. Compared to DI Resolever, it does not resolve dependencies for a class, 
but store a function that create object instances on the fly. It was decided not to add container functionality to DI Resolver, 
to preserve DI Resolver class lightness.<br />
DI Container implements ContainerInterface from [Container Interoperability](https://github.com/container-interop/container-interop). Delegate lookup feature not available.

## How it works?
Class offer multiple modes for manage data, it's possibole utilize class methods, object or array syntax.
```php
$container = new DIContainer();

//with method
$container->set('FooClass',  function () { return new \stdClass(); });

//with array syntax
$container['FooClass'] = function () { return new \stdClass(); };

//with object syntax
$container->FooClass = function () { return new \stdClass(); };
```

## Class Structure

Properties
- *no public properties*

Methods
- __construct()
- get()
- has()
- set()
- delete()

## Methods

### __construct()
Class constructor
```php
$container = new DIContainer();
```

### get()
Get valuefrom container

#### Parameters
*string* **$id**<br/>

#### Usage
```php
$container->get('FooClass');
```

### has()
Check if value is stored inside container

#### Parameters
*string* **$id**<br/>

#### Usage
```php
$container->has('FooClass');
```

### set()
Store value inside container

#### Parameters
*string* **$id**<br/>
*callable* **$value**<br/>

#### Usage
```php
$container->set('FooClass', function () { return new \stdClass(); });
```

### delete()
Delete value from container

#### Parameters
*string* **$id**<br/>

#### Usage
```php
$container->delete('FooClass');
```