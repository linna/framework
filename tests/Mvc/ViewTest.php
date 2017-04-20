<?php

/**
 * Linna Framework.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2017, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */
declare(strict_types=1);

use Linna\Foo\Mvc\FooModel;
use Linna\Foo\Mvc\FooTemplate;
use Linna\Foo\Mvc\FooBadTemplateView;
use Linna\Foo\Mvc\FooView;
use PHPUnit\Framework\TestCase;

class ViewTest extends TestCase
{
    public function testCreateView()
    {
       $this->assertInstanceOf(FooView::class, new FooView(new FooModel(), new FooTemplate()));
    }
    
    public function testBadTemplateView()
    {
       $this->assertInstanceOf(FooBadTemplateView::class, new FooBadTemplateView(new FooModel(), 'badTemplate'));
    }
    
    /**
     * @expectedException UnexpectedValueException
     */
    public function testBadTemplateOnRender()
    {
        (new FooBadTemplateView(new FooModel(), 'badTemplate'))->render();
    }
}