<?php

/**
 * Linna Framework.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2017, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */
declare(strict_types=1);

use Linna\DI\Resolver;
use Linna\Foo\DI\FooClassACache;
use PHPUnit\Framework\TestCase;

class ResolverTest extends TestCase
{
    public function testResolve()
    {
        $DIResolver = new Resolver();

        $FOOClassResObject = $DIResolver->resolve('\Linna\Foo\DI\FooClassResObject');
        $FOOClassB = $DIResolver->resolve('\Linna\Foo\DI\FooClassB');
        $FOOClassC = $DIResolver->resolve('\Linna\Foo\DI\FooClassC');
        $FOOClassD = $DIResolver->resolve('\Linna\Foo\DI\FooClassD');
        $FOOClassE = $DIResolver->resolve('\Linna\Foo\DI\FooClassE');
        $FOOClassF = $DIResolver->resolve('\Linna\Foo\DI\FooClassF');
        $FOOClassG = $DIResolver->resolve('\Linna\Foo\DI\FooClassG');
        $FOOClassH = $DIResolver->resolve('\Linna\Foo\DI\FooClassH');
        $FOOClassI = $DIResolver->resolve('\Linna\Foo\DI\FooClassI');

        $this->assertInstanceOf(\Linna\Foo\DI\FooClassResObject::class, $FOOClassResObject);
        $this->assertInstanceOf(\Linna\Foo\DI\FooClassB::class, $FOOClassB);
        $this->assertInstanceOf(\Linna\Foo\DI\FooClassC::class, $FOOClassC);
        $this->assertInstanceOf(\Linna\Foo\DI\FooClassD::class, $FOOClassD);
        $this->assertInstanceOf(\Linna\Foo\DI\FooClassE::class, $FOOClassE);
        $this->assertInstanceOf(\Linna\Foo\DI\FooClassF::class, $FOOClassF);
        $this->assertInstanceOf(\Linna\Foo\DI\FooClassG::class, $FOOClassG);
        $this->assertInstanceOf(\Linna\Foo\DI\FooClassH::class, $FOOClassH);
        $this->assertInstanceOf(\Linna\Foo\DI\FooClassI::class, $FOOClassI);
    }

    public function testResolveWithoutBackSlash()
    {
        $DIResolver = new Resolver();

        $FOOClassResObject = $DIResolver->resolve('Linna\Foo\DI\FooClassResObject');
        $FOOClassB = $DIResolver->resolve('Linna\Foo\DI\FooClassB');
        $FOOClassC = $DIResolver->resolve('Linna\Foo\DI\FooClassC');
        $FOOClassD = $DIResolver->resolve('Linna\Foo\DI\FooClassD');
        $FOOClassE = $DIResolver->resolve('Linna\Foo\DI\FooClassE');
        $FOOClassF = $DIResolver->resolve('Linna\Foo\DI\FooClassF');
        $FOOClassG = $DIResolver->resolve('Linna\Foo\DI\FooClassG');
        $FOOClassH = $DIResolver->resolve('Linna\Foo\DI\FooClassH');
        $FOOClassI = $DIResolver->resolve('Linna\Foo\DI\FooClassI');

        $this->assertInstanceOf(\Linna\Foo\DI\FooClassResObject::class, $FOOClassResObject);
        $this->assertInstanceOf(\Linna\Foo\DI\FooClassB::class, $FOOClassB);
        $this->assertInstanceOf(\Linna\Foo\DI\FooClassC::class, $FOOClassC);
        $this->assertInstanceOf(\Linna\Foo\DI\FooClassD::class, $FOOClassD);
        $this->assertInstanceOf(\Linna\Foo\DI\FooClassE::class, $FOOClassE);
        $this->assertInstanceOf(\Linna\Foo\DI\FooClassF::class, $FOOClassF);
        $this->assertInstanceOf(\Linna\Foo\DI\FooClassG::class, $FOOClassG);
        $this->assertInstanceOf(\Linna\Foo\DI\FooClassH::class, $FOOClassH);
        $this->assertInstanceOf(\Linna\Foo\DI\FooClassI::class, $FOOClassI);
    }

    public function testResolveWithCache()
    {
        $resolver = new Resolver();
        $resolver->cache('\Linna\Foo\DI\FooClassACache', new FooClassACache('DIResolver'));

        $FOOClassResCache = $resolver->resolve('Linna\Foo\DI\FooClassResCache');

        $this->assertInstanceOf(\Linna\Foo\DI\FooClassResCache::class, $FOOClassResCache);
    }

    public function testResolveWithRules()
    {
        $resolver = new Resolver();
        $resolver->rules([
            '\Linna\Foo\DI\FooClassARules' => [
                0 => true,
                2 => 'foo',
                3 => 1,
                4 => ['foo'],
                5 => 'foo',
            ],
        ]);

        $FOOClassResRules = $resolver->resolve('Linna\Foo\DI\FooClassResRules');

        $this->assertInstanceOf(\Linna\Foo\DI\FooClassResRules::class, $FOOClassResRules);
    }

    public function testResolveWithConstructorRules()
    {
        $resolver = new Resolver();
        $rules = [
            '\Linna\Foo\DI\FooClassARules' => [
                0 => true,
                2 => 'foo',
                3 => 1,
                4 => ['foo'],
                5 => 'foo',
            ],
        ];

        $FOOClassARules = $resolver->resolve('Linna\Foo\DI\FooClassARules', $rules);

        $this->assertInstanceOf(\Linna\Foo\DI\FooClassARules::class, $FOOClassARules);

        $FOOClassResRules = $resolver->resolve('Linna\Foo\DI\FooClassResRules');

        $this->assertInstanceOf(\Linna\Foo\DI\FooClassResRules::class, $FOOClassResRules);
    }
}
