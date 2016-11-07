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
        $session = new Session();
        $session->start();
        
        $DIResolver = new DIResolver();
        
        $DIResolver->cacheUnResolvable('\Linna\Session\Session', $session);
        
        $login = $DIResolver->resolve('\Linna\Auth\Login');
        
        $this->assertInstanceOf(Login::class, $login);
        
        $session->destroy();
    }
}
