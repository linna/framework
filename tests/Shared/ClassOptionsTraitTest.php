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
    protected $router;

    public function setUp()
    {
        $this->router = new Router();
    }

    public function testSetOption()
    {
        $this->router->setOption('badRoute', 'foo');

        $this->assertEquals(true, true);
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testSetBadOption()
    {
        $this->router->setOption('badRout', 'foo');
    }

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
     * @expectedException InvalidArgumentException
     */
    public function testSetBadOptions()
    {
        $this->router->setOptions([
            'basePath'    => '/',
            'badRoute'    => 'E404',
            'rewrite' => true,
        ]);
    }
}
