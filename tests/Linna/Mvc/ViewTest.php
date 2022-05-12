<?php

/**
 * Linna Framework.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@tim.it>
 * @copyright (c) 2018, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */
declare(strict_types=1);

namespace Linna\Mvc;

use Linna\TestHelper\Mvc\CalculatorMultiModel;
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
        $this->assertInstanceOf(CalculatorMultiView::class, new CalculatorMultiView(new CalculatorMultiModel(), new JsonTemplate()));
    }

    /**
     * Test view with bad template.
     *
     * return void
     */
    public function testViewWithBadTemplate(): void
    {
        $this->expectException(TypeError::class);

        $this->assertInstanceOf(BadTemplateView::class, new BadTemplateView(new CalculatorMultiModel(), 'badTemplate'));
    }
}
