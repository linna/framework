<?php

/**
 * Linna Framework.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2017, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */
declare(strict_types=1);

namespace Linna\TestHelper\Mvc;

use Linna\Authentication\Authenticate;
use Linna\Authentication\ProtectedController;
use Linna\Mvc\Controller;

class MultipleProtectedController extends Controller
{
    use ProtectedController;

    public $test = false;

    public function __construct(MultipleModel $model, Authenticate $login)
    {
        parent::__construct($model);

        $this->protect($login, 'http://localhost');

        $this->test = true;
    }

    public function ProtectedAction() : bool
    {
        if ($this->authentication === false) {
            return false;
        }
        
        return true;
    }
}
