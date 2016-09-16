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
    protected $session;
            
    protected $password;
            
    protected $login;
    
    protected function initialize()
    {
        //se session options
        Session::withOptions(array(
            'expire' => 1800,
            'cookieDomain' => '/',
            'cookiePath' => '/',
            'cookieSecure' => false,
            'cookieHttpOnly' => true
        ));
        
        $this->session = Session::getInstance();
        
        $this->password = new Password();
        
        $this->login = new Login($this->session, $this->password);
    }
    
    /**
     * @runInSeparateProcess
     */
    public function testLogin()
    {
        $this->initialize();
        
        $storedPassword = $this->password->hash('password');
        
        $loginResult = $this->login->login('root', 'password', $storedUser = 'root', $storedPassword, 1);
         
        $this->assertEquals(true, $loginResult);
        
        $newLogin = new Login($this->session, $this->password);
        
        $logged = $newLogin->logged;
        
        $this->assertEquals(true, $logged);
        
        $_SESSION['loginTime'] = time() - 3600;
        
        $newLogin = new Login($this->session, $this->password);
        
        $logged = $newLogin->logged;
        
        $this->assertEquals(false, $logged);
    }
    
     /**
     * @runInSeparateProcess
     */
    public function testLogout()
    {
        $this->initialize();
        
        $storedPassword = $this->password->hash('password');
        
        $loginResult = $this->login->login('root', 'password', $storedUser = 'root', $storedPassword, 1);
        
        $this->assertEquals(true, $loginResult);
        
        $this->login->logout();
    }
    
    /**
     * @runInSeparateProcess
     */
    public function testIncorrectLogin()
    {
        $this->initialize();
        
        $storedPassword = $this->password->hash('password');
        
        $loginResult = $this->login->login('root', 'badPassword', $storedUser = 'root', $storedPassword, 1);
        
        $this->assertEquals(false, $loginResult);
        
        $loginResult = $this->login->login('root', 'password', $storedUser = null, $storedPassword, 1);
        
        $this->assertEquals(false, $loginResult);
    }
}
