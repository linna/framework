<?php

/**
 * Linna Framework.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2017, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */
declare(strict_types=1);

namespace Linna\TestHelper\Mvc;

use Linna\Mvc\View;

class MultipleView extends View
{
    protected $template;

    public function __construct(MultipleModel $model, JsonTemplate $template)
    {
        parent::__construct($model);

        $this->template = $template;
    }
    
    public function SomeParam()
    {
    }
}
