---
layout: default
title: Session
current_menu: session
---

# Session

Session class help to utilize the php session, it is a low level session abstraction, various class methods are the direct counterpart of php session functions

## Basic usage
*Note: Class accept as argument an array for options, it will be explaned below*<br/>
Basic usage of Session class:
```php
use Linna\Session\Session;

//create session object
$session = new Session();

//start session
$session->start();
```

If you accindentally call more than one time Session->start() method, any error or exception will trow:
```php
$session = new Session();

//start session
$session->start();
$session->start();
$session->start();
$session->start();
$session->start();
$session->start();
```
Session class recognize that php session is already started and do nothing.

### Storing and retriving values from session
Session Class using php magic methods and is configured as generic container. All passed data is linked to $_SESSION superglobal variable.
```php
//other code
$session->start();
$session->fooData = 'bar_baz';
```
Is equal to raw php code
```php
session_start();
$_SESSION['fooData'] = 'bar_baz';
```

## Class Structure

Properties
- id

Methods
- __contruct()
- regenerate()
- start()
- destroy()
- commit()

