<?php

/**
 * Linna Framework
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2016, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 *
 */

use PHPUnit\Framework\TestCase;
use Linna\Http\Router;
use Linna\Http\Route;

class RouterTest extends TestCase
{
    public function testRouter()
    {
        $appRoutes = array();

        $appRoutes[] = [
            'name' => 'Home',
            'method' => 'GET',
            'url' => '/',
            'model' => 'HomeModel',
            'view' => 'HomeView',
            'controller' => 'HomeController',
            'action' => null,
        ];

        $appRoutes[] = [
            'name' => 'E404',
            'method' => 'GET',
            'url' => '/error',
            'model' => 'E404Model',
            'view' => 'E404View',
            'controller' => 'E404Controller',
            'action' => null,
        ];

        $appRoutes[] = [
            'name' => 'User',
            'method' => 'GET',
            'url' => '/user',
            'model' => 'UserModel',
            'view' => 'UserView',
            'controller' => 'UserController',
            'action' => null,
        ];
        
        //start router
        $router = new Router('/user', $appRoutes, array(
            'basePath' => '/',
            'badRoute' => 'E404',
            'rewriteMode' => true
                ));

        //get route
        $route = $router->getRoute();
        
        $this->assertInstanceOf(Route::class, $route);
        
        
    }    
}