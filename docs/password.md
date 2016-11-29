---
layout: default
title: Password
current_menu: password
---

# Password
This class help you to manage passwords

## How it works?
This class is a simple wrapper for PHP [Password Hashing Functions](http://php.net/manual/en/book.password.php)

```php
use Linna\Auth\Password;

$password = new Password();

$hash = $password->hash('FooPassword');

//$hash contain a string like this: $2y$11$cq3ZWO18l68X7pGs9Y1fveTGcNJ/iyehrDZ10BAvbY8LaBXNvnyk6
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

#### Parameters
*string* **$password**<br/>
*string* **$hash**<br/>

#### Returns
*bool*

#### Usage
```php
```

### hash()

#### Parameters
*string* **$password**<br/>

#### Returns
*string*

#### Usage
```php
```

### needsRehash()

#### Parameters
*string* **$hash**<br/>

#### Returns
*bool*

#### Usage
```php
```

### getInfo()

#### Parameters
*string* **$hash**<br/>

#### Returns
*array*

#### Usage
```php
```