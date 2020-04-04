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

/**
 * Provide a filtering service for Calculator.
 */
trait CalculatorFilterTrait
{
    /**
     * Filter.
     *
     * @param array $numbers
     *
     * @return void
     *
     * @throws InvalidArgumentException
     */
    private function filter(array &$numbers): void
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
