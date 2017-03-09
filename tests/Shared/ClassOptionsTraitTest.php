<?php

/**
 * Linna Framework.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2017, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */
declare(strict_types=1);

use Linna\Http\Router;
use PHPUnit\Framework\TestCase;

class ClassOptionsTest extends TestCase
{
    public function testSetOption()
    {
        $router = new Router([]);
        $router->setOption('badRoute', 'foo');
        
        $this->assertEquals(true, true);
    }
    
    public function testSetBadOption()
    {
        $router = new Router([]);
        
        $this->expectException(\InvalidArgumentException::class);
        $router->setOption('badRout', 'foo');
    }
    
    public function testSetOptions()
    {
        $router = new Router([]);
        $router->setOptions([
            'basePath'    => '/',
            'badRoute'    => 'E404',
            'rewriteMode' => true,
        ]);
        
        $this->assertEquals(true, true);
    }
}
