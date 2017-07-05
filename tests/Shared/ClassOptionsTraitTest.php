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
        $this->router->setOption('badRoute', 'foo');

        $this->assertEquals(true, true);
    }

    /**
     * Test set with wrong option.
     * 
     * @expectedException InvalidArgumentException
     */
    public function testSetWithWrongOption()
    {
        $this->router->setOption('badRout', 'foo');
    }

    /**
     * Test set options.
     */
    public function testSetOptions()
    {
        $this->router->setOptions([
            'basePath'    => '/',
            'badRoute'    => 'E404',
            'rewriteMode' => true,
        ]);

        $this->assertEquals(true, true);
    }

    /**
     * Test set with wrong options.
     * 
     * @expectedException InvalidArgumentException
     */
    public function testSetWithWrongOptions()
    {
        $this->router->setOptions([
            'basePath'    => '/',
            'badRoute'    => 'E404',
            'rewrite'     => true,
        ]);
    }
}
