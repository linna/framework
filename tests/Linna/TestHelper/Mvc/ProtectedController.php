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

class ProtectedController extends Controller
{
    use ProtectedControllerTrait;

    public $test = false;

    public function __construct(Model $model, Authentication $authentication)
    {
        parent::__construct($model);

        $this->protect($authentication, '/error');

        $this->test = true;
    }

    public function action(): bool
    {
        if ($this->authentication === false) {
            return false;
        }

        return true;
    }
}
