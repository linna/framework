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

use InvalidArgumentException;
use Linna\Mvc\Controller;

/**
 * Calculator Class.
 *
 * @property \Linna\TestHelper\Mvc\CalculatorModel $model Calculator Model.
 */
class CalculatorController extends Controller
{
    /**
     * Class Constructor.
     *
     * @param \Linna\TestHelper\Mvc\CalculatorModel $model
     */
    public function __construct(CalculatorModel $model)
    {
        parent::__construct($model);
    }

    /**
     * Multiply.
     */
    public function multiply()
    {
        $numbers = $_POST['numbers'];

        $this->model->multiply($numbers);
    }

    /**
     * Divide.
     */
    public function divide()
    {
        $numbers = $_POST['numbers'];

        $this->model->divide($numbers);
    }

    /**
     * Sub.
     */
    public function sub()
    {
        $numbers = $_POST['numbers'];

        $this->model->sub($numbers);
    }

    /**
     * Add.
     */
    public function add()
    {
        $numbers = $_POST['numbers'];

        $this->model->add($numbers);
    }

    /**
     * Filter.
     *
     * @param array $numbers
     * @throws InvalidArgumentException
     */
    public function filter(array $numbers)
    {
        foreach ($numbers as $key => $number) {
            switch (\gettype($number)) {
                case 'string':
                    $number[$key] = (int) $number;
                    break;
                case 'integer':
                    break;
                case 'double':
                    break;
                default:
                    throw new InvalidArgumentException('Not a number');
            }
        }
    }
}
