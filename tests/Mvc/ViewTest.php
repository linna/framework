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

use Linna\TestHelper\Mvc\CalculatorModel;
use Linna\TestHelper\Mvc\CalculatorView;
use Linna\TestHelper\Mvc\BadTemplateView;
use Linna\TestHelper\Mvc\JsonTemplate;
use PHPUnit\Framework\TestCase;

/**
 * View Test
 */
class ViewTest extends TestCase
{
    /**
     * Test new view instance.
     */
    public function testNewViewInstance(): void
    {
        $this->assertInstanceOf(CalculatorView::class, new CalculatorView(new CalculatorModel(), new JsonTemplate()));
    }

    /**
     * Test view with bad template.
     */
    public function testViewWithBadTemplate(): void
    {
        $this->assertInstanceOf(BadTemplateView::class, new BadTemplateView(new CalculatorModel(), 'badTemplate'));
    }

    /**
     * Test view with bad template on render.
     *
     * @expectedException UnexpectedValueException
     */
    public function testViewWithBadTemplateOnRender(): void
    {
        (new BadTemplateView(new CalculatorModel(), 'badTemplate'))->render();
    }
}
