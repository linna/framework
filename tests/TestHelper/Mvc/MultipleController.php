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

class MultipleController extends Controller
{
    public function __construct(MultipleModel $model)
    {
        parent::__construct($model);
    }

    //public function modifyData()
    //{
    //    $this->model->modifyData();
    //}

    //public function modifyDataFromParam($passedData)
    //{
    //    $this->model->modifyDataFromParam($passedData);
    //}
    
    public function SomeParam($year, $month, $day)
    {
        $this->model->SomeParam((int) $year, (int) $month, (int) $day);
    }
}
