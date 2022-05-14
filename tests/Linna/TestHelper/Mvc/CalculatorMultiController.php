<?php

/**
 * Linna Framework.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@tim.it>
 * @copyright (c) 2018, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */
declare(strict_types=1);

namespace Linna\TestHelper\Mvc;

use Linna\Mvc\Controller;
use Linna\TestHelper\Mvc\CalculatorMultiModel;

/**
 * Calculator Controller.
 * A controller that implements more than one action, one method per action.
 *
 * @property CalculatorMultiModel $model Calculator Model.
 */
class CalculatorMultiController extends Controller
{
    use CalculatorFilterTrait;

    /**
     * Class Constructor.
     *
     * @param CalculatorMultiModel $model
     */
    public function __construct(CalculatorMultiModel $model)
    {
        parent::__construct($model);
    }

    /**
     * Multiply.
     *
     * @return void
     */
    public function multiply(): void
    {
        $numbers = $_POST['numbers'];

        $this->filter($numbers);
        $this->model->multiply($numbers);
    }

    /**
     * Divide.
     *
     * @return void
     */
    public function divide(): void
    {
        $numbers = $_POST['numbers'];

        $this->filter($numbers);
        $this->model->divide($numbers);
    }

    /**
     * Sub.
     *
     * @return void
     */
    public function sub(): void
    {
        $numbers = $_POST['numbers'];

        $this->filter($numbers);
        $this->model->sub($numbers);
    }

    /**
     * Add.
     *
     * @return void
     */
    public function add(): void
    {
        $numbers = $_POST['numbers'];

        $this->filter($numbers);
        $this->model->add($numbers);
    }
}
