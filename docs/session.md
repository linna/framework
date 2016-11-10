---
layout: default
title: Session
current_menu: session
---

# Session

Session class help to utilize the php session, it is a low level session abstraction, various class methods are the direct counterpart of php session functions

## Basic usage
*Note: Class accept as argument an array for options, it will be [explaned below](https://github.com/s3b4stian/linna-framework/blob/master/docs/session.md#options)*<br/>
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
## Options
Options could passed to class constructor as key=>value array
```php
$session = new Session([
    'expire' => 1800,
    'name' => 'APP_SESSION',
    'cookieDomain' => '/',
    'cookiePath' => '/',
    'cookieSecure' => false,
    'cookieHttpOnly' => true
]);
```
| Name           | Default     | Description                                                                            |
|----------------|-------------|----------------------------------------------------------------------------------------|
| expire         | 1800        | indicate session expire time in seconds                                                |
| name           | APP_SESSION | session name                                                                           |
| cookieDomain   | /           | domain, example 'www.php.net'. for include all subdomains prefix with a dot '.php.net' |
| cookiePath     | /           | domain path where the cookie work                                                      |
| cookieSecure   | false       | cookie sent only over secure connection                                                |
| cookieHttpOnly | true        | cookie accessible only thorough HTTP protocol                                          |

## Class Structure

Properties
- id

Methods
- __contruct()
- regenerate()
- start()
- destroy()
- commit()

### id
#### Description
#### Usage

### __contruct()
#### Description
#### Parameters
#### Usage

### start()
#### Description
#### Usage

### regenerate()
#### Description
#### Usage

### destroy()
#### Description
#### Usage

### commit()
#### Description
#### Usage
