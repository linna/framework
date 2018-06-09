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

use Linna\Mvc\Controller;

/**
 * Multiple Controller.
 *
 * @property \Linna\TestHelper\Mvc\MultipleModel $model Multiple Model.
 */
class MultipleController extends Controller
{
    /**
     * Class Contructor.
     *
     * @param \Linna\TestHelper\Mvc\MultipleModel $model
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
