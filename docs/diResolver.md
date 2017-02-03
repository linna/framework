---
layout: default
title: DI Resolver
current_menu: diResolver
---

# DI Resolver
DI Resolver is one of the [dependency injection](https://en.wikipedia.org/wiki/Dependency_injection) tool of Linna framework, 
it automatically inject dependencies into a class constructor. Constructor injection works with all type of parameters but for non
objects parameters is necessary declare a rule through DIresolver->rules() method.

## How it works?
DI Resolver use for recognize constructor arguments [PHP reflection](http://php.net/manual/en/book.reflection.php)

## Class Structure

Properties
- *no public properties*

Methods
- __construct()
- cache()
- resolve()
- rules()

## Methods

### __construct()
Class constructor
```php
$DIResolver = new DIResolver();
```

### cache()
Store in DI Resolver, objects that cannot be resolved

#### Parameters
*string* **$name**<br/>
*object* **$object**<br/>

#### Usage
```php
$DIResolver = new DIResolver();

//store unresolvable object
$DIResolver->cache('\FOOObject', new FOOObject('bar','baz'));
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
//work
$a = $DIResolver->resolve('\A'); 
//work
$a = $DIResolver->resolve('A'); 
```

With namespaces:
```php
namespace Linna\Baz;

use \Linna\Foo\A;
use \Linna\Foo\B;
use \Linna\Foo\C;
use \Linna\Foo\D;

//example classes, A require B, B require C and D
class A { public function __construct(B $b) {echo 'A';} }
class B { public function __construct(C $c, D $d) {echo 'B';} }
class C { public function __construct() {echo 'C';} }
class D { public function __construct() {echo 'D';} }

$DIResolver = new DIResolver();
//work
$a = $DIResolver->resolve('\Linna\Foo\A');
//work
$a = $DIResolver->resolve('Linna\Foo\A');
//not work
$a = $DIResolver->resolve('\A'); 
//not work
$a = $DIResolver->resolve('A');
```

### rules()
Store in DI Resolver rules for resolve classes that accepts non object parameters

#### Parameters
*array* **$rules**<br/>

#### Usage
```php
namespace Linna\Foo;

class Foo { public function __construct(FooAA $aa) {echo 'Foo';} }
class FooAA { public function __construct(bool $aaBool, FooB $b, string $aaString, int $aaInt, array $aaArray, $aaNoType) {echo 'FooAA';} }
class FooB { public function __construct(C $c, D $d){echo 'FooB';} }

$DIResolver = new DIResolver();
//array with rules for class that require non object arguments
$DIResolver->rules([
    '\Linna\Foo\FooAA' => [
        0 => true,
        2 => 'baz',
        3 => 1,
        4 => ['baz'],
        5 => 'baz'
    ]
]);

$a = $DIResolver->resolve('Linna\Foo\Foo');