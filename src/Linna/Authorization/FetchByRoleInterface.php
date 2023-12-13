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

use Linna\Authorization\Role;

/**
 * Fetch By Role Interface.
 *
 * Contain methods required to fetch users or permissions by role.
 */
interface FetchByRoleInterface
{
    /**
     * Fetch from role.
     *
     * <p>From a <code>Role</code> instance as argument, this method must return an array
     * containing a <code>User|Permission</code> instance for every
     * <code>User|Permission</code> that belongs at the given role.</p>
     *
     * @param Role $role The role which will be used to fetch as <code>Role</code> instance.
     *
     * @return array<mixed> Users or permissions which belong the role.
     */
    public function fetchByRole(Role $role): array;

    /**
     * Fetch from role.
     *
     * <p>From a role id as argument, this method must return an array containing
     * a <code>User|Permission</code> instance for every <code>User|Permission</code>
     * that belongs at the given role.</p>
     *
     * @param int|string $roleId The role which will be used to fetch as role id.
     *
     * @return array<mixed> Users or permissions which belong the role.
     */
    public function fetchByRoleId(int|string $roleId): array;

    /**
     * Fetch from role.
     *
     * <p>From a role name as argument, this method must return an array containing
     * a <code>User|Permission</code> instance for every <code>User|Permission</code>
     * that belongs at the given role.</p>
     *
     * @param string $roleName The role which will be used to fetch as role name.
     *
     * @return array<mixed> Users or permissions which belong the role.
     */
    public function fetchByRoleName(string $roleName): array;
}
