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

use Linna\Authentication\Authentication;
use Linna\Authentication\ProtectedController;
use Linna\Mvc\Controller;
use Linna\Mvc\Model;

class ProtectedControllerWithRedirect extends Controller
{
    use ProtectedController;

    public $test = false;

    public function __construct(Model $model, Authentication $authentication)
    {
        parent::__construct($model);

        $this->protectWithRedirect($authentication, 'http://localhost');

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
