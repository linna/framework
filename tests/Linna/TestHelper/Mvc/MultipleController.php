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

use Linna\Mvc\Controller;
use Linna\TestHelper\Mvc\MultipleModel;

/**
 * Multiple Controller.
 *
 */
class MultipleController extends Controller
{
    /**
     * Class Contructor.
     *
     * @param MultipleModel $model
     */
    public function __construct(MultipleModel $model)
    {
        parent::__construct($model);
    }

    /**
     * Some Param.
     *
     * @param mixed $year
     * @param mixed $month
     * @param mixed $day
     */
    public function SomeParam($year, $month, $day)
    {
        $this->model->SomeParam((int) $year, (int) $month, (int) $day);
    }
}
