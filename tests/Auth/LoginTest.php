<?php

/**
 * Linna Framework
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2016, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 *
 */

use Linna\Session\Session;
use Linna\Auth\Password;
use Linna\Auth\Login;
use PHPUnit\Framework\TestCase;

class LoginTest extends TestCase
{
    /**
     * @runInSeparateProcess
     */
    public function testLogin()
    {
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
        $loginResult = $login->login('root', 'password', $storedUser = 'root', $storedPassword, 1);
        
        //attemp check if logged
        $newLogin = new Login(Session::getInstance(), $password);
        $logged = $newLogin->logged;
        
        //simulate expired login
        $_SESSION['loginTime'] = time() - 3600;
        $secondLogin = new Login(Session::getInstance(), $password);
        $notLogged = $secondLogin->logged;
        
        $this->assertEquals(true, $loginResult);
        $this->assertEquals(true, $logged);
        $this->assertEquals(false, $notLogged);
        
        Session::destroyInstance();
    }
    
     /**
     * @runInSeparateProcess
     */
    public function testLogout()
    {
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
        
        //do valid login
        $login = new Login(Session::getInstance(), $password);
        $login->login('root', 'password', $storedUser = 'root', $storedPassword, 1);
        $loginResult = $login->logged;
        
        //do logout
        $login->logout();
        
        //create new login instance
        $login = new Login(Session::getInstance(), $password);
        $noLoginResult = $login->logged;
        
        $this->assertEquals(true, $loginResult);
        $this->assertEquals(false, $noLoginResult);
        
        
        Session::destroyInstance();
    }
    
    /**
     * @runInSeparateProcess
     */
    public function testIncorrectLogin()
    {
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
        
        //try login with bad credentials
        $login = new Login(Session::getInstance(), $password);
        $loginResult = $login->login('root', 'badPassword', $storedUser = 'root', $storedPassword, 1);
        $loginResult2 = $login->login('root', 'password', $storedUser = null, $storedPassword, 1);
        
        $this->assertEquals(false, $loginResult);
        $this->assertEquals(false, $loginResult2);
        
        Session::destroyInstance();
    }
}
