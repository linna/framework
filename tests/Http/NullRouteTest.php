<?php

/**
 * Linna Framework.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2018, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */
declare(strict_types=1);

use Linna\Http\NullRoute;
use PHPUnit\Framework\TestCase;

/**
 * Null Route Test.
 */
class NullRouteTest extends TestCase
{
    /**
     * Test new null route instance.
     */
    public function testNewNullRouteInstance()
    {
        $this->assertInstanceOf(NullRoute::class, new NullRoute());
    }
    
    /**
     * Test null route to array.
     */
    public function testNullRouteToArray()
    {
        $this->assertEquals([], (new NullRoute())->toArray());
    }
}
