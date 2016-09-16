<?php

/**
 * Linna Framework
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2016, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 *
 */

use Linna\Autoloader;
use Linna\Session\Session;
use Linna\Auth\Password;
use Linna\Auth\Login;

use Linna\FOO\FOOProtectedController;
use Linna\FOO\FOOModel;


use PHPUnit\Framework\TestCase;

class ProtectedControllerTest extends TestCase
{
    /**
     * @runInSeparateProcess
     * @outputBuffering disabled
     */
    public function testProtectedController()
    {
        $autoloader = new Autoloader();
        $autoloader->register();
        $autoloader->addNamespaces([
           ['Linna\FOO', dirname(__DIR__).'/FOO']
        ]);
        
        //config options
        Session::withOptions(array(
            'expire' => 5,
            'cookieDomain' => '/',
            'cookiePath' => '/',
            'cookieSecure' => false,
            'cookieHttpOnly' => true
        ));
        
        $password = new Password();
        $storedPassword = $password->hash('password');
        
        //attemp first login
        $login = new Login(Session::getInstance(), $password);
        $login->login('root', 'password', $storedUser = 'root', $storedPassword, 1);
        $loginLogged = $login->logged;
        
        $model = new FOOModel();
        
        
        
        $controller1 = new FOOProtectedController($model, $login);
        $controllerTest1 = $controller1->test;
        
        $login->logout();
        $loginNoLogged = $login->logged;
        
        ob_start();
        
        $controller2 = new FOOProtectedController($model, $login);
        $headers_list = xdebug_get_headers();
        
        ob_end_clean();
        
        $this->assertEquals(true, $loginLogged);
        $this->assertEquals(false, $loginNoLogged);
        $this->assertEquals(true, $controllerTest1);
        $this->assertEquals(true, in_array('Location: http://localhost', $headers_list));
        
        Session::destroyInstance();
    }
}
