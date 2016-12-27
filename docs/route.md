---
layout: default
title: Route
current_menu: route
---

# Route
Is the class that describe a route and is the only object that Router class returns.

## How it works?
For more details about routes, please see [router page](router.md).

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

$router = new Router($routes, $options);

$uri = $_SERVER['REQUEST_URI'];
$method = $_SERVER['REQUEST_METHOD'];

$router->validate($uri, $method);

$route = $router->getRoute();

// assume that request uri was /
var_dump($route->getModel()); //string HomeModel
var_dump($route->getView()); //string HomeView
var_dump($route->getController()); //string HomeController
var_dump($route->getAction()); //string void
var_dump($route->getParam()); //array void
```

## Class Structure

Properties
- *no public properties*

Methods
- getModel()
- getView()
- getController()
- getAction()
- getParam()

## Methods

### getModel()
Returns Model name for Route

#### Returns
*string*

### getView()
Returns View name for Route

#### Returns
*string*

### getController()
Returns Controller name for Route

#### Returns
*string*

### getAction()
Returns Action for Route

#### Returns
*string*

### getParam()
Returns Params for Route

#### Returns
*array*
