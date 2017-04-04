<?php

/**
 * Linna App.
 *
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2017, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */

namespace Linna\Foo\Mvc;

use Linna\Mvc\Controller;

class FooController extends Controller
{
    public function __construct(FOOModel $model)
    {
        parent::__construct($model);
    }

    public function modifyData()
    {
        $this->model->modifyData();
    }

    public function modifyDataFromParam($passedData)
    {
        $this->model->modifyDataFromParam($passedData);
    }
}
