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
 * BeforeAfter Controller.
 *
 * @property \Linna\TestHelper\Mvc\BeforeAfterModel $model Before After Model.
 */
class BeforeAfterController extends Controller
{
    /**
     * Class Constructor.
     *
     * @param \Linna\TestHelper\Mvc\BeforeAfterModel $model
     */
    public function __construct(BeforeAfterModel $model)
    {
        parent::__construct($model);
    }

    /**
     * Before Action.
     */
    public function beforeAction()
    {
        $this->model->sub();
    }

    /**
     * Action.
     *
     * @param mixed $param
     */
    public function Action($param)
    {
        $this->model->doAction($param);
    }

    /**
     * After Action.
     */
    public function afterAction()
    {
        $this->model->add();
    }

    /**
     * Before.
     */
    public function before()
    {
        //do nothing
    }

    /**
     * After.
     */
    public function after()
    {
        $this->model->end();
    }
}
