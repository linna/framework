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
class CalculatorSingleAddView extends View
{
    /**
     * Constructor.
     *
     * @param CalculatorSingleAddModel $model
     * @param JsonTemplate             $template
     */
    public function __construct(CalculatorSingleAddModel $model, JsonTemplate $template)
    {
        parent::__construct($model, $template);
    }

    /**
     * Entry Poitn, Add.
     *
     * @return void
     */
    public function entryPoint(): void
    {
        $this->data['result'] = 'Add: '.$this->data['result'];
    }
}
