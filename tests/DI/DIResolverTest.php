<?php

/**
 * Linna Framework
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2016, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 *
 */

use Linna\DI\DIResolver;
use Linna\Session\Session;
use Linna\Auth\Login;

class DIResolverTest extends PHPUnit_Framework_TestCase
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
    public function testResolve()
    {
        $this->initialize();
        
        $DIResolver = new DIResolver();
        
        $DIResolver->cacheUnResolvable('\Linna\Session\Session', Session::getInstance());
        
        $login = $DIResolver->resolve('\Linna\Auth\Login');
        
        $this->assertInstanceOf(Login::class, $login);
    }
}
