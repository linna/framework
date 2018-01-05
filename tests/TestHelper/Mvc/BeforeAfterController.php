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

class BeforeAfterController extends Controller
{
    public function __construct(BeforeAfterModel $model)
    {
        parent::__construct($model);
    }

    public function beforeAction()
    {
        $this->model->sub();
    }

    public function Action($param)
    {
        $this->model->doAction($param);
    }

    public function afterAction()
    {
        $this->model->add();
    }

    public function before()
    {
        //do nothing
    }

    public function after()
    {
        $this->model->end();
    }
}
