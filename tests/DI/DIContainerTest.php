<?php

/**
 * Linna Framework
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2016, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 *
 */

declare(strict_types=1);

use Linna\DI\DIContainer;
use Linna\FOO\FOOClassA;
use Linna\FOO\FOOClassAA;
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
        
        $container->FOOClassA = function () {
            $i = new FOOClassI();
            $h = new FOOClassH();
            $g = new FOOClassG($i, $h);
            $f = new FOOClassF();
            $e = new FOOClassE();
            $d = new FOOClassD($e, $f, $g);
            $c = new FOOClassC($g);
            $b = new FOOClassB($c, $d);
            $aa = new FOOClassAA('DIContainer');
                    
            return new FOOClassA($b, $aa);
        };
        
        $FOOClassA = $container->FOOClassA;
        
        $this->assertInstanceOf(FOOClassA::class, $FOOClassA);
        
        $this->assertEquals(true, isset($container->FOOClassA));
        $this->assertEquals(false, isset($container->FOOClassB));
        $this->assertEquals(false, $container->FOOClassB);
    }
}
