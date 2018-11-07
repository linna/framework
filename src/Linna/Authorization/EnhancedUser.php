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

    /**
     * Check if an user has a role, use Role instance.
     *
     * @param Role $role
     *
     * @return bool
     */
    public function hasRole(Role $role): bool
    {
        return $this->hasRoleById($role->getId());
    }

    /**
     * Check if an user has a role, use role Id.
     *
     * @param int $roleId
     *
     * @return bool
     */
    public function hasRoleById(int $roleId): bool
    {
        if (isset($this->roles[$roleId])) {
            return true;
        }

        return false;
    }

    /**
     * Check if an user has a role, use role name.
     *
     * @param string $roleName
     *
     * @return bool
     */
    public function hasRoleByName(string $roleName): bool
    {
        if (in_array($roleName, array_column($this->roles, 'name'), true)) {
            return true;
        }

        return false;
    }
}
