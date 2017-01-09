<?php

/**
 * Linna Framework
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2017, Sebastian Rapetti
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
        if (!function_exists('xdebug_get_headers')) {
            $this->markTestSkipped('Xdebug not installed');
        }
        
        $autoloader = new Autoloader();
        $autoloader->register();
        $autoloader->addNamespaces([
           ['Linna\FOO', dirname(__DIR__).'/FOO']
        ]);
        
        //config options
        $session = new Session();
        
        $session->start();
        
        $password = new Password();
        $storedPassword = $password->hash('password');
        $storedUser = 'root';
        
        //attemp first login
        $login = new Login($session, $password);
        $login->login('root', 'password', $storedUser, $storedPassword, 1);
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
        
        $session->destroy();
    }
}
