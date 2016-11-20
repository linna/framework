---
layout: default
title: Login
current_menu: login
---

# Login
This class helps you to manage a login system and can be considered an extention of Linna Session. 
Login class, checks if provided user data matches with user's persistent storage data. 
If true, Login registers into Session the login status. Logout part of the Class, deletes login status from Session.

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

Return login status.

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
