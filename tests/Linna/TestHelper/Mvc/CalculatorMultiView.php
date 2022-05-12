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

use Linna\Mvc\View;

/**
 * Calculator View.
 * A view that implements more than one action, one method per action.
 */
class CalculatorMultiView extends View
{
    /**
     * Constructor.
     *
     * @param CalculatorMultiModel $model
     * @param JsonTemplate         $template
     */
    public function __construct(CalculatorMultiModel $model, JsonTemplate $template)
    {
        parent::__construct($model, $template);
    }

    /**
     * Multiply.
     *
     * @return void
     */
    public function multiply(): void
    {
        $this->data['result'] = 'Multiply: '.$this->data['result'];
    }

    /**
     * Divide.
     *
     * @return void
     */
    public function divide(): void
    {
        $this->data['result'] = 'Divide: '.$this->data['result'];
    }

    /**
     * Sub.
     *
     * @return void
     */
    public function sub(): void
    {
        $this->data['result'] = 'Sub: '.$this->data['result'];
    }

    /**
     * Add.
     *
     * @return void
     */
    public function add(): void
    {
        $this->data['result'] = 'Add: '.$this->data['result'];
    }
}
