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
use PHPUnit\Framework\TestCase;

class DIContainerTest extends TestCase
{
    /**
     * @runInSeparateProcess
     */
    public function testContainer()
    {
        //se session options
        Session::withOptions(array(
            'expire' => 4,
            'cookieDomain' => '/',
            'cookiePath' => '/',
            'cookieSecure' => false,
            'cookieHttpOnly' => true
        ));
                
        $container = new DIContainer();
        
        $container->login = function () {
            $password = new Password();
            $session = Session::getInstance();
            
            return new Login($session, $password);
        };
        
        $login = $container->login;
        
        $this->assertInstanceOf(Login::class, $login());
        
        $this->assertEquals(true, isset($container->login));
        $this->assertEquals(false, isset($container->login2));
        $this->assertEquals(false, $container->login2);
        
        Session::destroyInstance();
    }
}
