---
layout: default
title: Session
current_menu: session
---

# Session

Session class help to utilize the php session, it is a low level session abstraction, various class methods are the direct counterpart of php session functions

## How it works?
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
Passed to class constructor as key=>value array
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
- name

Methods
- __construct()
- setSessionHandler()
- regenerate()
- start()
- destroy()
- commit()

## Properties

### id
type: *string*<br/>

Contain session id after session start, is equal to call php session_id() function. Try to set this property don't overwrite session id.

#### Usage
```php
//start session
$session->start();

//show current session id
echo $session->id;
```

### name
type: *string*<br/>

Session name for current session

#### Usage
```php
//create session object with custom name
$session = new Session(['name' => 'FOOSession']) 

//start session
$session->start();

//show current session name "FOOSession"
echo $session->name;
```

Set the name from class property does not affect effective session name:
```php
//create session object with custom name
$session = new Session(['name' => 'FOOSession']);

//start session
$session->start();

//overwrite session name
echo $session->name = 'BARSession';

//end session
$session->commit();

//restart session
$session->start();

//show "FOOSession";
echo $session->name;
```

## Methods

### __construct()
Class constructor
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

#### Parameters
*array* **$options**

### setSessionHandler()
This method permits to choose where session store the data, Linna Framework has two builtin handlers: "memcached handler" and "pdo mysql handler".

#### Parameters
*\SessionHandlerInterface* **$handler**

#### Usage
Please refer to [sessionHandler](sessionHandler.md) page

### start()
Start session

#### Usage
```php
$session = new Session();
//start the session
$session->start();
```

### regenerate()
Regenerate session after an event (like a user login/logout or a time interval) occours. Learn more on [session_regenerate_id](http://php.net/manual/en/function.session-regenerate-id.php) reference

#### Usage
```php
$session = new Session();
//start the session
$session->start();

//do some actions

$session->regenerate();
```

### destroy()
Destroy session, clear all session data. Learn more on [session_destroy](http://php.net/manual/en/function.session-destroy.php) reference

#### Usage
```php
$session = new Session();
//start the session
$session->start();

//do some actions

$session->destroy();
```

### commit()
Write session data and end session. Learn more on [session_write_close](http://php.net/manual/en/function.session-write-close.php) reference

#### Usage
```php
$session = new Session();
//start the session
$session->start();

//do some actions

$session->commit();
```
