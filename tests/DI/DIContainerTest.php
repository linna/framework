<?php

/**
 * Linna Framework
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2016, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 *
 */

use Linna\DI\DIContainer;
use Linna\Session\Session;
use Linna\Auth\Password;
use Linna\Auth\Login;

class DIContainerTest extends PHPUnit_Framework_TestCase
{
    protected $session;
    
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
    }
    
    /**
     * @runInSeparateProcess
     */
    public function testContainer()
    {
        $this->initialize();
        
        $container = new DIContainer();
        
        $container->login = function()
        {
            $password = new Password();
            $session = $this->session;
            
            return new Login($session, $password);
        };
        
        $login = $container->login;
        
        $this->assertInstanceOf(Login::class, $login());
        
        //test isset
        if (isset($container->login))
        {
            $login2 = $container->login;
        }
        
        $this->assertInstanceOf(Login::class, $login2());
        
        //test unset
        unset($container->login);
        
        $this->assertEquals(false, isset($container->login));
        
        //test unset container
        $this->assertEquals(false, $container->login);
    }
}