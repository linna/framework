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

class BeforeAfterView extends View
{
    public function __construct(JsonTemplate $template)
    {
        parent::__construct($template);
    }

    public function Action()
    {
        $this->data['view'] = true;
    }
}
