<?php

declare(strict_types=1);

/**
 * This file is part of the Linna Framework.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@tim.it>
 * @copyright (c) 2018, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */

namespace Linna\Authorization;

/**
 * Role Trait.
 *
 * <p>Use it to add the role functionality to a class.</p>
 */
trait RoleTrait
{
    /** @var array<mixed> Roles. */
    protected array $role = [];

    /**
     * Check if the class that use the trait has a role, use the Role instance.
     *
     * @param Role $role The role as <code>Role<code> object which will be checked.
     *
     * @return bool True if has the role, false otherwise.
     */
    public function hasRole(Role $role): bool
    {
        return $this->hasRoleById($role->getId());
    }

    /**
     * Check if the class that use the trait has a role, use the role id.
     *
     * @param null|int|string $roleId The role as role id or uuid which will be checked.
     *
     * @return bool True if has the role, false otherwise.
     */
    public function hasRoleById(null|int|string $roleId): bool
    {
        //if (isset($this->role[$roleId])) {
        //    return true;
        //}

        if (\in_array($roleId, \array_column($this->role, 'id'), true)) {
            return true;
        }

        return false;
    }

    /**
     * Check if the class that use the trait has a role, use the role name.
     *
     * @param string $roleName The role as role name which will be checked.
     *
     * @return bool True if has the role, false otherwise.
     */
    public function hasRoleByName(string $roleName): bool
    {
        //if (isset($this->role[$roleName])) {
        //    return true;
        //}

        if (\in_array($roleName, \array_column($this->role, 'name'), true)) {
            return true;
        }

        return false;
    }
}
