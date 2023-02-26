<?php

declare(strict_types=1);

/**
 * This file is part of the Linna Framework.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@tim.it>
 * @copyright (c) 2018, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */

namespace Linna\Mvc;

use Linna\TestHelper\Mvc\CalculatorMultiView;
use Linna\TestHelper\Mvc\BadTemplateView;
use Linna\TestHelper\Mvc\JsonTemplate;
use PHPUnit\Framework\TestCase;
use TypeError;

/**
 * View Test
 */
class ViewTest extends TestCase
{
    /**
     * Test new view instance.
     *
     * @return void
     */
    public function testNewViewInstance(): void
    {
        $this->assertInstanceOf(CalculatorMultiView::class, new CalculatorMultiView(new JsonTemplate()));
    }

    /**
     * Test view with bad template.
     *
     * return void
     */
    public function testViewWithBadTemplate(): void
    {
        $this->expectException(TypeError::class);

        $this->assertInstanceOf(BadTemplateView::class, new BadTemplateView('badTemplate'));
    }
}
