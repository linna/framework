---
layout: default
title: DI Container
current_menu: diContainer
---

# DI Container
DI Container is a small class that will help you to manage object dependecies. Compared to DI Resolever, it does not resolve dependencies for a class, but store a function that create object instances on the fly. It was decided not to add container functionality to DI Resolver, to preserve DI Resolver class lightness.

## How it works?
Class, use php [magic methods](http://php.net/manual/en/language.oop5.magic.php) for create a container that accept only callable type:
```php
$DIContainer = new DIContainer();

//triggers an error
$DIContainer->FOOClass = 'Foo';

//correct
$DIContainer->FOOClass = function(){};
```

In order to utilize Container there's no need to call any method, simply declare the properties that you need to store in it
```php
$DIContainer = new DIContainer();

//create FOOClass instance builder
$DIContainer->FOOClass = function(){

    //put build code here
    
    //function must return new instance
    return new FOOClass(/*put previus built arguments here*/);
}

//$FOOClass now contain a FOOClass instance
$FOOClass = $DIContainer->FOOClass;
```

## Class Structure

Properties
- *no public properties*

Methods
- __construct()

## Methods

### __construct()
Class constructor
```php
$DIContainer = new DIContainer();
```
