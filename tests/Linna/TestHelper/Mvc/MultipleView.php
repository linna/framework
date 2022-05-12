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

class MultipleView extends View
{
    public function __construct(MultipleModel $model, JsonTemplate $template)
    {
        parent::__construct($model, $template);
    }

    public function SomeParam()
    {
    }
}
