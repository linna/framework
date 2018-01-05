<?php

/**
 * Linna Framework.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2018, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */
declare(strict_types=1);

use Linna\Http\Router;
use PHPUnit\Framework\TestCase;

/**
 * Class Options Trait test.
 */
class ClassOptionsTest extends TestCase
{
    /**
     *
     * @var Router The router class.
     */
    protected $router;

    /**
     * Setup.
     */
    public function setUp()
    {
        $this->router = new Router();
    }

    /**
     * Test set option.
     */
    public function testSetOption()
    {
        $this->assertEmpty($this->router->setOption('badRoute', 'foo'));

        $this->assertTrue(true);
    }

    /**
     * Test set with wrong option.
     *
     * @expectedException InvalidArgumentException
     */
    public function testSetWithWrongOption()
    {
        $this->assertEmpty($this->router->setOption('badRout', 'foo'));
    }

    /**
     * Test set options.
     */
    public function testSetOptions()
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
     */
    public function testSetWithWrongOptions()
    {
        $this->assertEmpty($this->router->setOptions([
            'basePath'    => '/',
            'badRoute'    => 'E404',
            'rewrite'     => true,
        ]));
    }
}
