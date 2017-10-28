<?php

/**
 * Linna Framework.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2017, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */
declare(strict_types=1);

namespace Linna\TestHelper\Mvc;

use InvalidArgumentException;
use Linna\Mvc\Controller;

class CalculatorController extends Controller
{
    public function __construct(CalculatorModel $model)
    {
        parent::__construct($model);
    }

    public function multiply()
    {
        $numbers = $_POST['numbers'];
        
        $this->model->multiply($numbers);
    }

    public function divide()
    {
        $numbers = $_POST['numbers'];
        
        $this->model->divide($numbers);
    }

    public function sub()
    {
        $numbers = $_POST['numbers'];
        
        $this->model->sub($numbers);
    }

    public function add()
    {
        $numbers = $_POST['numbers'];
        
        $this->model->add($numbers);
    }

    public function filter($numbers)
    {
        foreach ($numbers as $key => $number) {
            switch (gettype($number)) {
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
