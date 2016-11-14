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

Methods
- __contruct()
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
//other code
$session->start();

//show current session id
echo $session->id;
```

## Methods

### __contruct()
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
Prepare and use "memcached handler":
```php
//create memcached object
$memcached = new Memcached();

$memcached->addServer('localhost', 11211);

//create memcached session handler object
$sessionHandler = new MemcachedSessionHandler($memcached);
```

Prepare and use "mysql pdo session handler":
```php
use Linna\Database\MysqlPDOAdapter;
use Linna\Database\Database;

//create mysql adpter, it use pdo
$MysqlAdapter = new MysqlPDOAdapter(
    'mysql:host=localhost;dbname=test;charset=utf8',
    'user',
    'password',
    array(\PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_OBJ, \PDO::ATTR_ERRMODE => \PDO::ERRMODE_WARNING)
);

//create data base object
$dataBase = new Database($MysqlAdapter);

//create database session handler object
$sessionHandler = new DatabaseSessionHandler($dataBase);
```

After
```php
//create session object
$session = new Session();
//set session handler
$session->setSessionHandler($sessionHandler);

//start the session
$session->start();
```

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
