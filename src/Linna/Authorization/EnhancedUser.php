<?php

declare(strict_types=1);

/**
 * This file is part of the Linna Framwork.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@tim.it>
 * @copyright (c) 2018, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */

namespace Linna\Authorization;

use Linna\Authentication\Password;
use Linna\Authentication\User;

/**
 * Enhanched User, an user with permissions.
 */
class EnhancedUser extends User
{
    use PermissionTrait;

    /**
     * Class Constructor.
     *
     * @param Password     $password    <code>Password</code> class instance.
     * @param array<mixed> $roles       The roles granted to the user.
     * @param array<mixed> $permissions The permission granted to the user.
     */
    public function __construct(
        Password $password,
        private array $roles = [],
        array $permissions = []
    ) {
        parent::__construct($password);

        $this->permission = $permissions;
    }

    /**
     * Check if an user has a role, use Role instance.
     *
     * @param Role $role The role as <code>Role<code> object which will be checked.
     *
     * @return bool True if the user has the role, false otherwise.
     */
    public function hasRole(Role $role): bool
    {
        return $this->hasRoleById($role->getId());
    }

    /**
     * Check if an user has a role, use role Id.
     *
     * @param string|int $roleId The role as role id or uuid which will be checked.
     *
     * @return bool True if the user has the role, false otherwise.
     */
    public function hasRoleById(string|int $roleId): bool
    {
        if (isset($this->roles[$roleId])) {
            return true;
        }

        return false;
    }

    /**
     * Check if an user has a role, use role name.
     *
     * @param string $roleName The role as role name which will be checked.
     *
     * @return bool True if the user has the role, false otherwise.
     */
    public function hasRoleByName(string $roleName): bool
    {
        if (\in_array($roleName, \array_column($this->roles, 'name'), true)) {
            return true;
        }

        return false;
    }
}
