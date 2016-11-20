---
layout: default
title: Login
current_menu: login
---

# Login
This class help you to manage a login system. Login class checks if user passed data matchs with persistent storage 
data and if true register in to Session the login status. Logout part of Class, delete login status from Session.

## Basic usage
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

### logged
type: *bool*<br/>

#### Usage

## Methods

### __contruct()

#### Parameters
*Linna\Session\Session* **$session**<br/>
*Linna\Auth\Password* **$password**<br/>

#### Usage

### login()

#### Parameters
*string* **$user**<br/>
*string* **$password**<br/>
*string* **$storedUser**<br/>
*string* **$storedPassword**<br/>
*int* **$storedId**<br/>

#### Returns
*bool*

#### Usage

### logout()

#### Returns
*bool*

#### Usage
