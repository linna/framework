<?php

declare(strict_types=1);

/**
 * This file is part of the Linna Framework.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@tim.it>
 * @copyright (c) 2018, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */

namespace Linna\Router\Exception;

use PHPUnit\Framework\TestCase;

/**
 * Redirect Exception test.
 */
class RedirectExceptionTest extends TestCase
{
    /**
     * Test new instance.
     *
     * @return void
     */
    public function testCreateInstance(): void
    {
        $this->assertInstanceOf(RedirectException::class, (new RedirectException()));
    }

    /**
     * Test Exception.
     *
     * @return void
     */
    public function testException(): void
    {
        try {
            throw new RedirectException('/new/route');
        } catch (RedirectException $e) {
            $this->assertEquals('/new/route', $e->getPath());
        }

        try {
            $exception = new RedirectException();
            $exception->setPath('/new/route');
        } catch (RedirectException $e) {
            $this->assertEquals('/new/route', $e->getPath());
        }
    }
}
