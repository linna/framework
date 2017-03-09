---
layout: default
title: Login
current_menu: login
---

# Authenticate
This class helps you to manage a login system and can be considered an extension of Linna Session. 

## How it works?
This class manage the login status for current session. On login action, Authenticate class checks if provided user data matches with 
user's persistent storage data. If true, Authenticate registers into Session the new login data. Logout part of the Class, deletes login data from Session. At every page refresh Authenticate class update automatically login data in session.<br/>

Also this class allow to check if there is a valid login inside session.

Login data in session look like this array below:
```php
//after session start and login, session data appear like below array:
[
    'time' => 1479641396
    'expire' => 1800
    'loginTime' => 1479641395
    'login' => [
        'login' => true
        'user_id' => 1
        'user_name' => 'root'
    ]
]
```

## Dependency
[Session](session.md) and [Password](password.md) classes

## Class Structure

Properties
- data
- logged

Methods
- __construct()
- login()
- logout()

## Properties

### data
type: *array*<br/>

#### Usage
This property allow access to login data.<br/>
Data has this format:
```php
//login data example
[
    'login' => true,
    'user_id' => 1,
    'user_name'=> 'root'
]
```

### logged
type: *bool*<br/>

Return login status.

#### Usage
```php
$login = new Authenticate($session, $password);

if ($login->logged === true)
{
    //do actions
}

// or

if ($login->logged !== true)
{
    //redirect
}

//do actions
```

## Methods

### __construct()

#### Parameters
*Linna\Session\Session* **$session**<br/>
*Linna\Auth\Password* **$password**<br/>

#### Usage
```php
use Linna\Auth\Authenticate;
use Linna\Auth\Password;
use Linna\Session\Session;

$password = new Password();
$session = new Session();

$login = new Authenticate($session, $password);

```
### login()
Do login if passed data match and return true, return false and do nothing if data doesn't match

#### Parameters
*string* **$user**<br/>
*string* **$password**<br/>
*string* **$storedUser**<br/>
*string* **$storedPassword**<br/>
*int* **$storedId**<br/>

#### Returns
*bool*

#### Usage
```php
$user = ''; //user from login page form
$password = ''; //password from login page form

$storedUser = ''; //user from stored user informations
$storedPassword = ''; //password from stored user informations
$storedId = ''; //user id from stored user informations

$login = new Authenticate($session, $password);
$login->login($user, $password, $storedUser, $storedPassword, $storedId);
```

### logout()
Do logout clearing login information from session

#### Returns
*bool*

#### Usage
```php
$login = new Authenticate($session, $password);
$login->logout();
```
