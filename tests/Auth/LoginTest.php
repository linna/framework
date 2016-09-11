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

class LoginTest extends PHPUnit_Framework_TestCase
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
    public function testValidLogin()
    {
        $this->initialize();
        
        $storedPassword = $this->password->hash('password');
        
        $loginResult = $this->login->login('root', 'password', $storedUser = 'root', $storedPassword, 1);
        
        $this->assertEquals(true, $loginResult);
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
    }
}