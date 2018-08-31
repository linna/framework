<?php

/**
 * Linna Framework.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2018, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */
declare(strict_types=1);

namespace Linna\Tests;

use Linna\Http\Router;
use PHPUnit\Framework\TestCase;

/**
 * Class Options Trait test.
 */
class ClassOptionsTraitTest extends TestCase
{
    /**
     *
     * @var Router The router class.
     */
    protected $router;

    /**
     * Setup.
     */
    public function setUp(): void
    {
        $this->router = new Router();
    }

    /**
     * Test set option.
     */
    public function testSetOption(): void
    {
        $this->assertEmpty($this->router->setOption('badRoute', 'foo'));

        $this->assertTrue(true);
    }

    /**
     * Test set with wrong option.
     *
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage Class does not support the badRout option.
     */
    public function testSetWithWrongOption(): void
    {
        $this->assertEmpty($this->router->setOption('badRout', 'foo'));
    }

    /**
     * Test set options.
     */
    public function testSetOptions(): void
    {
        $this->assertEmpty($this->router->setOptions([
            'basePath'    => '/',
            'badRoute'    => 'E404',
            'rewriteMode' => true,
        ]));

        $this->assertTrue(true);
    }

    /**
     * Test set with wrong options.
     *
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage Class does not support the rewrite option.
     */
    public function testSetWithWrongOptions(): void
    {
        $this->assertEmpty($this->router->setOptions([
            'basePath'    => '/',
            'badRoute'    => 'E404',
            'rewrite'     => true,
        ]));
    }
}
