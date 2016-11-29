---
layout: default
title: Password
current_menu: password
---

# Password
This class help you to manage passwords

## How it works?
This class is a simple wrapper for PHP [Password Hashing Functions](http://php.net/manual/en/book.password.php).
At this state of develop Password class has for default options only 'cost' set to value 11.

```php
use Linna\Auth\Password;

$password = new Password();

//$hash contain a string like this: $2y$11$cq3ZWO18l68X7pGs9Y1fveTGcNJ/iyehrDZ10BAvbY8LaBXNvnyk6
$hash = $password->hash('FooPassword');
```

## Class Structure

Properties
- *no public properties*

Methods
- __contruct()
- verify()
- hash()
- needsRehash()
- getInfo()

## Methods

### __construct()

### verify()
Verifies that a password matches a hash

#### Parameters
*string* **$password**<br/>
*string* **$hash**<br/>

#### Returns
*bool*

#### Usage
```php
$password = new Password();

//password hash, for example you get it from persistent storage
$hash = '$2y$11$cq3ZWO18l68X7pGs9Y1fveTGcNJ/iyehrDZ10BAvbY8LaBXNvnyk6';

//word for hash comparing, for example passed by an user into web form
$passwordString = 'FooPassword';


$passwordCheck = $password->verify($passwordString, $hash);
```

### hash()
Create a password hash

#### Parameters
*string* **$password**<br/>

#### Returns
*string*

#### Usage
```php
$password = new Password();

//$hash contain a string like this: $2y$11$cq3ZWO18l68X7pGs9Y1fveTGcNJ/iyehrDZ10BAvbY8LaBXNvnyk6
$hash = $password->hash('FooPassword');
```

### needsRehash()
Checks if the given hash matches the given options

#### Parameters
*string* **$hash**<br/>

#### Returns
*bool*

#### Usage
```php
$password = new Password();

$hash = '$2y$11$cq3ZWO18l68X7pGs9Y1fveTGcNJ/iyehrDZ10BAvbY8LaBXNvnyk6';

//true if rehash is needed, false if no
$rehashCheck = $password->needsRehash($hash);
```

### getInfo()
Returns information about the given hash

#### Parameters
*string* **$hash**<br/>

#### Returns
*array*

#### Usage
```php
$password = new Password();

$hash = '$2y$11$cq3ZWO18l68X7pGs9Y1fveTGcNJ/iyehrDZ10BAvbY8LaBXNvnyk6';

$info = $password->getInfo($hash);

//show an array like this:
//[
// 'algo' => 1,
// 'algoName' => 'bcrypt',
// 'options' => [
//      'cost' => int 11
// ]
//]
vardump($info);
```