<?php

/**
 * Linna Framework.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2018, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */
declare(strict_types=1);

use Linna\TestHelper\Mvc\MultipleModel;

use PHPUnit\Framework\TestCase;

/**
 * Model Test
 */
class ModelTest extends TestCase
{
    /**
     * Test new view instance.
     */
    public function testNewModelInstance(): void
    {
        $this->assertInstanceOf(MultipleModel::class, new MultipleModel());
    }

    /*
     * Test model get and set.
     */
    public function testModelGetAndSet(): void
    {
        $model = new MultipleModel();
        
        $model->set([1,2,3,4,5]);

        $this->assertEquals([1,2,3,4,5], $model->get());

        $model->set([6,7,8,9,10]);

        $this->assertEquals([1,2,3,4,5,6,7,8,9,10], $model->get());
    }
}
