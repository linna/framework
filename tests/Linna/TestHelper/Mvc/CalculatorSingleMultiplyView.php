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

use Linna\Mvc\View;

/**
 * Calculator View.
 * A view that implements  one action, one method per action.
 */
class CalculatorSingleMultiplyView extends View
{
    /**
     * Constructor.
     *
     * @param JsonTemplate  $template
     */
    public function __construct(JsonTemplate $template)
    {
        parent::__construct($template);
    }

    /**
     * Entry Poitn, Multiply.
     *
     * @return void
     */
    public function entryPoint(): void
    {
        $this->data['result'] = 'Multiply: '.$this->data['result'];
    }
}
