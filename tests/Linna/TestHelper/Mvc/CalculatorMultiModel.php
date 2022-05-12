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
 * A model that implements more than one action, one method per action.
 */
class CalculatorMultiModel extends Model
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

    /**
     * Divide.
     *
     * @param array $numbers
     *
     * @return void
     */
    public function divide(array $numbers): void
    {
        $this->set(['result' => $this->operation('/', $numbers)]);
    }

    /**
     * Sub.
     *
     * @param array $numbers
     *
     * @return void
     */
    public function sub(array $numbers): void
    {
        $this->set(['result' => $this->operation('-', $numbers)]);
    }

    /**
     * Add.
     *
     * @param array $numbers
     *
     * @return void
     */
    public function add(array $numbers): void
    {
        $this->set(['result' => $this->operation('+', $numbers)]);
    }
}
