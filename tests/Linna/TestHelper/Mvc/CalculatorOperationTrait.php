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

/**
 * Provide a math operation service for Calculator.
 */
trait CalculatorOperationTrait
{
    /**
     * Perform mathematical operation.
     *
     * @param string $operator
     * @param array  $numbers
     *
     * @return mixed
     */
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
