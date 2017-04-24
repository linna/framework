<?php

/**
 * Linna Framework.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2017, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */
declare(strict_types=1);

use Linna\Http\NullRoute;
use PHPUnit\Framework\TestCase;

class NullRouteTest extends TestCase
{
    public function testCreateRoute()
    {
        $this->assertInstanceOf(NullRoute::class, new NullRoute());
    }

    public function testToArray(): array
    {
        $this->assertEquals([], (new NullRoute())->toArray());
    }
}
