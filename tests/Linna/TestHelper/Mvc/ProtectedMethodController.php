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

use Linna\Authentication\Authentication;
use Linna\Authentication\ProtectedControllerTrait;
use Linna\Mvc\Controller;
use Linna\Mvc\Model;

class ProtectedMethodController extends Controller
{
    use ProtectedControllerTrait;

    private $auth;

    public $test = false;

    public function __construct(Model $model, Authentication $authentication)
    {
        parent::__construct($model);

        $this->auth = $authentication;
    }

    public function ProtectedAction(): bool
    {
        $this->protect($this->auth, '/error');

        $this->test = true;

        return true;
    }

    public function ProtectedActionWithRedirect(): bool
    {
        $this->protectWithRedirect($this->auth, 'http://localhost', '/');

        $this->test = true;

        return true;
    }
}
