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

use Linna\Authorization\Role;

/**
 * Fetch By Role Interface
 * Contain methods required from fetching by Role.
 */
interface FetchByRoleInterface
{
    /**
     * Fetch from role.
     * From a Role instance as argument, this method must return an array containing
     * a EnhancedUser|Permission instance for every EnhancedUser|Permission
     * that belongs at the given role.
     *
     * @param Role $role
     *
     * @return array
     */
    public function fetchByRole(Role $role): array;

    /**
     * Fetch from role.
     * From a role id as argument, this method must return an array containing
     * a EnhancedUser|Permission instance for every EnhancedUser|Permission
     * that belongs at the given role.
     *
     * @param int $roleId
     *
     * @return array
     */
    public function fetchByRoleId(int $roleId): array;

    /**
     * Fetch from role.
     * From a role name as argument, this method must return an array containing
     * a EnhancedUser|Permission instance for every EnhancedUser|Permission
     * that belongs at the given role.
     *
     * @param string $roleName
     *
     * @return array
     */
    public function fetchByRoleName(string $roleName): array;
}
