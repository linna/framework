<?php

/**
 * Linna Framework
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2017, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 *
 */

declare(strict_types=1);

use Linna\DI\DIContainer;
use Linna\FOO\FOOClassResCache;
use Linna\FOO\FOOClassACache;
use Linna\FOO\FOOClassB;
use Linna\FOO\FOOClassC;
use Linna\FOO\FOOClassD;
use Linna\FOO\FOOClassE;
use Linna\FOO\FOOClassF;
use Linna\FOO\FOOClassG;
use Linna\FOO\FOOClassH;
use Linna\FOO\FOOClassI;
use PHPUnit\Framework\TestCase;

class DIContainerTest extends TestCase
{
    public function testContainer()
    {
        $container = new DIContainer();
        
        $container->FOOClassResCache = function () {
            $i = new FOOClassI();
            $h = new FOOClassH();
            $g = new FOOClassG($i, $h);
            $f = new FOOClassF();
            $e = new FOOClassE();
            $d = new FOOClassD($e, $f, $g);
            $c = new FOOClassC($g);
            $b = new FOOClassB($c, $d);
            $aa = new FOOClassACache('DIContainer');
                    
            return new FOOClassResCache($b, $aa);
        };
        
        $FOOClassResCache = $container->FOOClassResCache;
        
        $this->assertInstanceOf(FOOClassResCache::class, $FOOClassResCache);
        
        $this->assertEquals(true, isset($container->FOOClassResCache));
        $this->assertEquals(false, isset($container->FOOClassB));
        $this->assertEquals(false, $container->FOOClassB);
    }
}
