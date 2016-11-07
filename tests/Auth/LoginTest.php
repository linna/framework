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
        $session = new Session();
        $session->start();
        
        $password = new Password();
        $storedPassword = $password->hash('password');
        
        //attemp first login
        $login = new Login($session, $password);
        $loginResult = $login->login('root', 'password', $storedUser = 'root', $storedPassword, 1);
        
        //attemp check if logged
        $newLogin = new Login($session, $password);
        $logged = $newLogin->logged;
        
        //simulate expired login
        $_SESSION['loginTime'] = time() - 3600;
        $secondLogin = new Login($session, $password);
        $notLogged = $secondLogin->logged;
        
        $this->assertEquals(true, $loginResult);
        $this->assertEquals(true, $logged);
        $this->assertEquals(false, $notLogged);
        
        $session->destroy();
    }
    
     /**
     * @runInSeparateProcess
     */
    public function testLogout()
    {
        //config options
        $session = new Session();
        $session->start();
        
        
        $password = new Password();
        $storedPassword = $password->hash('password');
        
        //do valid login
        $login = new Login($session, $password);
        $login->login('root', 'password', $storedUser = 'root', $storedPassword, 1);
        $loginResult = $login->logged;
        
        //do logout
        $login->logout();
        
        //create new login instance
        $login = new Login($session, $password);
        $noLoginResult = $login->logged;
        
        $this->assertEquals(true, $loginResult);
        $this->assertEquals(false, $noLoginResult);
        
        $session->destroy();
    }
    
    /**
     * @runInSeparateProcess
     */
    public function testIncorrectLogin()
    {
        //config options
        $session = new Session();
        
        $session->start();
        
        $password = new Password();
        $storedPassword = $password->hash('password');
        
        //try login with bad credentials
        $login = new Login($session, $password);
        $loginResult = $login->login('root', 'badPassword', $storedUser = 'root', $storedPassword, 1);
        $loginResult2 = $login->login('badUser', 'password', $storedUser = 'root', $storedPassword, 1);
        
        $this->assertEquals(false, $loginResult);
        $this->assertEquals(false, $loginResult2);
        
        $session->destroy();
    }
}
