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

use Linna\TestHelper\Mvc\MultipleModel;
use PHPUnit\Framework\TestCase;

/**
 * Model Test
 */
class ModelTest extends TestCase
{
    /**
     * Test new view instance.
     *
     * @return void
     */
    public function testNewModelInstance(): void
    {
        $this->assertInstanceOf(MultipleModel::class, new MultipleModel());
    }

    /*
     * Test model get and set.
     *
     * @return void
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
