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

class FooControllerBeforeAfter extends Controller
{
    public function __construct(FOOModel $model)
    {
        parent::__construct($model);
    }

    public function beforeModifyDataTimed()
    {
        $this->model->addToData();
    }

    public function modifyDataTimed()
    {
        $this->model->modifyDataTimed();
    }

    public function afterModifyDataTimed()
    {
        $this->model->addToData();
    }

    public function before()
    {
        //do nothing
    }

    public function after()
    {
        //do nothing
    }
}
