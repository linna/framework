<?php

/**
 * Linna Framework.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2017, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */
declare(strict_types=1);

use Linna\DI\DIResolver;
use Linna\FOO\FOOClassACache;
use PHPUnit\Framework\TestCase;

class DIResolverTest extends TestCase
{
    public function testResolve()
    {
        $DIResolver = new DIResolver();

        $FOOClassResObject = $DIResolver->resolve('\Linna\FOO\FOOClassResObject');
        $FOOClassB = $DIResolver->resolve('\Linna\FOO\FOOClassB');
        $FOOClassC = $DIResolver->resolve('\Linna\FOO\FOOClassC');
        $FOOClassD = $DIResolver->resolve('\Linna\FOO\FOOClassD');
        $FOOClassE = $DIResolver->resolve('\Linna\FOO\FOOClassE');
        $FOOClassF = $DIResolver->resolve('\Linna\FOO\FOOClassF');
        $FOOClassG = $DIResolver->resolve('\Linna\FOO\FOOClassG');
        $FOOClassH = $DIResolver->resolve('\Linna\FOO\FOOClassH');
        $FOOClassI = $DIResolver->resolve('\Linna\FOO\FOOClassI');

        $this->assertInstanceOf(\Linna\FOO\FOOClassResObject::class, $FOOClassResObject);
        $this->assertInstanceOf(\Linna\FOO\FOOClassB::class, $FOOClassB);
        $this->assertInstanceOf(\Linna\FOO\FOOClassC::class, $FOOClassC);
        $this->assertInstanceOf(\Linna\FOO\FOOClassD::class, $FOOClassD);
        $this->assertInstanceOf(\Linna\FOO\FOOClassE::class, $FOOClassE);
        $this->assertInstanceOf(\Linna\FOO\FOOClassF::class, $FOOClassF);
        $this->assertInstanceOf(\Linna\FOO\FOOClassG::class, $FOOClassG);
        $this->assertInstanceOf(\Linna\FOO\FOOClassH::class, $FOOClassH);
        $this->assertInstanceOf(\Linna\FOO\FOOClassI::class, $FOOClassI);
    }

    public function testResolveWithoutBackSlash()
    {
        $DIResolver = new DIResolver();

        $FOOClassResObject = $DIResolver->resolve('Linna\FOO\FOOClassResObject');
        $FOOClassB = $DIResolver->resolve('Linna\FOO\FOOClassB');
        $FOOClassC = $DIResolver->resolve('Linna\FOO\FOOClassC');
        $FOOClassD = $DIResolver->resolve('Linna\FOO\FOOClassD');
        $FOOClassE = $DIResolver->resolve('Linna\FOO\FOOClassE');
        $FOOClassF = $DIResolver->resolve('Linna\FOO\FOOClassF');
        $FOOClassG = $DIResolver->resolve('Linna\FOO\FOOClassG');
        $FOOClassH = $DIResolver->resolve('Linna\FOO\FOOClassH');
        $FOOClassI = $DIResolver->resolve('Linna\FOO\FOOClassI');

        $this->assertInstanceOf(\Linna\FOO\FOOClassResObject::class, $FOOClassResObject);
        $this->assertInstanceOf(\Linna\FOO\FOOClassB::class, $FOOClassB);
        $this->assertInstanceOf(\Linna\FOO\FOOClassC::class, $FOOClassC);
        $this->assertInstanceOf(\Linna\FOO\FOOClassD::class, $FOOClassD);
        $this->assertInstanceOf(\Linna\FOO\FOOClassE::class, $FOOClassE);
        $this->assertInstanceOf(\Linna\FOO\FOOClassF::class, $FOOClassF);
        $this->assertInstanceOf(\Linna\FOO\FOOClassG::class, $FOOClassG);
        $this->assertInstanceOf(\Linna\FOO\FOOClassH::class, $FOOClassH);
        $this->assertInstanceOf(\Linna\FOO\FOOClassI::class, $FOOClassI);
    }

    public function testResolveWithCache()
    {
        $DIResolver = new DIResolver();
        $DIResolver->cache('\Linna\FOO\FOOClassACache', new FOOClassACache('DIResolver'));

        $FOOClassResCache = $DIResolver->resolve('Linna\FOO\FOOClassResCache');
        $FOOClassB = $DIResolver->resolve('Linna\FOO\FOOClassB');
        $FOOClassC = $DIResolver->resolve('Linna\FOO\FOOClassC');
        $FOOClassD = $DIResolver->resolve('Linna\FOO\FOOClassD');
        $FOOClassE = $DIResolver->resolve('Linna\FOO\FOOClassE');
        $FOOClassF = $DIResolver->resolve('Linna\FOO\FOOClassF');
        $FOOClassG = $DIResolver->resolve('Linna\FOO\FOOClassG');
        $FOOClassH = $DIResolver->resolve('Linna\FOO\FOOClassH');
        $FOOClassI = $DIResolver->resolve('Linna\FOO\FOOClassI');

        $this->assertInstanceOf(\Linna\FOO\FOOClassResCache::class, $FOOClassResCache);
        $this->assertInstanceOf(\Linna\FOO\FOOClassB::class, $FOOClassB);
        $this->assertInstanceOf(\Linna\FOO\FOOClassC::class, $FOOClassC);
        $this->assertInstanceOf(\Linna\FOO\FOOClassD::class, $FOOClassD);
        $this->assertInstanceOf(\Linna\FOO\FOOClassE::class, $FOOClassE);
        $this->assertInstanceOf(\Linna\FOO\FOOClassF::class, $FOOClassF);
        $this->assertInstanceOf(\Linna\FOO\FOOClassG::class, $FOOClassG);
        $this->assertInstanceOf(\Linna\FOO\FOOClassH::class, $FOOClassH);
        $this->assertInstanceOf(\Linna\FOO\FOOClassI::class, $FOOClassI);
    }

    public function testResolveWithRules()
    {
        $DIResolver = new DIResolver();
        $DIResolver->rules([
            '\Linna\FOO\FOOClassARules' => [
                0 => true,
                2 => 'ciao',
                3 => 1,
                4 => ['ciao'],
                5 => 'prova',
            ],
        ]);

        $FOOClassResRules = $DIResolver->resolve('Linna\FOO\FOOClassResRules');

        $this->assertInstanceOf(\Linna\FOO\FOOClassResRules::class, $FOOClassResRules);
    }

    public function testResolveWithConstructorRules()
    {
        $DIResolver = new DIResolver();
        $rules = [
            '\Linna\FOO\FOOClassARules' => [
                0 => true,
                2 => 'ciao',
                3 => 1,
                4 => ['ciao'],
                5 => 'prova',
            ],
        ];

        $FOOClassARules = $DIResolver->resolve('Linna\FOO\FOOClassARules', $rules);

        $this->assertInstanceOf(\Linna\FOO\FOOClassARules::class, $FOOClassARules);

        $FOOClassResRules = $DIResolver->resolve('Linna\FOO\FOOClassResRules', $rules);

        $this->assertInstanceOf(\Linna\FOO\FOOClassResRules::class, $FOOClassResRules);
    }
}
