---
layout: default
title: DI Resolver
current_menu: diResolver
---

# DI Resolver
DI Resolver is one of the [dependency injection](https://en.wikipedia.org/wiki/Dependency_injection) tool of Linna framework, 
it automatically inject dependencies into a class constructor. Unfortunately, at moment there are some limitation for constructor injection.<br />
It works only with objects, is not possible to inject primitive types like *int, string, float, array*. To solve this problem is possile to store in DI Resolver objects that require non-supported types arguments.

## How it works?
DI Resolver use for recognize constructor arguments [PHP reflection](http://php.net/manual/en/book.reflection.php)

## Class Structure

Properties
- *no public properties*

Methods
- __construct()
- cacheUnResolvable()
- resolve()

## Methods

### __construct()
Class constructor
```php
$DIResolver = new DIResolver();
```

### cacheUnResolvable()
Store in DI Resolver object that require primitive types arguments

#### Parameters
*string* **$name**<br/>
*object* **$object**<br/>

#### Usage
```php
$DIResolver = new DIResolver();

//store unresolvable object
$DIResolver->cacheUnResolvable('\FOOObject', new FOOObject('bar','baz'));
```

### resolve()
Return object instance after injecting dependencies

#### Parameters
*string* **$className**<br/>

#### Usage
```php

//example classes, A require B, B require C and D
class A { public function __construct(B $b) {echo 'A';} }
class B { public function __construct(C $c, D $d) {echo 'B';} }
class C { public function __construct() {echo 'C';} }
class D { public function __construct() {echo 'D';} }

$DIResolver = new DIResolver();
$a = $DIResolver->resolve('\A');
```
In the above example **$a** contain class **A** instance.<br />
*Important: to resolve, must pass as argument, '\ A' instead of 'A', if 'A' is passed DI Resolver will build dependencies returning a null value*
