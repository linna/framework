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
 * A view that implements  one action, one method per action.
 */
class CalculatorSingleDivideView extends View
{
    /**
     * Constructor.
     *
     * @param CalculatorSingleDivideModel $model
     * @param JsonTemplate                $template
     */
    public function __construct(CalculatorSingleDivideModel $model, JsonTemplate $template)
    {
        parent::__construct($model, $template);
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
