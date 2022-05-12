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
 * @property CalculatorSingleMultiplyController $model Calculator Model.
 */
class CalculatorSingleMultiplyController extends Controller
{
    use CalculatorFilterTrait;

    /**
     * Class Constructor.
     *
     * @param CalculatorSingleMultiplyModel $model
     */
    public function __construct(CalculatorSingleMultiplyModel $model)
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
        $this->model->multiply($numbers);
    }
}
