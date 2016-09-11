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
use Linna\Session\Session;
use Linna\Auth\Password;
use Linna\Auth\Login;

class LoginTest extends TestCase
{
    /**
     * @runInSeparateProcess
     */
    public function testLogin()
    {
        //se session options
        Session::withOptions(array(
            'expire' => 1800,
            'cookieDomain' => '/',
            'cookiePath' => '/',
            'cookieSecure' => false,
            'cookieHttpOnly' => true
        ));
        
        $session = Session::getInstance();
        $password = new Password();
        
        $storedPassword = $password->hash('password');
        
        $login = new Login($session, $password);
        
        $loginResult = $login->login('root', 'password', $storedUser = 'root', $storedPassword, 1);
        
        $this->assertEquals(true, $loginResult);
    }    
}