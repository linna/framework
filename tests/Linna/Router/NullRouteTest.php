<?php

declare(strict_types=1);

/**
 * This file is part of the Linna Framework.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@tim.it>
 * @copyright (c) 2018, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */

namespace Linna\Router;

use PHPUnit\Framework\TestCase;

/**
 * Null Route Test.
 */
class NullRouteTest extends TestCase
{
    /**
     * Test new null route instance.
     *
     * @return void
     */
    public function testNewNullRouteInstance(): void
    {
        $this->assertInstanceOf(NullRoute::class, new NullRoute());
    }
}
