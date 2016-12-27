---
layout: default
title: Router
current_menu: router
---

# Router
The Linna Framework url routing class, it combine url with application code.

## How it works?
Router instance must be created passing to constructor an array contains all valid routes and router options.
```php
use Linna\Http\Router;

$router = new Router($routes, $options);
```
Now router is ready for evaluate URLs.

### Routes example
Below routes example, is necessary always declare one valide route and one bad route.
```php
$routes = array();

// index route
$routes[] = [
    'name' => 'Home',
    'method' => 'GET',
    'url' => '/',
    'model' => 'HomeModel',
    'view' => 'HomeView',
    'controller' => 'HomeController',
    'action' => '',
];

//404 page route
$routes[] = [
    'name' => 'E404',
    'method' => 'GET',
    'url' => '/error',
    'model' => 'E404Model',
    'view' => 'E404View',
    'controller' => 'E404Controller',
    'action' => '',
];
```
| Name           | Description                                                                            |
|----------------|----------------------------------------------------------------------------------------|
| name           | route name, for reverse routing (to do)                                                |
| method         | valid request method for this route                                                    |
| url            | route url                                                                              |
| model          | model name                                                                             |
| view           | view name                                                                              |
| controller     | controller name                                                                        |
| action         | specify an action for controller                                                       |

### Options
```php
$options =  [
    'basePath' => '/',
    'badRoute' => 'E404',
    'rewriteMode' => true
];
```
| Name           | Default     | Description                                                                            |
|----------------|-------------|----------------------------------------------------------------------------------------|
| basePath       | /           | base path for all urls                                                                 |
| badRoute       | void string | route name for 404 page                                                                |
| rewriteMode    | false       | true if you use rewrite engine, false if no                                            |

basePath explaination:<br />
if you have **/var/www/html/** as webserver doc directory and App code is placed under it, set basePath to **/**<br/>
if you place App code inside a subdirectory as **/var/www/html/app/**, set basePath to **/app/**

## Class Structure

Properties
- *no public properties*

Methods
- __construct()
- validate()
- getRoute()

## Methods

### __construct()
Class constructor
```php
$router = new Router($routes, $options);
```
#### Parameters
*array* **$routes**<br/>
*array* **$options**

### validate()
Evaluate request uri
```php
$router = new Router($routes, $options);

$uri = $_SERVER['REQUEST_URI'];
$method = $_SERVER['REQUEST_METHOD'];

$router->validate($uri, $method);
```

#### Parameters
*string* **$requestUri**<br/>
*string* **$requestMethod**

#### Returns
*bool*

## getRoute()
Get [route](route.md) object from router
```php
$router = new Router($routes, $options);

$uri = $_SERVER['REQUEST_URI'];
$method = $_SERVER['REQUEST_METHOD'];

$router->validate($uri, $method);

$route = $router->getRoute();
```

#### Returns
*Route object*