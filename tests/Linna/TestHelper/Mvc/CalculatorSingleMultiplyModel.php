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

use Linna\Mvc\Model;

/**
 * Calculator Model.
 * A model that implements  one action, one method per action.
 */
class CalculatorSingleMultiplyModel extends Model
{
    use CalculatorOperationTrait;

    /**
     * Constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Multiply.
     *
     * @param array $numbers
     *
     * @return void
     */
    public function multiply(array $numbers): void
    {
        $this->set(['result' => $this->operation('*', $numbers)]);
    }
}
