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

use Linna\Authentication\Authenticate;
use Linna\Authentication\ProtectedController;
use Linna\Mvc\Controller;

class FooProtectedController extends Controller
{
    use ProtectedController;

    public $test = false;

    public function __construct(FOOModel $model, Authenticate $login)
    {
        parent::__construct($model);

        $this->protect($login, 'http://localhost');

        $this->test = true;
    }

    public function fooAction() : bool
    {
        if ($this->authentication === false) {
            return false;
        }
        
        return true;
    }
}
