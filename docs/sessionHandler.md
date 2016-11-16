---
layout: default
title: Session Handler
current_menu: sessionHandler
---

# Session Handler
Linna framework has two session handlers built in, DatabaseSessionHandler and MemcachedSessionHandler. These classes
implements php interface [SessionHandlerInterface](http://tr2.php.net/manual/en/class.sessionhandlerinterface.php).<br/>
If you wish implement your session handler follow php documentation instructions.

## DatabaseSessionHandler class
Store session in data base
```php
use Linna\Database\MysqlPDOAdapter;
use Linna\Database\Database;

//create mysql adpter, it use pdo
$MysqlAdapter = new MysqlPDOAdapter(
    'mysql:host=localhost;dbname=test;charset=utf8mb4',
    'user',
    'password',
    array(\PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_OBJ, \PDO::ATTR_ERRMODE => \PDO::ERRMODE_WARNING)
);

//create data base object
$dataBase = new Database($MysqlAdapter);

//create database session handler object
$sessionHandler = new DatabaseSessionHandler($dataBase);

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

//create memcached session handler object
$sessionHandler = new MemcachedSessionHandler($memcached);

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