---
layout: default
title: DI Container
current_menu: diContainer
---

# DI Container
DI Container is a small class that would help you to manage object dependecies. Compared to DI Resolever, it does not resolve dependencies for a class, but store a function that create object instance on the fly. Choose of not add container functionality to DI Resolver, it was determined by preserve DI Resolver class lighter possible.

## How it work?
Class use php [magic methods](http://php.net/manual/en/language.oop5.magic.php)


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
