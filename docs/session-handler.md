---
layout: default
title: Session Handler
current_menu: session-handler
---

# Session Handler
Linna framework has two session handlers built in, MysqlPdoSessionHandler and MemcachedSessionHandler. These classes
implements php interface [SessionHandlerInterface](http://tr2.php.net/manual/en/class.sessionhandlerinterface.php).<br/><br/>
*If you wish implement your session handler follow php documentation instructions.*

## MysqlPdoSessionHandler class
Store session in Mysql data base
```php
use Linna\Storage\MysqlPdoAdapter;
use Linna\Storage\Database;

//create mysql adpter, it use pdo
$mysqlPdoAdapter = new MysqlPdoAdapter(
    'mysql:host=localhost;dbname=test;charset=utf8mb4',
    'user',
    'password',
    array(\PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_OBJ, \PDO::ATTR_ERRMODE => \PDO::ERRMODE_WARNING)
);

//get connection
$dataBase = $mysqlPdoAdapter->getResource();

//create mysql pdo session handler object
$sessionHandler = new MysqlPdoSessionHandler($dataBase);

//create session object
$session = new Session();
//set session handler
$session->setSessionHandler($sessionHandler);

//start the session
$session->start();
```

## MemcachedSessionHandler class
Store session in memcached object caching system
```php
//create memcached object
$memcached = new Memcached();

$memcached->addServer('localhost', 11211);

//create memcached session handler object, 1800 was expire time in seconds
$sessionHandler = new MemcachedSessionHandler($memcached, 1800);

//create session object
$session = new Session();
//set session handler
$session->setSessionHandler($sessionHandler);

//start the session
$session->start();
```

### Why another memcached session handler?
Because if you don't have possibility to change php.ini config, 
with this you can anyway store sessions in memcached