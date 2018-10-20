<?php

/**
 * Linna Framework.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2018, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */
declare(strict_types=1);

namespace Linna\Authorization;

use Linna\Authentication\Password;
use Linna\Authentication\User;

/**
 * Enhanched User.
 */
class EnhancedUser extends User
{
    use PermissionTrait;

    /**
     * @var array Contain roles for the user
     */
    private $roles = [];

    /**
     * Class Constructor.
     *
     * @param Password $password
     * @param array    $roles
     * @param array    $permissions
     */
    public function __construct(Password $password, array $roles = [], array $permissions = [])
    {
        parent::__construct($password);

        $this->roles = $roles;
        $this->permission = $permissions;
    }
}
