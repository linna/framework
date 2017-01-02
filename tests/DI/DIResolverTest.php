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

use Linna\DI\DIResolver;
use Linna\FOO\FOOClassAA;
use PHPUnit\Framework\TestCase;

class DIResolverTest extends TestCase
{
    public function testResolve()
    {
        $DIResolver = new DIResolver();
        $DIResolver->cacheUnResolvable('\Linna\FOO\FOOClassAA', new FOOClassAA('DIResolver'));
        
        
        $FOOClassA = $DIResolver->resolve('\Linna\FOO\FOOClassA');
        $FOOClassB = $DIResolver->resolve('\Linna\FOO\FOOClassB');
        $FOOClassC = $DIResolver->resolve('\Linna\FOO\FOOClassC');
        $FOOClassD = $DIResolver->resolve('\Linna\FOO\FOOClassD');
        $FOOClassE = $DIResolver->resolve('\Linna\FOO\FOOClassE');
        $FOOClassF = $DIResolver->resolve('\Linna\FOO\FOOClassF');
        $FOOClassG = $DIResolver->resolve('\Linna\FOO\FOOClassG');
        $FOOClassH = $DIResolver->resolve('\Linna\FOO\FOOClassH');
        $FOOClassI = $DIResolver->resolve('\Linna\FOO\FOOClassI');
        
        $this->assertInstanceOf(\Linna\FOO\FOOClassA::class, $FOOClassA);
        $this->assertInstanceOf(\Linna\FOO\FOOClassB::class, $FOOClassB);
        $this->assertInstanceOf(\Linna\FOO\FOOClassC::class, $FOOClassC);
        $this->assertInstanceOf(\Linna\FOO\FOOClassD::class, $FOOClassD);
        $this->assertInstanceOf(\Linna\FOO\FOOClassE::class, $FOOClassE);
        $this->assertInstanceOf(\Linna\FOO\FOOClassF::class, $FOOClassF);
        $this->assertInstanceOf(\Linna\FOO\FOOClassG::class, $FOOClassG);
        $this->assertInstanceOf(\Linna\FOO\FOOClassH::class, $FOOClassH);
        $this->assertInstanceOf(\Linna\FOO\FOOClassI::class, $FOOClassI);
    }
}
