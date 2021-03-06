<?php

/**
 * Linna Framework.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2018, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */
declare(strict_types=1);

namespace Linna\TestHelper\Mvc;

use Linna\Mvc\Controller;

/**
 * Calculator Controller.
 * A controller that implements one action, one method per action, default method entryPoint
 *
 * @property CalculatorSingleSubModel $model Calculator Model.
 */
class CalculatorSingleSubController extends Controller
{
    use CalculatorFilterTrait;

    /**
     * Class Constructor.
     *
     * @param CalculatorSingleSubModel $model
     */
    public function __construct(CalculatorSingleSubModel $model)
    {
        parent::__construct($model);
    }

    /**
     * Entry Point for this controller.
     *
     * @return void
     */
    public function entryPoint(): void
    {
        $numbers = $_POST['numbers'];

        $this->filter($numbers);
        $this->model->sub($numbers);
    }
}
