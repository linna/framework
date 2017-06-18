<?php

/**
 * Linna Framework.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2017, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */
declare(strict_types=1);

use Linna\Foo\Mvc\FooBadTemplateView;
use Linna\Foo\Mvc\FooModel;
use Linna\Foo\Mvc\FooTemplate;
use Linna\Foo\Mvc\FooView;
use PHPUnit\Framework\TestCase;

/**
 * View Test
 */
class ViewTest extends TestCase
{
    /**
     * Test new view instance.
     */
    public function testNewViewInstance()
    {
        $this->assertInstanceOf(FooView::class, new FooView(new FooModel(), new FooTemplate()));
    }

    /**
     * Test view with bad template.
     */
    public function testViewWithBadTemplate()
    {
        $this->assertInstanceOf(FooBadTemplateView::class, new FooBadTemplateView(new FooModel(), 'badTemplate'));
    }

    /**
     * Test view with bad template on render.
     * 
     * @expectedException UnexpectedValueException
     */
    public function testViewWithBadTemplateOnRender()
    {
        (new FooBadTemplateView(new FooModel(), 'badTemplate'))->render();
    }
}
