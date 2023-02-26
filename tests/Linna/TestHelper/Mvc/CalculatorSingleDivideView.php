<?php

declare(strict_types=1);

/**
 * This file is part of the Linna Framework.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@tim.it>
 * @copyright (c) 2018, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */

namespace Linna\TestHelper\Mvc;

use Linna\Mvc\View;

/**
 * Calculator View.
 * A view that implements  one action, one method per action.
 */
class CalculatorSingleDivideView extends View
{
    /**
     * Constructor.
     *
     * @param JsonTemplate $template
     */
    public function __construct(JsonTemplate $template)
    {
        parent::__construct($template);
    }

    /**
     * Entry Poitn, Divide.
     *
     * @return void
     */
    public function entryPoint(): void
    {
        $this->data['result'] = 'Divide: '.$this->data['result'];
    }
}
