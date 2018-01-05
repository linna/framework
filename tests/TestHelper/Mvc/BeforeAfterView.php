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

class BeforeAfterView extends View
{
    protected $template;

    public function __construct(BeforeAfterModel $model, JsonTemplate $template)
    {
        parent::__construct($model);

        $this->template = $template;
    }

    public function Action()
    {
    }
}
