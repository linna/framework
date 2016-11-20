---
layout: default
title: Login
current_menu: login
---

# Login
This class help you to manage a login system. Login class checks if user passed data matchs with persistent storage 
data and if true register in to Session the login status. Logout part of Class, delete login status from Session.

## How it works?
Login:
```php
use Linna\Auth\Login;

$user = ''; //user from login page form
$password = ''; //password from login page form

$storedUser = ''; //user from stored user informations
$storedPassword = ''; //password from stored user informations
$storedId = ''; //user id from stored user informations

$login = new Login();
$login->login($user, $password, $storedUser, $storedPassword, $storedId);
```

Logout:
```php
$login = new Login();
$login->logout();
```

Login Check:
```php
$login = new Login();

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
Login class store in session more than $login->data content, the login time with key 'loginTime':
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


## Class Structure

Properties
- data
- logged

Methods
- __contruct()
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

## Methods

### __contruct()

#### Parameters
*Linna\Session\Session* **$session**<br/>
*Linna\Auth\Password* **$password**<br/>

#### Usage

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

### logout()
Do logout clearing login information from session

#### Returns
*bool*
