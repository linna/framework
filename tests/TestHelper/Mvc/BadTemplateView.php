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

class BadTemplateView extends View
{
    protected $template;

    public function __construct(CalculatorModel $model, $template)
    {
        parent::__construct($model);

        $this->template = $template;
    }
}
