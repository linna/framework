<?php

/**
 * Linna App
 *
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2016, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 *
 */

namespace Linna\FOO;

use Linna\Mvc\Controller;
use Linna\FOO\FOOModel;

class FOOController extends Controller
{
    public function __construct(FOOModel $model)
    {
        parent::__construct($model);
    }
    
    public function modifyData()
    {
        $this->model->modifyData();
    }
}
