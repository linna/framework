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
class CalculatorSingleSubView extends View
{
    /**
     * Constructor.
     *
     * @param CalculatorSingleSubModel $model
     * @param JsonTemplate             $template
     */
    public function __construct(CalculatorSingleSubModel $model, JsonTemplate $template)
    {
        parent::__construct($model, $template);
    }

    /**
     * Entry Poitn, Sub.
     *
     * @return void
     */
    public function entryPoint(): void
    {
        $this->data['result'] = 'Sub: '.$this->data['result'];
    }
}
