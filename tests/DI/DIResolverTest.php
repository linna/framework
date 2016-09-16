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
use PHPUnit\Framework\TestCase;

class DIResolverTest extends TestCase
{
    /**
     * @runInSeparateProcess
     */
    public function testResolve()
    {
        Session::withOptions(array(
            'expire' => 3,
            'cookieDomain' => '/',
            'cookiePath' => '/',
            'cookieSecure' => false,
            'cookieHttpOnly' => true
        ));
        
        $DIResolver = new DIResolver();
        
        $DIResolver->cacheUnResolvable('\Linna\Session\Session', Session::getInstance());
        
        $login = $DIResolver->resolve('\Linna\Auth\Login');
        
        $this->assertInstanceOf(Login::class, $login);
        
        Session::destroyInstance();
    }
}
