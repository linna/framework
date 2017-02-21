---
layout: default
title: Password
current_menu: password
---

# Password
This class help you to manage passwords

## How it works?
This class is a simple wrapper for PHP [Password Hashing Functions](http://php.net/manual/en/book.password.php).

```php
use Linna\Auth\Password;

//use default options
$password = new Password();

//$hash contain a string like this: $2y$11$cq3ZWO18l68X7pGs9Y1fveTGcNJ/iyehrDZ10BAvbY8LaBXNvnyk6
$hash = $password->hash('FooPassword');
```

## Options
Passed to class constructor as key=>value array

```php
$password = new Password([
    'cost' => 11,
    'algo' => PASSWORD_DEFAULT,
]);
```
| Name           | Default          | Description                                                                            |
|----------------|------------------|----------------------------------------------------------------------------------------|
| cost           | 11               | indicating key expansion rounds                                                        |
| algo           | PASSWORD_DEFAULT | password algorithm denoting the algorithm to use when hashing the password             |

for password algorithm constants see [Password Constants](http://php.net/manual/en/password.constants.php).

## Calculate good cost
```php
/**
 * Get appropriate cost 
 * 
 * This code will benchmark your server to determine how high of a cost you can
 * afford. You want to set the highest cost that you can without slowing down
 * you server too much.
 * 
 * @param int $time_limit Time limit in milliseconds
 * @param int $algo Algoritm, default PASSWORD_DEFAULT
 * @return int 
 */
function password_get_appropriate_cost(int $time_limit, int $algo = PASSWORD_DEFAULT) : int
{
    //set start cost
    $cost = 3;
    
    do {
        //increase cost
        $cost++;
        
        //check if cost is out of range for bcrypt
        if ($algo === 1 && ($cost < 4 || $cost > 31)) {
            break;
        }
        
        //start time
        $start = microtime(true);
        
        //generate password
        password_hash("test", $algo, ["cost" => $cost]);
        
        //stop time
        $end = microtime(true);
        
    } while (($end - $start) < ($time_limit / 100));
    
    return $cost;
}
```

## Class Structure

Properties
- *no public properties*

Methods
- __construct()
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
var_dump($info);
```