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

use Linna\Mvc\Model;

class CalculatorModel extends Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function multiply(array $numbers)
    {
        $this->set(['result' => $this->operation('*', $numbers)]);
    }

    public function divide(array $numbers)
    {
        $this->set(['result' => $this->operation('/', $numbers)]);
    }

    public function sub(array $numbers)
    {
        $this->set(['result' => $this->operation('-', $numbers)]);
    }

    public function add(array $numbers)
    {
        $this->set(['result' => $this->operation('+', $numbers)]);
    }

    private function operation(string $operator, array $numbers)
    {
        $temp = null;

        foreach ($numbers as $n) {
            if ($temp === null) {
                $temp = $n;
                continue;
            }
            
            switch ($operator) {
                case '*':
                    $temp = $temp * $n;
                    break;
                case '/':
                    $temp = $temp / $n;
                    break;
                case '-':
                    $temp = $temp - $n;
                    break;
                case '+':
                    $temp = $temp + $n;
                    break;
            }
        }
        
        return $temp;
    }
}
